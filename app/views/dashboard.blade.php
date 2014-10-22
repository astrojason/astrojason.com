@extends('layouts/main')

@section('content')
  @if(Auth::check())
    <div ng-controller="todaysLinksController as linkCtrl" class="row">
      <div class="col-md-12" ng-show="linkCtrl.success">
        <div class="panel panel-default">
          <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left">Added today: @{{ linkCtrl.added || 0 }}, read today: @{{ linkCtrl.read || 0 }}</h3>
          </div>
        </div>
        <div class="panel panel-default" ng-show="linkCtrl.daily.length > 0">
          <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left">Daily</h3>
          </div>
          <table class="table table-striped">
            <tr ng-repeat="link in linkCtrl.daily" ng-include src="'js/templates/linkInfo.html'"></tr>
          </table>
        </div>
        <div class="panel panel-default" ng-show="linkCtrl.hockey.length > 0">
          <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left">Hockey Exercise</h3>
            <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('Hockey Exercise', 3)"></div>
          </div>
          <table class="table table-striped">
            <tr ng-repeat="link in linkCtrl.hockeyexercise" ng-include src="'js/templates/linkInfo.html'"></tr>
          </table>
        </div>
        <div class="panel panel-default" ng-show="linkCtrl.programming.length > 0">
          <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left">Programming</h3>
            <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('Programming', 3)"></div>
          </div>
          <table class="table table-striped">
            <tr ng-repeat="link in linkCtrl.programming" ng-include src="'js/templates/linkInfo.html'"></tr>
          </table>
        </div>
        <div class="panel panel-default" ng-show="linkCtrl.unread.length > 0">
          <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left">Unread</h3>
            <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('Unread', 10)"></div>
          </div>
          <table class="table table-striped">
            <tr ng-repeat="link in linkCtrl.unread" ng-include src="'js/templates/linkInfo.html'"></tr>
          </table>
        </div>
        <div class="panel panel-default" ng-show="linkCtrl.cooking.length > 0">
          <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left">Cooking</h3>
            <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('Cooking', 1)"></div>
          </div>
          <table class="table table-striped">
            <tr ng-repeat="link in linkCtrl.cooking" ng-include src="'js/templates/linkInfo.html'"></tr>
          </table>
        </div>
        <div class="panel panel-default" ng-show="linkCtrl.exercise.length > 0">
          <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left">Exercise</h3>
            <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('Exercise', 1)"></div>
          </div>
          <table class="table table-striped">
            <tr ng-repeat="link in linkCtrl.exercise" ng-include src="'js/templates/linkInfo.html'"></tr>
          </table>
        </div>
        <div class="panel panel-default" ng-show="linkCtrl.forreview.length > 0">
          <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left">For Review</h3>
            <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('For Review', 1)"></div>
          </div>
          <table class="table table-striped">
            <tr ng-repeat="link in linkCtrl.forreview" ng-include src="'js/templates/linkInfo.html'"></tr>
          </table>
        </div>
        <div class="panel panel-default" ng-show="linkCtrl.forthehouse.length > 0">
          <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left">For the House</h3>
            <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('For the House', 1)"></div>
          </div>
          <table class="table table-striped">
            <tr ng-repeat="link in linkCtrl.forthehouse" ng-include src="'js/templates/linkInfo.html'"></tr>
          </table>
        </div>
        <div class="panel panel-default" ng-show="linkCtrl.groups.length > 0">
          <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left">Groups</h3>
            <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('Groups', 1)"></div>
          </div>
          <table class="table table-striped">
            <tr ng-repeat="link in linkCtrl.groups" ng-include src="'js/templates/linkInfo.html'"></tr>
          </table>
        </div>
        <div class="panel panel-default" ng-show="linkCtrl.guitar.length > 0">
          <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left">Guitar</h3>
            <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('Guitar', 1)"></div>
          </div>
          <table class="table table-striped">
            <tr ng-repeat="link in linkCtrl.guitar" ng-include src="'js/templates/linkInfo.html'"></tr>
          </table>
        </div>
        <div class="panel panel-default" ng-show="linkCtrl.photography.length > 0">
          <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left">Photography</h3>
            <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('Photography', 1)"></div>
          </div>
          <table class="table table-striped">
            <tr ng-repeat="link in linkCtrl.photography" ng-include src="'js/templates/linkInfo.html'"></tr>
          </table>
        </div>
        <div class="panel panel-default" ng-show="linkCtrl.projects.length > 0">
          <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left">Projects</h3>
            <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('Projects', 1)"></div>
          </div>
          <table class="table table-striped">
            <tr ng-repeat="link in linkCtrl.projects" ng-include src="'js/templates/linkInfo.html'"></tr>
          </table>
        </div>
        <div class="panel panel-default" ng-show="linkCtrl.wishlist.length > 0">
          <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left">Wishlist</h3>
            <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('Wishlist', 1)"></div>
          </div>
          <table class="table table-striped">
            <tr ng-repeat="link in linkCtrl.wishlist" ng-include src="'js/templates/linkInfo.html'"></tr>
          </table>
        </div>
        <div class="panel panel-default" ng-show="linkCtrl.wordpress.length > 0">
          <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left">Wordpress</h3>
            <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('Wordpress', 1)"></div>
          </div>
          <table class="table table-striped">
            <tr ng-repeat="link in linkCtrl.wordpress" ng-include src="'js/templates/linkInfo.html'"></tr>
          </table>
        </div>
        <div class="panel panel-default" ng-show="linkCtrl.athome.length > 0">
          <div class="panel-heading clearfix">
            <h3 class="panel-title pull-left">At Home</h3>
            <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="linkCtrl.refreshCategory('At Home', 1)"></div>
          </div>
          <table class="table table-striped">
            <tr ng-repeat="link in linkCtrl.athome" ng-include src="'js/templates/linkInfo.html'"></tr>
          </table>
        </div>
      </div>
      <div class="col-md-12" ng-show="!linkCtrl.success">
        @{{ linkCtrl.error }}
      </div>
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

