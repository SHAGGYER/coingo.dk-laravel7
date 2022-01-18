<?php

namespace App\Http\Controllers;

use App\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AccountingController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function getVat(Request $request) {
    $invoices = $this->getInvoices($request->startDate, $request->endDate);
    $sellInvoices = $invoices['sell'];
    $creditNotes = $invoices['creditNotes'];
    $buyInvoices = $invoices['buy'];

    $totalSell = 0;

    foreach ($sellInvoices as $invoice) {
      $totalSell += $invoice->vat;
    }

    $totalCreditNote = 0;
    foreach ($creditNotes as $creditNote) {
      $totalCreditNote += $creditNote->vat;
    }

    $totalBuy = 0;
    foreach ($buyInvoices as $invoice) {
      $totalBuy += $invoice->vat;
    }

    if (!count($sellInvoices) && !count($creditNotes) && !count($buyInvoices)) {
      $data['gotInvoices'] = false;
    } else {
      $data['gotInvoices'] = true;
    }
    $data['totalSell'] = $totalSell;
    $data['totalBuy'] = $totalBuy;
    $data['totalCreditNote'] = $totalCreditNote;
    $data['total'] = $totalSell - $totalCreditNote - $totalBuy;

    return $data;
  }

  public function getAccounting(Request $request) {
    $invoices = $this->getInvoices($request->startDate, $request->endDate);
    $sellInvoices = $invoices['sell'];
    $creditNotes = $invoices['creditNotes'];
    $buyInvoices = $invoices['buy'];


    $totalSell = 0;

    foreach ($sellInvoices as $invoice) {
      $totalSell += $invoice->subtotal + $invoice->vat;
    }

    $totalCreditNote = 0;
    foreach ($creditNotes as $creditNote) {
      $totalCreditNote += $creditNote->subtotal + $creditNote->vat;
    }

    $totalBuy = 0;
    foreach ($buyInvoices as $invoice) {
      $totalBuy += $invoice->subtotal + $invoice->vat;
    }

    $totalSell = $totalSell - $totalCreditNote;

    if (!count($sellInvoices) && !count($creditNotes) && !count($buyInvoices)) {
      $data['gotInvoices'] = false;
    } else {
      $data['gotInvoices'] = true;
    }
    $data['totalSell'] = $totalSell;
    $data['totalBuy'] = $totalBuy;

    return $data;
  }

  public function getChartData() {
    $dates = [];
    Carbon::setLocale('da');


    for ($i = 0; $i < 12; $i++) {
      $startDate = new Carbon();
      $subStartDate = $startDate->subMonth($i);
      $startMonth = $subStartDate->startOfMonth();
      $endDate = new Carbon();
      $subEndDate = $endDate->subMonth($i);
      $endMonth = $subEndDate->endOfMonth();
      $dates[] = ["month" => $subStartDate->getTranslatedMonthName(), "startMonth" => $startMonth, 'endMonth' => $endMonth];
    }


    $totalSell = [];
    foreach ($dates as $date) {
      $invoices = Invoice::where([
        ['company_id', '=', Auth::user()->company_id],
        ['type', '=', 'sell'],
      ])->whereBetween('created_at', [$date['startMonth'], $date['endMonth']])->get();

      $total = 0;
      foreach ($invoices as $invoice) {
        $total += $invoice->subtotal;
      }

      $totalSell[] = $total;
    }

    $totalBuy = [];
    foreach ($dates as $date) {
      $invoices = Invoice::where([
        ['company_id', '=', Auth::user()->company_id],
        ['type', '=', 'buy'],
      ])->whereBetween('created_at', [$date['startMonth'], $date['endMonth']])->get();

      $total = 0;
      foreach ($invoices as $invoice) {
        $total += $invoice->subtotal;
      }

      $totalBuy[] = $total;
    }

    return [
      'dates' => $dates,
      'sell' => $totalSell,
      'buy' => $totalBuy
    ];
  }

  private function getInvoices($startDate, $endDate) {
    $start = Carbon::parse($startDate);
    $end = Carbon::parse($endDate)->endOfDay();

    $sellInvoices = Invoice::where([
      ['company_id', '=', Auth::user()->company_id],
      ['type', '=', 'sell']
    ])->whereBetween('created_at', [$start, $end])->get();
    $creditNotes = Invoice::where([
      ['company_id', '=', Auth::user()->company_id],
      ['type', '=', 'creditNote']
    ])->whereBetween('created_at', [$start, $end])->get();
    $buyInvoices = Invoice::where([
      ['company_id', '=', Auth::user()->company_id],
      ['type', '=', 'buy']
    ])->whereBetween('created_at', [$start, $end])->get();


    return [
      'sell' => $sellInvoices,
      'creditNotes' => $creditNotes,
      'buy' => $buyInvoices
    ];
  }



}
