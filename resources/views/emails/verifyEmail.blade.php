<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Verify Email</title>
</head>
<body>
<h1>Velkommen til Coingo.dk Email Verifikation</h1>
<a href="{{ URL::to('/') }}/#/auth/verifyEmail/{{$token}}">Tryk her for at verificere din email</a>
</body>
</html>
