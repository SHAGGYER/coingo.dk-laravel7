<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Lager</title>
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
    }
    table thead td {
      font-weight: bold;
    }
    table thead td,
    table tbody td,
    table tfoot td
    {
      border: 1px solid black;
      padding: 10px;
    }

  </style>
</head>
<body>
  <h1>{{ $company->title }}: Lager</h1>
  <span>Antal produkter: {{ count($items) }}</span>
  <table style="margin-top: 2rem">
    <thead>
    <tr>
      <td>Navn</td>
      <td>Sted</td>
      <td>Stk. Pris</td>
      <td>Antal</td>
      <td>Total</td>
    </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
      <tr>
        <td>{{ $item->title }}</td>
        <td>{{ isset($item->place) ? $item->place->title : '-' }}</td>
        <td>{{ number_format($item->price, 2, '.', '') }}</td>
        <td>{{ $item->quantity }}</td>
        <td>{{ number_format($item->price * $item->quantity, 2, '.', '') }}</td>
      </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td>{{ number_format($total, 2, '.', '') }}</td>
    </tr>
    </tfoot>
  </table>
</body>
</html>