@section('rightrail')
  @if(Auth::check())
    <div class="panel panel-default" ng-controller="nextBookController as bookCtrl" ng-show="bookCtrl.next_book">
      <div class="panel-heading clearfix">
        <h4 class="panel-title pull-left">Next Book to Read</h4>
        <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="bookCtrl.getNextBook()"></div>
      </div>
      <div class="panel-body">
        <p>@{{ bookCtrl.next_book.title }}<span ng-show="bookCtrl.next_book.series"> @{{ bookCtrl.next_book.series }} #@{{ bookCtrl.next_book.seriesorder }}</span><br /><small>by @{{ bookCtrl.next_book.author_fname }}<span ng-show="bookCtrl.next_book.author_lname" > @{{ bookCtrl.next_book.author_lname }}</span></small></p>
      </div>
      <div class="panel-footer clearfix">
        <div class="pull-right">
          <div class="glyphicon glyphicon-pencil book-action" data-toggle="modal" data-target="#bookModal" ng-click="bookCtrl.edit(bookCtrl.next_book)"></div>
          <div class="glyphicon glyphicon-ok book-action right" ng-click="bookCtrl.read(bookCtrl.next_book)"></div>
        </div>
      </div>
    </div>
    <div class="panel panel-default" ng-controller="nextGameController as gameCtrl" ng-show="gameCtrl.game">
      <div class="panel-heading clearfix">
        <h4 class="panel-title pull-left">Next Game to Play</h4>
        <div class="glyphicon glyphicon-refresh game-action pull-right" ng-click="gameCtrl.getNextGame()"></div>
      </div>
      <div class="panel-body">
        <p>@{{ gameCtrl.game.title }}<br /><small>@{{ gameCtrl.game.platform }}</small></p>
      </div>
      <div class="panel-footer clearfix">
        <div class="pull-right">
          <div class="glyphicon glyphicon-pencil game-action" data-toggle="modal" data-target="#gameModal" ng-click="gameCtrl.edit(gameCtrl.game)"></div>
          <div class="glyphicon glyphicon-ok game-action right" ng-click="gameCtrl.played(game)"></div>
        </div>
      </div>
    </div>
  @endif
@stop

@section('scripts')

@stop