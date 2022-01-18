<?php

namespace App\Http\Controllers;

use App\Company;
use App\Item;
use App\Invoice;
use App\InvoiceItem;
use App\Mail\SendInvoice;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvoicesController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function create(Request $request){
    $invoice = new Invoice();
    $invoice->company_id = Auth::user()->company_id;
    $invoice->contact_id = $request->contactId;


    if ($request->type == 'creditNote' || $request->type == 'reminder') {
      $invoice->parent_invoice_id = $request->invoiceId;
    }

    $invoice->type = $request->type;
    $invoice->payment_date = $request->paymentDate;
    $invoice->account = $request->account;
    $invoice->headline = $request->headline;
    $invoice->subtotal = $request->subtotal;
    $invoice->vat = $request->vat;
    $invoice->total = $request->total;
    $invoice->invoice_number = $request->invoiceNumber;
    $invoice->save();

    $company = Company::where('id', Auth::user()->company_id)->first();
    $newInvoiceNumber = $request->invoiceNumber + 1;
    $company->invoice_number = $newInvoiceNumber;
    $company->save();

    foreach ($request->lines as $line) {
      $item = new InvoiceItem();
      $item->invoice_id = $invoice->id;
      $item->quantity = $line['quantity'];
      $item->discount = $line['discount'];
      $item->price = $line['price'];
      $item->unit = $line['unit'];
      if (isset($line['itemId'])) {
        $item->item_id = $line['itemId'];

      }
      $item->freetext = $line['freetext'];
      if (isset($line['comments'])) {
        $item->comments = $line['comments'];
      }
      $item->save();

      if (isset($line['itemId']) && $line['itemId'] && $line['unit'] != 'digital') {
        $_item = Item::find($line['itemId']);
        if ($request->type == 'sell') {
          $_item->quantity -= $line['quantity'];
        } elseif ($request->type == 'creditNote') {
          $_item->quantity += $line['quantity'];
        }
        $_item->save();
      }
    }

    return [
      'invoice' => $invoice,
      'newInvoiceNumber' => $newInvoiceNumber
    ];
  }

  public function browse(Request $request) {
    $invoices = Invoice::query();

    $invoices = $invoices->with('contact');

    $invoices = $invoices->where('type', $request->type);

    if ($request->type == 'creditNote' || $request->type == 'reminder') {
      $invoices = $invoices->with('parent');
    }


    $invoices = $invoices->where('company_id', Auth::user()->company_id);

    if (isset($request->searchQuery)) {
      $invoices = $invoices->where('id', '=', $request->searchQuery);
    }

    if (isset($request->paginate)) {
      $invoices = $invoices->paginate(10);
    } else {
      $invoices = $invoices->get();
    }

    return $invoices;
  }

  public function show(Request $request, $id) {
    $invoice = Invoice::query();

    if ($request->type == 'sell' || $request->type === 'creditNote') {
      $invoice = $invoice->with('items.item', 'contact');
    } elseif ($request->type == 'buy') {
      $invoice = $invoice->with('items', 'contact');
    } elseif ($request->type == 'reminder') {
      $invoice = $invoice->with('contact', 'items');
    }

    $invoice = $invoice->where('id', $id)->first();

    if (!isset($invoice->id)) {
      return response()->json(['message' => 'Kunne ikke finde denne faktura'], 404);
    }

    return $invoice;
  }

  public function delete($id) {
    $invoice = Invoice::with('items')->where('id', $id)->first();

    if ($invoice->type != 'buy') {
      foreach ($invoice->items as $item) {

        if ($item->item_id) {
          $_item = Item::find($item->item_id);
          if ($invoice->type == 'sell') {
            $_item->quantity += $item->quantity;
          } elseif ($invoice->type == 'creditNote') {
            $_item->quantity -= $item->quantity;
          }
          $_item->save();
        }
      }

      if ($invoice->type == 'sell') {
        $creditNotes = Invoice::with('items')->where([
          ['parent_invoice_id', '=', $invoice->id],
          ['type', '=', 'creditNote']
        ])->get();
        foreach ($creditNotes as $creditNote) {
          foreach ($creditNote->items as $item) {
            if ($item->item_id) {
              $_item = Item::find($item->item_id);
              $_item->quantity -= $item->quantity;
              $_item->save();
            }
          }
          $creditNote->delete();
        }
      }
    }
    $invoice->delete();
  }

  public function registerPayment(Request $request) {
    $invoice = Invoice::find($request->invoiceId);
    $invoice->paid = $request->payment;
    $invoice->save();
  }

  public function printInvoice(Request $request, $id) {
    $company = Company::where('id', Auth::user()->company_id)->first();
    $type = $request->type;
    $pdf = $this->makePdfInvoice($type, $id, $company);
    return $pdf->stream();
  }

  public function sendInvoiceToEmail(Request $request) {
    $type = $request->type;
    $id = $request->invoiceId;
    $company = Company::where('id', Auth::user()->company_id)->first();
    $invoice = Invoice::with('contact')->where('id', $id)->first();
    if (!isset($invoice->contact->email)) {
      return response()->json(['message' => 'Denne kunde mangler en email. Redigér den under Ressourcer -> Kunder.'], 403);
    }

    $pdf = $this->makePdfInvoice($type, $id, $company);
    $filepath = Str::random(32).'.pdf';
    Storage::put('public/pdf/'.$filepath, $pdf->output());
    $pdf = 'public/pdf/'.$filepath;
    Mail::to($invoice->contact->email)->send(new SendInvoice($pdf, $company, $type));
    Storage::delete($pdf);
  }

  private function makePdfInvoice($type, $id, $company) {
    $invoice = Invoice::query();
    if ($type == 'sell' || $type == 'creditNote') {
      $invoice = $invoice->with('items.item', 'contact');
    } else {
      $invoice = $invoice->with('items', 'contact');
    }
    $invoice = $invoice->where([
      ['id', '=', $id],
      ['company_id', '=', Auth::user()->company_id]
    ])->first();

    if (!isset($invoice->id)) {
      return [
        'message' => 'Kunne ikke finde fakturen.'
      ];
    }


    $units = [
      ['key' => 'piece', 'text' => 'Stk.'],
      ['key' => 'liter', 'text' => 'Liter']
    ];

    foreach ($invoice->items as &$line) {
      foreach ($units as $unit) {
        if ($line->unit == $unit['key']) {
          $line['unitText'] = $unit['text'];
        }
      }

      $discount = 1 - (0.01 * $line->discount);
      $total = $line->quantity * $line->price * $discount;
      $line['total'] = $total;
    }

    $data = [
      'invoice' => $invoice,
      'company' => $company
    ];

    if ($type == 'sell') {
      $template = 'invoice';
    } elseif ($type == 'creditNote') {
      $template = 'creditNote';
    } elseif ($type == 'buy') {
      $template = 'buyInvoice';
    } elseif ($type == 'reminder') {
      $template = 'reminder';
    }

    $pdf = App::make('dompdf.wrapper');
    $pdf->loadView('pdf.'.$template, $data);

    return $pdf;
  }
}
