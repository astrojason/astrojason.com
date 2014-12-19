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
    <div ng-controller="ReadLaterController"@if(Auth::check()) ng-init="createLink({{ Auth::user()->id }}, '{{ $title }}', '{{ $link }}')"@endif>
      <div class="alert alert-success" ng-show="success" ng-cloak>Link added successfully.</div>
      <div class="alert alert-danger" ng-show="error" ng-cloak>@{{ error }} <span class="glyphicon glyphicon-remove-sign pull-right" ng-click="closeWindow()"></span></div>
      <link-form
        link="newLink"
        editing="editing"
        ng-show="!error">
      </link-form>
    </div>
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