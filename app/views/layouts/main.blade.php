<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>astrojason.com</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">
    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="stylesheet" href="/css/libs/reset.min.css">
    <link rel="stylesheet" href="css/libs/ui-lightness/jquery-ui-1.10.4.custom.min.css" />
    <link rel="stylesheet" href="/css/libs/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/css/astrojason.min.css">
    <script src="/js/libs/modernizr.min.js"></script>
  </head>
  <body ng-app="astroApp">
    <!--[if lt IE 8]>
    <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
    <![endif]-->
    <!-- Add your site or application content here -->
    <header>
      <nav class="navbar navbar-inverse" role="navigation">
        <div class="container-fluid">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-9">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">astrojason.com</a>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-9">
            <ul class="nav navbar-nav navbar-left">
              @if(Auth::check())
                <li><a href="/links">Links</a></li>
                <li><a href="/books">Books</a></li>
              @endif
              <li><a href="http://blog.astrojason.com">Blog</a></li>
              <li><a href="http://wiki.astrojason.com">Wiki</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              @if(Auth::check())
                <li><a>Hello {{ Auth::user()->name }}</a></li>
              @else
                <li><a href="#" data-toggle="modal" data-target="#loginModal">Login</a></li>
              @endif
            </ul>
          </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
      </nav>
    </header>
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          @yield('content')
        </div>
      </div>
    </div>

    <!-- Modals -->

    <div class="modal fade" id="linkModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" ng-controller="editLinkController as el">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">Link Edit</h4>
          </div>
          <div class="modal-body">
            <form id="link-edit" data-abide>
              <div class="row">
                <div class="col-lg-10 col-lg-offset-1 input-group">
                  <label class="input-group-addon">Name:</label>
                  <input class="form-control" type="text" ng-model="el.link.name" />
                </div>
              </div>
              <div class="row">
                <div class="col-lg-10 col-lg-offset-1 input-group">
                  <label class="input-group-addon">URL:</label>
                  <input class="form-control" type="text" ng-model="el.link.link" />
                </div>
              </div>
              <div class="row">
                <div class="col-lg-10 col-lg-offset-1 input-group">
                  <label class="input-group-addon">Category:</label>
                  <select class="form-control" ng-model="el.link.category" ng-options="category.category as category.category for category in el.categories"></select>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <div id="link-error" class="hidden alert alert-danger"></div>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" ng-click="el.save()">Save</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="bookModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" ng-controller="editBookController as eb">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">Book Edit</h4>
          </div>
          <div class="modal-body">
            <form id="book-edit" data-abide>
              <div class="row">
                <div class="col-lg-12 input-group">
                  <label class="input-group-addon">Title:</label>
                  <input class="form-control" type="text" ng-model="eb.book.title" required />
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6 input-group">
                  <label class="input-group-addon">Author First Name:</label>
                  <input class="form-control" type="text" ng-model="eb.book.author_fname" required />
                </div>
                <div class="col-lg-6 input-group">
                  <label class="input-group-addon">Author Last Name:</label>
                  <input class="form-control" type="text" ng-model="eb.book.author_lname" required />
                </div>
              </div>
              <div class="row">
                <div class="col-lg-8 input-group">
                  <label class="input-group-addon">Series:</label>
                  <input class="form-control" type="text" ng-model="eb.book.series" />
                </div>
                <div class="col-lg-4 input-group">
                  <label class="input-group-addon">Series Order:</label>
                  <input class="form-control" type="text" ng-model="eb.book.seriesorder" />
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12 input-group">
                  <label class="input-group-addon">Category:</label>
                  <select class="form-control" ng-model="eb.book.category" required ng-options="category.category as category.category for category in eb.categories"></select>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <div id="book-error" class="hidden alert alert-danger"></div>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" ng-click="eb.save()">Save</button>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript" src="/js/libs/jquery.min.js"></script>
    <script type="text/javascript" src="/js/libs/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/js/libs/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/libs/angular/angular.min.js"></script>
    <script type="text/javascript" src="/js/modules/app.min.js?v=1"></script>
    <script type="text/javascript" src="/js/controllers/linkControllers.min.js?v=1"></script>
    <script type="text/javascript" src="/js/controllers/bookControllers.min.js?v=1"></script>
<!--    <script type="text/javascript" src="/js/controllers/loginController.js"></script>-->
<!--    <script type="text/javascript" src="/js/controllers/movieControllers.js"></script>-->
<!--    <script type="text/javascript" src="/js/controllers/gameControllers.js"></script>-->
    <script type="text/javascript" src="/js/main.min.js"></script>
    @yield('scripts')
    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
    <script type="text/javascript">
      var _gaq=[['_setAccount','UA-34293418-1'],['_trackPageview']];
      (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
        g.src='//www.google-analytics.com/ga.js';
        s.parentNode.insertBefore(g,s)}(document,'script'));
    </script>
  </body>
</html>