<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Coingo Admin Login</title>
  <style>
    .login-page {
      width: 360px;
      padding: 8% 0 0;
      margin: auto;
    }
    .form {
      position: relative;
      z-index: 1;
      background: #FFFFFF;
      max-width: 360px;
      margin: 0 auto 100px;
      padding: 45px;
      text-align: center;
      box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
    }
    .form input {
      font-family: "Roboto", sans-serif;
      outline: 0;
      background: #f2f2f2;
      width: 100%;
      border: 0;
      margin: 0 0 15px;
      padding: 15px;
      box-sizing: border-box;
      font-size: 14px;
    }
    .form button {
      font-family: "Roboto", sans-serif;
      text-transform: uppercase;
      outline: 0;
      background: #9C27B0;
      width: 100%;
      border: 0;
      padding: 15px;
      color: #FFFFFF;
      font-size: 14px;
      -webkit-transition: all 0.3 ease;
      transition: all 0.3 ease;
      cursor: pointer;
    }
    .error {
      background-color: pink;
      color: white;
      border: 1px solid darkred;
      padding: 15px;
    }
    body {
      background: #9C27B0;
    }
  </style>
</head>
<body>
<div class="login-page">
  <div class="form">
    <form class="login-form" action="{{ URL::to('/admin/auth/login') }}" method="POST">
      @if (Session::has('error'))
        <p class="error">{{ Session::get('error') }}</p>
      @endif
      @csrf
      <input type="text" placeholder="Email" name="email"/>
      <input type="password" placeholder="Password" name="password"/>
      <button>login</button>
    </form>
  </div>
</div>
</body>
</html>
