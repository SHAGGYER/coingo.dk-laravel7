<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Rykker</title>
  <style>
    .invoice {
      position: relative;
    }
    .invoice .preview {
      position: absolute;
      right: 5px;
      top: 5px;
    }
    .invoice .top {
      display: block;
      margin-top: 2rem;
      vertical-align: top;
    }
    .invoice .top .customer {
      float: left;
      border: 1px solid #ccc;
      width: 300px;
      padding: 10px;
    }
    .invoice .top .company {
      display: inline-block;
      margin-left: 200px;
      float: left;
      border: 1px solid #ccc;
      padding: 15px;
    }
    .invoice .top .customer .customer-info {
      font-size: 12px;
      line-height: 0.7;
    }
    .invoice .top2 {
      margin-top: 2rem;
    }
    .invoice .top2 .date {
      display: inline-block;
      width: 80%;
    }
    .invoice .top2 .invoice-number {
      display: inline-block;
      width: 20%;
    }
    .invoice .middle {
      margin-top: 2rem;
    }
    .invoice .middle2 {
      margin-top: 2rem;
    }
    .invoice .middle2 table {
      width: 100%;
    }
    .invoice .bottom {
      margin-top: 2rem;
      text-align: right;
    }
    .clearfix {
      clear: both;
    }
  </style>

</head>
<body>
<div class="invoice">
  <div class="top">
    <div class="customer">
      <p>
        {{ $invoice->contact->name }}
      </p>

      <div class="customer-info">
        <p>{{ $invoice->contact->address }}</p>
        <p>{{ $invoice->contact->zip }}</p>
        <p>{{ $invoice->contact->city }}</p>
        <p class="mb-0">{{ $invoice->contact->country }}</p>
      </div>
    </div>
    <div class="company">
      <h2>{{ $company->title }}</h2>
      <p>{{ $company->address }}</p>
      <p>{{ $company->city }}</p>
      <p>{{ $company->zip }}</p>
      <p>{{ $company->country }}</p>
      <p>Tlf.: {{ $company->phone }}</p>
      <p>{{ $company->email }}</p>
    </div>
  </div>
  <div class="clearfix"></div>
  <div class="top2">
    <div class="date">
      <p>Betalingsdato: {{ $invoice->payment_date }}</p>
    </div>
    <div class="invoice-number">
      <p>Fakturanummer: {{ $invoice->invoice_number }}</p>
    </div>
  </div>
  <div class="middle">
    <h2>{{ $invoice->headline }}</h2>
    <p>{{ $invoice->comments }}</p>
  </div>
  <div class="middle2">
      <table>
        <thead>
        <tr>
          <th style="width: 30%" class="text-left">Beskrivelse</th>
          <th style="width: 10%" class="text-left">Antal</th>
          <th style="width: 15%" class="text-left">Enhed</th>
          <th style="width: 15%" class="text-left">Stk. Pris</th>
          <th style="width: 10%" class="text-left">Rabat</th>
          <th style="width: 15%" class="text-left">Pris</th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoice->items as $line)
        <tr>
          <td>
            {{ $line->freetext }}
          </td>
          <td>
            <p class="mb-0">{{ $line->quantity }}</p>
          </td>
          <td>
            <p class="mb-0">
              {{ $line['unitText'] }}
            </p>
          </td>
          <td>
            <span>{{ number_format($line->price, 2, '.', '') }}</span>
          </td>
          <td>
            <span>{{ $line->discount }} %</span>
          </td>
          <td>
            <p class="mb-0">{{ number_format($line['total'], 2, '.', '') }}</p>
          </td>
        </tr>
          @endforeach
        </tbody>
      </table>
  </div>
  <div class="bottom">
    <div class="content">
      <p>Subtotal: {{ number_format($invoice->subtotal, 2, '.', '') }}</p>
      @if ($invoice->account == 'withVat')
      <p>Moms (25%): {{ number_format($invoice->vat, 2, '.', '') }}</p>
      @endif
      <p>Total: {{ number_format($invoice->total, 2, '.', '') }}</p>
    </div>
  </div>
  <div>
    Reg.nr.: {{ $company->reg_nr }} &middot; Kontonr.: {{ $company->account_number }}
  </div>
</div>
</body>
</html>














