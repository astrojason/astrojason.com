<!DOCTYPE html>
<html lang="en">
  <base href="/" />
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Read it Later :: astrojason.com</title>
    <link href="assets/bower/alertifyjs/dist/css/alertify.css" rel="stylesheet" />
    <link href="assets/bower/angular-bootstrap/ui-bootstrap-csp.css" rel="stylesheet" />
    <link href="assets/bower/typeahead.js-bootstrap3.less/typeaheadjs.css" rel="stylesheet" />
    <link href="assets/sass/build/vendor/loader.css" rel="stylesheet" />
    <link href="assets/sass/build/v1/base.css" rel="stylesheet" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script type="text/javascript" src="assets/bower/html5shiv/dist/html5shiv.min.js"></script>
      <script type="text/javascript" src="assets/bower/respond/dest/respond.min.js"></script>
    <![endif]-->
  </head>
  <body ng-app="astroApp" ng-controller="MasterController">
    <div id="overlay" ng-show="init" class="overlay"></div>
    <div
      ng-controller="ReadLaterController"
      ng-init="article = {title: '<%% $title %%>', url: '<%% $url %%>', categories: []}">
      @if(Auth::check())
        <div
          class="alert alert-danger"
          ng-show="error"
          ng-cloak>
          {{ error }}
          <span
            class="glyphicon glyphicon-remove-sign pull-right"
            ng-click="closeWindow()"></span>
        </div>
      @include('v1.partials.article_form')
      @else
        <div class="alert alert-warning">
          You must <a href="http://www.astrojason.com" target="_blank">Login</a> to use this widget.
        </div>
      @endif
    </div>
    @include('v1.partials.js')
  </body>