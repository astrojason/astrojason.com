<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') :: astrojason.com</title>
    <link href="assets/sass/build/base.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script type="text/javascript" src="assets/bower/html5shiv/dist/html5shiv.min.js"></script>
      <script type="text/javascript" src="assets/bower/respond/dest/respond.min.js"></script>
    <![endif]-->
  </head>
  <body ng-app="astroApp" ng-controller="MasterController">
    <div id="overlay" ng-show="init"></div>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">astrojason.com</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="/">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="active">
              @if(Auth::check())
                Logged in
              @else
                <form class="navbar-form navbar-right" role="form">
                  <div class="form-group">
                    <input type="text" placeholder="Username" class="form-control">
                  </div>
                  <div class="form-group">
                    <input type="password" placeholder="Password" class="form-control">
                  </div>
                  <button type="submit" class="btn btn-success">Sign in</button>
                  <a href="/register" class="btn btn-default">Register</a>
                </form>
              @endif
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <div class="container main">
      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        @yield('content')
      </div>
    </div> <!-- /container -->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script type="text/javascript" src="assets/bower/jquery/dist/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script type="text/javascript" src="assets/coffee/build/min/vendor/spin.min.js"></script>
    <script type="text/javascript" src="assets/coffee/build/min/vendor/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/bower/angular/angular.min.js"></script>
    <script type="text/javascript" src="assets/coffee/build/min/app.min.js"></script>
  </body>
</html>