<?php

namespace App\Http\Controllers;

use App\Admin;
use App\ContactMessage;
use App\Feedback;
use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth:admin');
    }

    public function browseUsers(Request $request) {
      $users = User::query();

      if (isset($request->searchQuery)) {
        $users = $users->where('name', 'like', '%'.$request->searchQuery.'%')
          ->orWhere('email', 'like', '%'.$request->searchQuery.'%');
      }

      $users = $users->paginate(10);

      foreach ($users as &$user) {
        $user['subscription'] = $user->subscription('main');
        $user['isSubscribed'] = $user->subscribed('main');
        if ($user->subscribed('main')) {
          $user['onGracePeriod'] = $user->subscription('main')->onGracePeriod();
        }
      }
      return $users;
    }

    public function browseAdmins() {
      $users = Admin::paginate(10);
      return $users;
    }

    public function createAdmin(Request $request) {
      $admin = new Admin();
      $admin->name = $request->name;
      $admin->email = $request->email;
      $admin->password = bcrypt($request->password);
      $admin->root = 0;
      $admin->save();

      return $admin;
    }

    public function deleteAdmin(Request $request) {
      Admin::where('id', $request->id)->delete();
    }

    public function browseFeedback() {
      $feedbacks = Feedback::with('user')->paginate(10);
      return $feedbacks;
    }

    public function browseContactMessages() {
      $contacts = ContactMessage::paginate(10);
      return $contacts;
    }

    public function resumeSubscription(Request $request) {
      $user = User::find($request->id);
      $user->subscription('main')->resume();
    }

    public function cancelSubscription(Request $request) {
      $user = User::find($request->id);
      $user->subscription('main')->cancel();
      $subscription = $user->subscription('main');
      return $subscription;
    }

    public function getDashboardData() {
      $usersCount = User::count();
      $adminsCount = Admin::count();
      $feedbackCount = Feedback::count();

      return [
        'usersCount' => $usersCount,
        'adminsCount' => $adminsCount,
        'feedbackCount' => $feedbackCount
      ];
    }
}
