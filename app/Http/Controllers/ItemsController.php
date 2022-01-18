<?php

namespace App\Http\Controllers;

use App\Batch;
use App\Company;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ItemsController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function printWarehouse() {
    $items = Item::with('place')->where('company_id', \Auth::user()->company_id)->get();
    $data['items'] = $items;

    $data['company'] = Company::where('id', \Auth::user()->company_id)->first();

    $total = 0;
    foreach ($items as $item) {
      $total += $item->quantity * $item->price;
    }

    $data['total'] = $total;

    $pdf = App::make('dompdf.wrapper');
    $pdf->loadView('pdf.warehouse', $data);
    return $pdf->stream();
  }

  public function create(Request $request){
    $item = new Item();
    $item->title = $request->title;
    $item->company_id = \Auth::user()->company_id;
    $item->place_id = $request->placeId;
    $item->price = $request->price;
    $item->quantity = $request->quantity;
    $item->unit = $request->unit;
    $item->code = $request->code;
    if (isset($request->supplierId)) {
      $item->supplier_id = $request->supplierId;
    }
    $item->save();

    return $item;
  }

  public function update(Request $request) {
    $item = Item::find($request->id);
    $item->title = $request->title;
    $item->place_id = $request->placeId;
    $item->price = $request->price;
    $item->unit = $request->unit;
    $item->code = $request->code;
    $item->quantity = $request->quantity;
    if (isset($request->supplierId)) {
      $item->supplier_id = $request->supplierId;
    }
    $item->save();

    return $item;
  }

  public function browse(Request $request) {
    $items = Item::query()->with('place')->where('company_id', \Auth::user()->company_id);

    if (isset($request->searchQuery)) {
      $items = $items->where('title', 'like', '%'.$request->searchQuery.'%')
        ->orWhere('code', 'like', '%'.$request->searchQuery.'%');
    }

    if (isset($request->paginate)) {
      $items = $items->paginate(10);
    } else {
      $items = $items->get();
    }

    return $items;
  }

  public function show($id) {
    $item = Item::with('place', 'supplier')->where('id', $id)->first();
    if (!isset($item->id)) {
      return response()->json(['message' => 'Could not find this item.'], 404);
    }

    return $item;
  }

  public function delete($id) {
    $item = Item::find($id);
    $item->delete();
  }
}
