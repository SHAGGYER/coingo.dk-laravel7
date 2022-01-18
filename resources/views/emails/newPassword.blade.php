<!doctype html>
<html lang="da">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Nyt Kodeord</title>
</head>
<body>
 <h1>Velkommen til Coingo.dk Kodeord Gendannelse</h1>
  <a href="{{ URL::to('/auth/newPassword') }}/{{$token}}">Tryk her for at gendanne kodeord</a>
</body>
</html>
