<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Coingo.dk</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="/css/startup-materialize.min.css">

  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <style>
    @media (max-width: 768px) {
      .background img {
        display: none;
      }
    }

    nav.navbar.dark a, nav.navbar.solid a {
      color: #fff;
    }

    nav.navbar.absolute,
    nav.navbar.solid.dark, nav.navbar.solid{
      background-color: #9c27b0;
    }

    .dropdown-trigger i.right {
      color: #fff;
    }

    i {
      color: #9c27b0;
    }


    .pricing-table {
      border-color: #9c27b0;
    }

    .section.light {
      background-color: #9c27b0;
    }

    .pricing-feature {
      text-align: left !important;
    }

    .pricing-feature a {
      border: 1px solid #9C27B0;
      padding: 10px 35px;
      color: #9C27B0;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar absolute">
  <div class="nav-wrapper">
    <a href="{{ URL::to('/') }}" class="brand-logo"><i class="icon-diamond white-text"></i></a>
    <ul id="nav-mobile" class="right hide-on-med-and-down">
      <li><a href="{{ URL::to('/') }}">Start</a></li>
      <li><a class="dropdown-trigger" href="#!" data-target="pages">Konto<i class="material-icons right">arrow_drop_down</i></a></li>
    </ul>

    <ul id="pages" class="dropdown-content">
      <li><a href="{{ URL::to('/login') }}">Log Ind</a></li>
      <li><a href="{{ URL::to('/register') }}">Registrér</a></li>
    </ul>

    <a href="#" data-target="slide-out" class="sidenav-trigger button-collapse right"><i class="material-icons white-text">menu</i></a>
  </div>
</nav>
<ul id="slide-out" class="sidenav">
  <li><a class="waves-effect waves-teal" href="{{ URL::to('/') }}">Start</a></li>
  <li class="no-padding">
    <ul class="collapsible collapsible-accordion">
      <li class="bold">
        <a class="collapsible-header waves-effect waves-teal active">Konto</a>
        <div class="collapsible-body">
          <ul>
            <li><a href="{{ URL::to('/login') }}">Log Ind</a></li>
            <li><a href="{{ URL::to('/register') }}">Registrér</a></li>
          </ul>
        </div>
      </li>
    </ul>
  </li>
</ul>

@yield('content')

<!-- Footer -->
<footer class="page-footer">
  <div class="container">
    <div class="row">
      <div class="col s6 m3">
        <p>Mikolaj Marciniak. Alle rettigheder forebeholdes. {{ \Carbon\Carbon::now()->year }} &copy;</p>
      </div>
      <div class="col s6 m3">
        <h5>Start</h5>
        <ul>
          <li><a href="{{ URL::to('/') }}">Startside</a></li>
        </ul>
      </div>
      <div class="col s6 m3">
        <h5>Konto</h5>
        <ul>
          <li><a href="{{ URL::to('/login') }}">Log Ind</a></li>
          <li><a href="{{ URL::to('/register') }}">Registrér</a></li>
        </ul>
      </div>
    </div>
  </div>
</footer>
<!-- Scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="/js/materialize.min.js"></script>

<!-- External libraries -->
<script src="/js/imagesloaded.pkgd.min.js"></script>
<script src="/js/masonry.pkgd.min.js"></script>
<script src="/js/TweenMax.min.js"></script>
<script src="/js/ScrollMagic.min.js"></script>
<script src="/js/animation.gsap.min.js"></script>

<!-- Initialization script -->
<script src="/js/startup.js"></script>
<script src="/js/init.js"></script>



</body>
</html>
