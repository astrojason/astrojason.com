@extends('layouts/main')

@section('content')
  @if(Auth::check())
    <div class="col-md-7 col-md-offset-1">
      <div ng-controller="todaysLinksListCtrl as linkCtrl" class="row">
        <div class="col-md-12">
          <div class="panel panel-default" ng-show="linkCtrl.unread.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">Unread</h3>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in linkCtrl.unread" ng-include src="'js/templates/linkInfo.html'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="linkCtrl.cooking.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">Cooking</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('Cooking')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in linkCtrl.cooking" ng-include src="'js/templates/linkInfo.html'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="linkCtrl.exercise.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">Exercise</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('Exercise')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in linkCtrl.exercise" ng-include src="'js/templates/linkInfo.html'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="linkCtrl.forreview.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">For Review</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('For Review')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in linkCtrl.forreview" ng-include src="'js/templates/linkInfo.html'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="linkCtrl.forthehouse.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">For the House</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('For the House')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in linkCtrl.forthehouse" ng-include src="'js/templates/linkInfo.html'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="linkCtrl.groups.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">Groups</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('Groups')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in linkCtrl.groups" ng-include src="'js/templates/linkInfo.html'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="linkCtrl.guitar.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">Guitar</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('Guitar')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in linkCtrl.guitar" ng-include src="'js/templates/linkInfo.html'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="linkCtrl.photography.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">Photography</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('Photography')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in linkCtrl.photography" ng-include src="'js/templates/linkInfo.html'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="linkCtrl.projects.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">Projects</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('Projects')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in linkCtrl.projects" ng-include src="'js/templates/linkInfo.html'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="linkCtrl.programming.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">Programming</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('Programming')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in linkCtrl.programming" ng-include src="'js/templates/linkInfo.html'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="linkCtrl.wishlist.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">Wishlist</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('Wishlist')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in linkCtrl.wishlist" ng-include src="'js/templates/linkInfo.html'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="linkCtrl.wordpress.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">Wordpress</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('Wordpress')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in linkCtrl.wordpress" ng-include src="'js/templates/linkInfo.html'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="linkCtrl.athome.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">At Home</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('At Home')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in linkCtrl.athome" ng-include src="'js/templates/linkInfo.html'"></tr>
            </table>
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
            <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#linkModal" ng-click="ec.new()" ng-controller="editLinkCtrl as ec">Add Link</button>

          </p>
          <p>
            <a class="btn btn-primary btn-xs" href="javascript:(
                  function(){
                  var jsCode = document.createElement('script');
                  jsCode.setAttribute('src', 'http://www.astrojason.com/js/bookmarklet.min.js');
                  document.body.appendChild(jsCode);
                  })()">Read Later</a>
          </p>
        </div>
      </div>
    </div>
    <div class="col-md-1">&nbsp;</div>
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

@stop