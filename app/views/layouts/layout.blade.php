<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') :: astrojason.com</title>
    <link href="assets/bower/alertifyjs/dist/css/alertify.css" rel="stylesheet" />
    <link href="assets/sass/build/base.css" rel="stylesheet" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script type="text/javascript" src="assets/bower/html5shiv/dist/html5shiv.min.js"></script>
      <script type="text/javascript" src="assets/bower/respond/dest/respond.min.js"></script>
    <![endif]-->
  </head>
  <body ng-app="astroApp" ng-controller="MasterController">
    <div id="overlay" ng-show="init" class="overlay"></div>
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
<!--            <li class="active"><a href="/">Home</a></li>-->
<!--            <li><a href="#about">About</a></li>-->
<!--            <li><a href="#contact">Contact</a></li>-->
<!--            <li class="dropdown">-->
<!--              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span class="caret"></span></a>-->
<!--              <ul class="dropdown-menu" role="menu">-->
<!--                <li><a href="#">Action</a></li>-->
<!--                <li><a href="#">Another action</a></li>-->
<!--                <li><a href="#">Something else here</a></li>-->
<!--                <li class="divider"></li>-->
<!--                <li class="dropdown-header">Nav header</li>-->
<!--                <li><a href="#">Separated link</a></li>-->
<!--                <li><a href="#">One more separated link</a></li>-->
<!--              </ul>-->
<!--            </li>-->
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="active"
              @if(Auth::check())
                ng-init="user = {firstname: '{{ Auth::user()->firstname }}', id: {{ Auth::user()->id }}}"
              @endif
              >
              <li>
              <a ng-show="user" ng-cloak>Hello <span>@{{ user.firstname }}</span> <span class="glyphicon glyphicon-remove-circle" ng-click="logout()" data-toggle="tooltip" data-placement="top" title="Log out"></span></a></li>
              <form ng-show="!user" class="navbar-form navbar-right" role="form" ng-submit="login()" ng-cloak>
                <div class="form-group">
                  <input type="text" placeholder="Username" class="form-control" ng-model="username">
                </div>
                <div class="form-group">
                  <input type="password" placeholder="Password" class="form-control" ng-model="password">
                </div>
                <button type="submit" class="btn btn-success">Sign in</button>
                <a href="#" class="btn btn-default" data-toggle="modal" data-target="#registrationModal">Register</a>
              </form>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <div class="container main">
      <div class="alert alert-danger" ng-show="show_error" ng-cloak>@{{ error_message }}</div>
      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        @yield('content')
      </div>
      @if(Auth::user() && Auth::user()->hasRole('Admin'))
        <div ng-controller="AdminController">
          <button class="btn btn-toolbar" ng-click="migrateLinks()">Migrate Links</button>
          <div ng-show="importSql">
            <textarea class="col-lg-12">@{{ importSql }}</textarea>
          </div>
        </div>
      @endif
    </div> <!-- /container -->

    <div class="modal fade" id="registrationModal" ng-controller="UserController">
      <form role="form" name="registrationForm" class="form-inline" ng-submit="registerUser()">
        <div class="modal-dialog">
          <div id="register_overlay" class="overlay" ng-show="submitting"></div>
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <h4 class="modal-title">User Registration</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label class="sr-only" for="first_name">First Name</label>
                <input type="text" class="form-control" placeholder="First Name" ng-model="first_name" required />
                <div>&nbsp;</div>
              </div>
              <div class="form-group">
                <label class="sr-only" for="last_name">Last Name</label>
                <input type="text" class="form-control" placeholder="Last Name" ng-model="last_name" required />
                <div>&nbsp;</div>
              </div>
              <div class="form-group">
                <label class="sr-only" for="email">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="Email address" ng-model="email" required check-availibility />
                <div><span ng-show="registrationForm.email.$error.unique" class="error">Email address already in use</span>&nbsp;</div>
              </div>
              <div class="form-group">
                <label class="sr-only" for="username">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Username" ng-model="username" required check-availibility />
                <div><span ng-show="registrationForm.username.$error.unique" class="error">Username already in use</span>&nbsp;</div>
              </div>
              <div class="form-group">
                <label class="sr-only" for="password">Password</label>
                <input type="password" class="form-control" placeholder="Password" ng-model="password" required />
                <div>&nbsp;</div>
              </div>
              <div class="form-group">
                <label class="sr-only" for="confirm_password">Confirm Password</label>
                <input type="password" class="form-control" placeholder="Confirm Password" ng-model="confirm_password" required compare-to="password" />
                <div>&nbsp;</div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <input type="submit" class="btn" value="Register" ng-disabled="!registrationForm.$valid" ng-class="(!registrationForm.$valid) ? 'btn-disabled' : 'btn-success'">
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </form>
    </div><!-- /.modal -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script type="text/javascript" src="assets/bower/jquery/dist/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script type="text/javascript" src="assets/bower/angular/angular.min.js"></script>
    <script type="text/javascript" src="assets/js/vendor/spin.min.js"></script>
    <script type="text/javascript" src="assets/bower/alertifyjs/dist/js/alertify.js"></script>
    <script type="text/javascript" src="assets/js/vendor/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/app.min.js"></script>
    <script type="text/javascript" src="assets/js/models.min.js"></script>
    <script type="text/javascript" src="assets/js/directives.min.js"></script>
    <script type="text/javascript" src="assets/js/filters.min.js"></script>
    <script type="text/javascript" src="assets/js/controllers.min.js"></script>
  </body>
</html>
