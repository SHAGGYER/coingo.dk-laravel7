<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Invoice;
use App\Item;
use App\Mail\VerifyEmail;
use App\Place;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function getDashboardData() {
    $companyId = Auth::user()->company_id;

    $placesCount = Place::where('company_id', $companyId)->count();
    $productsCount = Item::where('company_id', $companyId)->count();
    $customersCount = Contact::where([
      ['company_id', '=', $companyId],
      ['type_contact', '=', 'customer']
    ])->count();
    $suppliersCount = Contact::where([
      ['company_id', '=', $companyId],
      ['type_contact', '=', 'supplier']
    ])->count();
    $sellInvoicesCount = Invoice::where([
      ['company_id', '=', $companyId],
      ['type', '=', 'sell']
    ])->count();
    $remindersCount = Invoice::where([
      ['company_id', '=', $companyId],
      ['type', '=', 'reminder']
    ])->count();
    $buyInvoicesCount = Invoice::where([
      ['company_id', '=', $companyId],
      ['type', '=', 'buy']
    ])->count();

    return [
      'placesCount' => $placesCount,
      'productsCount' => $productsCount,
      'customersCount' => $customersCount,
      'suppliersCount' => $suppliersCount,
      'sellInvoicesCount' => $sellInvoicesCount,
      'remindersCount' => $remindersCount,
      'buyInvoicesCount' => $buyInvoicesCount
    ];
  }

  public function changeName(Request $request) {
    $user = Auth::user();
    $user->name = $request->name;
    $user->save();
  }

  public function resendEmailVerification() {
    $user = Auth::user();
    $token = $user->email_validation_token;
    Mail::to($user->email)->send(new VerifyEmail($token));
  }

  public function changeEmail(Request $request) {
    $email = $request->email;

    $user = User::where('email', $email)->first();
    if (isset($user->id)) {
      return response()->json(['message' => 'Denne email eksisterer allerede'], 401);
    }

    $user = Auth::user();
    $user->email = $email;
    $user->email_verified = 0;

    $token = Str::random(32);
    $user->email_validation_token = $token;
    $user->save();
    Mail::to($user->email)->send(new VerifyEmail($token));
  }

  public function changePassword(Request $request) {
    $password = $request->password;
    $user = Auth::user();
    $user->password = bcrypt($password);
    $user->save();
  }

  public function downloadInvoice($invoiceId) {
    $user = Auth::user();
    return $user->downloadInvoice($invoiceId, [
      'vendor' => 'MBF Warehouse',
      'product' => 'MBF Warehouse Software',
    ]);
  }

  public function addCard(Request $request) {
    $user = Auth::user();

    if (!$user->stripe_id) {
      $user->createAsStripeCustomer();
    }

    $user->updateDefaultPaymentMethod($request->paymentMethod);
    $paymentMethod = $user->defaultPaymentMethod()->asStripePaymentMethod();
    return response()->json($paymentMethod, 200);
  }

  public function deleteCard() {
    $user = Auth::user();
    $user->defaultPaymentMethod()->delete();
  }

  public function getCardInfo() {
    $user = Auth::user();
    $paymentMethod = $user->defaultPaymentMethod();
    if ($paymentMethod) {
      $paymentMethod = $paymentMethod->asStripePaymentMethod();
    }
    return response()->json(['paymentMethod' => $paymentMethod], 200);
  }

  public function getInvoices() {
    $user = Auth::user();
    $invoices = $user->invoices();

    $_invoices = [];

    foreach ($invoices as $invoice) {
      $_invoices[] = [
        'date' => $invoice->date()->toFormattedDateString(),
        'total' => $invoice->total(),
        'id' => $invoice->id
      ];
    }

    return [
      'invoices' => $_invoices,
    ];
  }

  public function changePlan(Request $request) {
    $user = User::find(Auth::id());
    $user->subscription('main')->swap($request->plan);
    $subscription = $user->subscription('main');
    return $subscription;
  }

  public function addPlan(Request $request) {
    $user = User::find(Auth::id());
    $paymentMethod = $user->defaultPaymentMethod();
    $user->newSubscription('main', $request->plan)->create($paymentMethod->id);
    $subscription = $user->subscription('main');
    return $subscription;
  }

  public function resumePlan() {
    $user = Auth::user();
    $user->subscription('main')->resume();
  }

  public function cancelPlan() {
    $user = Auth::user();
    $user->subscription('main')->cancel();
    $subscription = $user->subscription('main');
    return $subscription;
  }

  public function updateHasSeenTour() {
    $user = User::with('company')->where('id', Auth::id())->first();
    $user->has_seen_tour = 1;
    $user->save();

    return $user;
  }
}
