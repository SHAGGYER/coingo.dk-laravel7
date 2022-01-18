<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function create(Request $request){
    $contact = new Contact();
    $contact->company_id = \Auth::user()->company_id;
    $contact->type_contact = $request->typeContact;
    $contact->type = $request->type;
    $contact->name = $request->name;
    $contact->address = $request->address;
    $contact->zip = $request->zip;
    $contact->city = $request->city;
    $contact->email = $request->email;
    $contact->phone = $request->phone;
    $contact->country = $request->country;
    $contact->cvr = $request->cvr;
    $contact->ean = $request->ean;
    $contact->website = $request->website;
    $contact->save();

    return $contact;
  }

  public function update(Request $request) {
    $contact = Contact::find($request->id);
    $contact->name = $request->name;
    $contact->address = $request->address;
    $contact->zip = $request->zip;
    $contact->city = $request->city;
    $contact->email = $request->email;
    $contact->phone = $request->phone;
    $contact->country = $request->country;
    $contact->cvr = $request->cvr;
    $contact->ean = $request->ean;
    $contact->website = $request->website;
    $contact->save();

    return $contact;
  }

  public function browse(Request $request) {
    $contacts = Contact::query()->where('company_id', \Auth::user()->company_id);

    $contacts = $contacts->where('type_contact', '=', $request->typeContact);

    if (isset($request->searchQuery)) {
      $contacts = $contacts->where('name', 'like', '%'.$request->searchQuery.'%');
    }

    if (isset($request->paginate)) {
      $contacts = $contacts->paginate(10);
    } else {
      $contacts = $contacts->get();
    }

    return $contacts;
  }

  public function show($id) {
    $contact = Contact::find($id);
    if (!isset($contact->id)) {
      return response()->json(['message' => 'Could not find this contact'], 404);
    }

    return $contact;
  }

  public function delete($id) {
    $contact = Contact::find($id);
    $contact->delete();
  }
}
