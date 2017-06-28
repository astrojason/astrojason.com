<!DOCTYPE html>
<html
  lang="en" ng-app="astroApp">
  <base href="/" />
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') :: astrojason.com</title>
    <link href="assets/bower/alertifyjs/dist/css/alertify.css" rel="stylesheet" />
    <link href="assets/bower/angular-fx/dist/angular-fx.min.css" rel="stylesheet" />
    <link href="assets/bower/angular-bootstrap/ui-bootstrap-csp.css" rel="stylesheet" />
    <link href="assets/bower/typeahead.js-bootstrap3.less/typeaheadjs.css" rel="stylesheet" />
    <link href="assets/sass/build/v1/base.css" rel="stylesheet" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script type="text/javascript" src="assets/bower/html5shiv/dist/html5shiv.min.js"></script>
      <script type="text/javascript" src="assets/bower/respond/dest/respond.min.js"></script>
    <![endif]-->
  </head>
  <body
    ng-controller="MasterController"
    ng-model-options="{
      updateOn: 'default blur',
      debounce: { 'default': 500, 'blur': 0 }
    }"
    @if(Auth::user())ng-init="initUser({ id: <% Auth::user()->id %>, username: '<% Auth::user()->username %>', firstname: '<% Auth::user()->firstname %>', lastname: '<% Auth::user()->lastname %>', email: '<% Auth::user()->email %>' })"@endif>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container-fluid">
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
            <li ng-show="user" uib-dropdown on-toggle="toggled(open)" id="user_nav">
              <a href id="simple-dropdown" uib-dropdown-toggle>
                Hello <span>{{ user.firstname }}</span>
                <small class="glyphicon" ng-class="open ? 'glyphicon-chevron-up' : 'glyphicon-chevron-down'"></small>
              </a>
              <ul class="dropdown-menu" uib-dropdown-menu aria-labelledby="simple-dropdown">
                <li><a href="/account" target="_self">My account</a></li>
                <li><a ng-click="logout()" id="logout_button">Logout</a></li>
              </ul>
            </li>
            <li ng-show="!user">
              <form id="login_form" name="login_form" class="navbar-form navbar-right" role="form" ng-submit="login()" ng-cloak>
                <div class="form-group">
                  <input
                    type="text"
                    id="login_username"
                    placeholder="Username"
                    class="form-control"
                    ng-model="username"
                    required />
                </div>
                <div class="form-group">
                  <input
                    type="password"
                    id="login_password"
                    placeholder="Password"
                    class="form-control"
                    ng-model="password"
                    required />
                </div>
                <button
                  type="submit"
                  id="login_submit"
                  ng-disabled="!login_form.$valid"
                  ng-class="login_form.$valid ? 'btn-success' : 'btn-disabled'"
                  class="btn">Sign in</button>
                <a
                  href="register"
                  class="btn btn-default"
                  id="register_link"
                  target="_self">Register</a>
              </form>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <div class="container-fluid main">
      <div class="row">
        <div class="alert alert-danger" ng-show="show_error" ng-cloak>{{ error_message }}</div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          @yield('content')
        </div>
      </div>
    </div><!-- /container -->
    @include('v1.partials.js')
  </body>
</html>
