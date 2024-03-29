<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
        <title>Coingo.dk</title>
    </head>
    <body>
    <div id="app"></div>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var BASE_URL = "{{ URL::to('/') }}";
        var STRIPE_KEY = "{{ config('cashier.key') }}";
    </script>
    <script src="{{ asset('js/app.js') }}?version={{ time() }}"></script>
    </body>
</html>
