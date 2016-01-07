<!DOCTYPE html>
<html lang="en" ng-app="astroApp">
  <base href="/" />
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') :: astrojason.com</title>
    <link href="assets/bower/alertifyjs/dist/css/alertify.css" rel="stylesheet" />
    <link href="assets/bower/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="assets/bower/angular-fx/dist/angular-fx.min.css" rel="stylesheet" />
    <link href="assets/sass/build/vendor/loader.css" rel="stylesheet" />
    <link href="assets/sass/build/base.css" rel="stylesheet" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script type="text/javascript" src="assets/bower/html5shiv/dist/html5shiv.min.js"></script>
      <script type="text/javascript" src="assets/bower/respond/dest/respond.min.js"></script>
    <![endif]-->
  </head>
  <body ng-controller="MasterController"@if(Auth::user()) ng-init="initUser({ id: <% Auth::user()->id %>, username: '<% Auth::user()->username %>', firstname: '<% Auth::user()->firstname %>', lastname: '<% Auth::user()->lastname %>', email: '<% Auth::user()->email %>' })"@endif>
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
          <ul class="nav navbar-nav" ng-show="user" ng-cloak>
            @foreach($userNav as $navItem)
              <li @if($navItem->active) class="active"@endif><a href="<% $navItem->href %>" target="_self"><% $navItem->name %></a></li>
            @endforeach
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="active">
              <li>
              <a ng-show="user" ng-cloak>Hello <span>{{ user.firstname }}</span> <span class="glyphicon glyphicon-remove-circle" ng-click="logout()" data-toggle="tooltip" data-placement="top" title="Log out"></span></a></li>
              <form ng-show="!user" class="navbar-form navbar-right" role="form" ng-submit="login()" ng-cloak>
                <div class="form-group">
                  <input type="text" placeholder="Username" class="form-control" ng-model="username">
                </div>
                <div class="form-group">
                  <input type="password" placeholder="Password" class="form-control" ng-model="password">
                </div>
                <button type="submit" class="btn btn-success">Sign in</button>
                <a href="register" class="btn btn-default">Register</a>
              </form>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <div class="container main">
      <div class="row">
        <div class="alert alert-danger" ng-show="show_error" ng-cloak>{{ error_message }}</div>
      </div>
      <div class="jumbotron">
        @yield('content')
      </div>
    </div><!-- /container -->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script type="text/javascript" src="assets/bower/jquery/dist/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script type="text/javascript" src="assets/bower/angular/angular.min.js"></script>
    <script type="text/javascript" src="assets/bower/angular-resource/angular-resource.min.js"></script>
    <script type="text/javascript" src="assets/bower/angular-animate/angular-animate.min.js"></script>
    <script type="text/javascript" src="assets/bower/alertifyjs/dist/js/alertify.js"></script>
    <script type="text/javascript" src="assets/bower/angular-fx/dist/angular-fx.min.js"></script>
    <script type="text/javascript" src="assets/bower/angular-messages/angular-messages.min.js"></script>
    <script type="text/javascript" src="assets/bower/moment/min/moment.min.js"></script>
    <script type="text/javascript" src="assets/bower/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="assets/js/vendor/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/app.min.js"></script>
    <script type="text/javascript" src="assets/js/models.min.js"></script>
    <script type="text/javascript" src="assets/js/directives.min.js"></script>
    <script type="text/javascript" src="assets/js/filters.min.js"></script>
    <script type="text/javascript" src="assets/js/services.min.js"></script>
    <script type="text/javascript" src="assets/js/resources.min.js"></script>
    <script type="text/javascript" src="assets/js/controllers.min.js"></script>
  </body>
</html>
