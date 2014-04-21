@extends('layouts/main')

@section('content')
  @if(Auth::check())
    <div class="row">
    <div class="col-md-7 col-md-offset-1">
      <div class="row">
        <div class="col-md-12">
          <div ng-controller="searchLinksCtrl">
            <div class="panel panel-default">
              <input ng-model="filter" placeholder="Search Links" class="form-control" />
              <table class="table table-striped all-links" ng-show="filter">
                <tr ng-repeat="link in filtered = (links | filter: filter)" ng-class="link.read ? 'read' : ''" ng-include src="'js/templates/linkInfo.html'"></tr>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div ng-controller="todaysLinksListCtrl">
            <div class="panel panel-default" ng-show="athome.length > 0 && amhome">
              <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">At Home</h3>
                <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('At Home')"></div>
              </div>
              <table class="table table-striped">
                <tr ng-repeat="link in athome" ng-include src="'js/templates/linkInfo.html'"></tr>
              </table>
            </div>
            <div class="panel panel-default" ng-show="cooking.length > 0">
              <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">Cooking</h3>
                <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('Cooking')"></div>
              </div>
              <table class="table table-striped">
                <tr ng-repeat="link in cooking" ng-include src="'js/templates/linkInfo.html'"></tr>
              </table>
            </div>
            <div class="panel panel-default" ng-show="exercise.length > 0">
              <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">Exercise</h3>
                <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('Exercise')"></div>
              </div>
              <table class="table table-striped">
                <tr ng-repeat="link in exercise" ng-include src="'js/templates/linkInfo.html'"></tr>
              </table>
            </div>
            <div class="panel panel-default" ng-show="forreview.length > 0">
              <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">For Review</h3>
                <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('For Review')"></div>
              </div>
              <table class="table table-striped">
                <tr ng-repeat="link in forreview" ng-include src="'js/templates/linkInfo.html'"></tr>
              </table>
            </div>
            <div class="panel panel-default" ng-show="forthehouse.length > 0">
              <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">For the House</h3>
                <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('For the House')"></div>
              </div>
              <table class="table table-striped">
                <tr ng-repeat="link in forthehouse" ng-include src="'js/templates/linkInfo.html'"></tr>
              </table>
            </div>
            <div class="panel panel-default" ng-show="groups.length > 0">
              <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">Groups</h3>
                <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('Groups')"></div>
              </div>
              <table class="table table-striped">
                <tr ng-repeat="link in groups" ng-include src="'js/templates/linkInfo.html'"></tr>
              </table>
            </div>
            <div class="panel panel-default" ng-show="guitar.length > 0">
              <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">Guitar</h3>
                <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('Guitar')"></div>
              </div>
              <table class="table table-striped">
                <tr ng-repeat="link in guitar" ng-include src="'js/templates/linkInfo.html'"></tr>
              </table>
            </div>
            <div class="panel panel-default" ng-show="photography.length > 0">
              <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">Photography</h3>
                <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('Photography')"></div>
              </div>
              <table class="table table-striped">
                <tr ng-repeat="link in photography" ng-include src="'js/templates/linkInfo.html'"></tr>
              </table>
            </div>
            <div class="panel panel-default" ng-show="projects.length > 0">
              <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">Projects</h3>
                <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('Projects')"></div>
              </div>
              <table class="table table-striped">
                <tr ng-repeat="link in projects" ng-include src="'js/templates/linkInfo.html'"></tr>
              </table>
            </div>
            <div class="panel panel-default" ng-show="programming.length > 0">
              <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">Programming</h3>
                <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('Programming')"></div>
              </div>
              <table class="table table-striped">
                <tr ng-repeat="link in programming" ng-include src="'js/templates/linkInfo.html'"></tr>
              </table>
            </div>
            <div class="panel panel-default" ng-show="wishlist.length > 0">
              <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">Wishlist</h3>
                <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('Wishlist')"></div>
              </div>
              <table class="table table-striped">
                <tr ng-repeat="link in wishlist" ng-include src="'js/templates/linkInfo.html'"></tr>
              </table>
            </div>
            <div class="panel panel-default" ng-show="wordpress.length > 0">
              <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">Wordpress</h3>
                <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('Wordpress')"></div>
              </div>
              <table class="table table-striped">
                <tr ng-repeat="link in wordpress" ng-include src="'js/templates/linkInfo.html'"></tr>
              </table>
            </div>
            <div class="panel panel-default" ng-show="unread.length > 0">
              <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">Unread</h3>
              </div>
              <table class="table table-striped">
                <tr ng-repeat="link in unread" ng-include src="'js/templates/linkInfo.html'"></tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">Controls</h4>
        </div>
        <div class="panel-body">
          <p>
            <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#linkModal" ng-click="edit()" ng-controller="editLinkCtrl">Add Link</button>
            <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#bookModal" ng-click="edit()">Add Book</button>
            <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#movieModal" ng-click="edit()">Add Movie</button>
            <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#gameModal" ng-click="edit()">Add Game</button>
          </p>
          <p>
            <a class="btn btn-primary btn-xs" href="javascript:(
                  function(){
                  var jsCode = document.createElement('script');
                  jsCode.setAttribute('src', 'http://www.astrojason.com/js/bookmarklet.min.js');
                  document.body.appendChild(jsCode);
                  })()">Read Later</a>
            <button class="btn btn-primary btn-xs" href="#" ng-click="migrate()">Run Migration</button>
          </p>
        </div>
      </div>

      <div class="panel panel-default" ng-controller="nextBookCtrl" ng-show="book">
        <div class="panel-heading clearfix">
          <h4 class="panel-title pull-left">Next Book to Read</h4>
          <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="getNextBook()"></div>
        </div>
        <div class="panel-body">
          <p>@{{ book.title }}<br /><small>by @{{ book.author_fname }}<span ng-show="book.author_lname" > @{{ book.author_lname }}</span></small></p>
        </div>
        <div class="panel-footer clearfix">
          <div class="pull-right">
            <div class="glyphicon glyphicon-pencil book-action" data-toggle="modal" data-target="#bookModal" ng-click="edit(book)"></div>
            <div class="glyphicon glyphicon-ok book-action right" ng-click="read(book)"></div>
          </div>
        </div>
      </div>

    </div>
    <div class="col-md-1"></div>
  </div>
  @else
    <div class="row">
      <div class="col-lg-12 text-center">
        <h2>Nothing to see here, move along</h2>
        <img src="/img/whos_awesome.jpg" />
      </div>
    </div>
  @endif
@stop

@section('scripts')
  <script type="text/javascript" src="/js/controllers/linkControllers.js"></script>
  <script type="text/javascript" src="/js/controllers/bookControllers.js"></script>
@stop