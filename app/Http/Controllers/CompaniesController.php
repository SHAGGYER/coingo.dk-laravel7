<?php

namespace App\Http\Controllers;

use App\Company;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompaniesController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function create(Request $request){
    $company = new Company();
    $company->title = $request->title;
    $company->cvr = $request->cvr;
    $company->invoice_number = 1;
    $company->address = $request->address;
    $company->city = $request->city;
    $company->zip = $request->zip;
    $company->country = $request->country;
    $company->reg_nr = $request->regNr;
    $company->account_number = $request->accountNumber;
    $company->phone = $request->phone;
    $company->email = $request->email;
    $company->save();

    $user = Auth::user();
    $user->company_id = $company->id;
    $user->save();


    $user = User::with('company')->where('id', Auth::id())->first();
    $user['subscription'] = $user->subscription('main');
    $user['stripeClientSecret'] = $user->createSetupIntent()->client_secret;
    $card = $user->defaultPaymentMethod();
    if ($card) {
      $user['card'] = $card->asStripePaymentMethod();
    }
    $data['user'] = $user;
    $data['company'] = $company;
    return $data;
  }

  public function update(Request $request) {
    $company = Company::find(Auth::user()->company_id);
    $company->title = $request->title;
    $company->cvr = $request->cvr;
    $company->reg_nr = $request->regNr;
    $company->address = $request->address;
    $company->city = $request->city;
    $company->zip = $request->zip;
    $company->country = $request->country;
    $company->account_number = $request->accountNumber;
    $company->invoice_number = $request->invoiceNumber;
    $company->phone = $request->phone;
    $company->email = $request->email;
    $company->save();

    return $company;
  }


  public function show($id) {
    $company = Company::find($id);
    if (!isset($company->id)) {
      return response()->json(['message' => 'Could not find this contact'], 404);
    }

    return $company;
  }

  public function delete($id) {
    $company = Company::find($id);
    $company->delete();
  }
}
