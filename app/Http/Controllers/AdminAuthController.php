<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function init(){
      $user = Auth::guard('admin')->user();
      return [
        'user' => $user
      ];
    }

    public function login(Request $request) {
      if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
        return redirect()->to('/admin');
      } else {
        return redirect()->back()->with(['error' => 'Could not log you in']);
      }
    }

    public function logout() {
      Auth::guard('admin')->logout();
    }
}
