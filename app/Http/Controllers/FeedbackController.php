<?php

namespace App\Http\Controllers;

use App\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function create(Request $request) {
      $feedback = new Feedback();
      $feedback->user_id = Auth::id();
      $feedback->body = $request->body;
      $feedback->save();
      return $feedback;
    }

    public function browse(Request $request) {
      $feedbacks = Feedback::where('user_id', Auth::id())->paginate(10);
      return $feedbacks;
    }

    public function delete(Request $request) {
      $feedback = Feedback::where('id', $request->id)->delete();
    }
}
