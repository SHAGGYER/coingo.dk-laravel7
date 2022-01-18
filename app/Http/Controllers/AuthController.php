<?php

namespace App\Http\Controllers;

use App\ContactMessage;
use App\Mail\NewPassword;
use App\Mail\VerifyEmail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function init() {
      $user = User::with('company')->where('id', Auth::id())->first();
      $data['user'] = $user;

      if (Auth::check()) {
        $user['subscription'] = $user->subscription('main');
        $user['stripeClientSecret'] = Auth::user()->createSetupIntent()->client_secret;
        $card = $user->defaultPaymentMethod();
        if ($card) {
          $user['card'] = $card->asStripePaymentMethod();
        }
        $data['isSubscribed'] = $user->subscribed('main');
        if ($user->subscribed('main')) {
          $data['onGracePeriod'] = $user->subscription('main')->onGracePeriod();

        }
      }

      return $data;
    }

    public function register(Request $request) {
      $user = User::where('email', $request->email)->first();
      if (isset($user->id)){
        return redirect()->back()->with(['message' => 'Denne email eksisterer allerede']);
      }

      if ($request->password !== $request->password_confirmation) {
        return redirect()->back()->with(['message' => 'Kodeord skal være ens']);
      }

      $user = new User();
      $user->name = $request->name;
      $user->email = $request->email;
      $user->password = bcrypt($request->password);
      $user->email_verified = 0;

      $token = Str::random(32);
      $user->email_validation_token = $token;
      $user->save();

      Auth::login($user, true);

      return redirect()->to('/app');
    }

    public function login(Request $request) {
      if (Auth::attempt(['email' => $request->email, 'password' => $request->password], true)) {
        return redirect()->to('/app');
      } else {
        return redirect()->back()->with(['message' => 'Kunne ikke logge dig ind']);
      }
    }

    public function forgotPassword(Request $request) {
      $toEmail = $request->email;

      $user = User::where('email', $toEmail)->first();
      if (!isset($user->id)) {
        return redirect()->back()->withErrors(['message' => 'Denne email findes ikke i vores database.']);
      }

      $user->password_reset_token = Str::random(12);
      $user->save();

      Mail::to($user)->send(new NewPassword($user->password_reset_token));

      return redirect()->back()->with(['message' => 'Vi har sendt en email til dig med instrukser. Tjek også din spam mappe.']);
    }

    public function newPassword(Request $request, $token) {
      $user = User::where('password_reset_token', $token)->first();
      if (!isset($user->id)) {
        return view('errors.401');
      }

      return view('auth.newPassword', ['token' => $token]);
    }

    public function resetPassword(Request $request) {
      if ($request->password != $request->password_confirmation){
        return redirect()->back()->withErrors(['message' => 'Kodeord skal være ens']);
      }

      $user = User::where('password_reset_token', $request->token)->first();
      $user->password = bcrypt($request->password);
      $user->password_reset_token = null;
      $user->save();

      return redirect()->to('/login');
    }

    public function checkVerifyEmailToken(Request $request) {
      $user = User::where('email_validation_token', $request->token)->first();
      if (!isset($user->id)) {
        return response()->json(['message' => 'Forkert token'], 401);
      }

      $user->email_verified = 1;
      $user->email_validation_token = '';
      $user->save();
    }

    public function sendContactMessage(Request $request) {
      $message = new ContactMessage();
      $message->name = $request->name;
      $message->email = $request->email;
      $message->body = $request->body;
      $message->save();

      return redirect()->back();
    }


    public function logout() {
      Auth::logout();
    }
}
