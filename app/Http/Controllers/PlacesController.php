<?php

namespace App\Http\Controllers;

use App\Place;
use Illuminate\Http\Request;

class PlacesController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function create(Request $request){
      $place = new Place();
      $place->company_id = \Auth::user()->company_id;
      $place->title = $request->title;
      $place->save();

      return $place;
    }

    public function update(Request $request) {
      $place = Place::find($request->id);
      $place->title = $request->title;
      $place->save();

      return $place;
    }

    public function browse(Request $request) {
      $places = Place::query()->where('company_id', \Auth::user()->company_id);

      if (isset($request->searchQuery)) {
        $places = $places->where('title', 'like', '%'.$request->searchQuery.'%');
      }

      if (isset($request->paginate)) {
        $places = $places->paginate(10);
      } else {
        $places = $places->get();
      }

      return $places;
    }

    public function show($id) {
      $place = Place::find($id);
      if (!isset($place->id)) {
        return response()->json(['message' => 'Could not find this place.'], 404);
      }

      return $place;
    }

    public function delete($id) {
      $place = Place::find($id);
      $place->delete();
    }
}
