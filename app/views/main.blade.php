@extends('layouts/layout')

@section('content')
  @if(Auth::check())
    <div ng-controller="todayController">
      <div class="row">
        <div class="col-md-8 col-md-offset-1">
          <div class="pull-left">
            <input type="checkbox" id="amhome" ng-model="amhome" /> <label for="amhome">I am at Home</label>
          </div>
          <!-- TODO: Wire this up -->
          <a href="#" class="btn pull-right">Add Link</a>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8 col-md-offset-1">
          <div class="panel panel-default" ng-show="athome.length > 0 && amhome">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">At Home</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('At Home')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in athome" ng-include="'link-info'"></tr>
            </table>
          </div>

          <div class="panel panel-default" ng-show="cooking.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">Cooking</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('Cooking')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in cooking" ng-include="'link-info'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="exercise.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">Exercise</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('Exercise')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in exercise" ng-include="'link-info'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="forreview.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">For Review</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('For Review')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in forreview" ng-include="'link-info'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="forthehouse.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">For the House</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('For the House')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in forthehouse" ng-include="'link-info'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="groups.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">Groups</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('Groups')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in groups" ng-include="'link-info'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="guitar.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">Guitar</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('Guitar')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in guitar" ng-include="'link-info'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="photography.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">Photography</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('Photography')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in photography" ng-include="'link-info'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="projects.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">Projects</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('Projects')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in projects" ng-include="'link-info'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="programming.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">Programming</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('Programming')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in programming" ng-include="'link-info'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="wishlist.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">Wishlist</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('Wishlist')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in wishlist" ng-include="'link-info'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="wordpress.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">Wordpress</h3>
              <div class="glyphicon glyphicon-refresh link-action pull-right" ng-click="refreshCategory('Wordpress')"></div>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in wordpress" ng-include="'link-info'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="unread.length > 0">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">Unread</h3>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in unread" ng-include="'link-info'"></tr>
            </table>
          </div>
        </div>
        <div class="col-md-3">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">Controls</h4>
            </div>
            <div class="panel-body">
              <p><button ng-click="migrate()">Migrate</button></p>
              <p>
                <a href="javascript:(
                function(){
                var jsCode = document.createElement('script');
                jsCode.setAttribute('src', 'http://www.astrojason.com/js/bookmarklet.min.js');
                document.body.appendChild(jsCode);
                })()">Read Later</a>
              </p>
            </div>
          </div>
          <div class="panel panel-default" ng-controller="nextBookController" ng-show="book">
            <div class="panel-heading">
              <h4 class="panel-title">Next Book to Read</h4>
            </div>
            <div class="panel-body">
              <p>@{{ book.title }}<br /><small>by @{{ book.author_fname }}<span ng-show="book.author_lname" > @{{ book.author_lname }}</span></small></p>
            </div>
            <div class="panel-footer clearfix">
              <div class="glyphicon glyphicon-pencil link-action" data-toggle="modal" data-target="#bookModal" ng-click="edit(book)"></div>
              <div class="glyphicon glyphicon-ok book-action right" ng-click="read(book)"></div>
            </div>
          </div>

          <!-- TODO: Add next videogame -->
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">Next Game to Play</h4>
            </div>
            <div class="panel-body">
              <p>@{{ game.title }} - @{{ game.platform }}</p>
            </div>
            <div class="panel-footer clearfix">
              <div class="glyphicon glyphicon-pencil game-action" data-toggle="modal" data-target="#gameModal" ng-click="edit(game)"></div>
              <div class="glyphicon glyphicon-ok game-action right" ng-click="read(game)"></div>
            </div>
          </div>

          <div class="panel panel-default" ng-controller="movieRaterController" ng-show="movies">
            <div class="panel-heading">
              <h4 class="panel-title">Order these Movies</h4>
            </div>
            <div class="panel-body">
              <table>
                <!-- TODO: Find out how to organize these -->
                <!-- TODO: Enable drag-n-drop -->
                <tr ng-repeat="movie in movies">
                  <td class="fill-row">@{{ movie.title }}</td>
                  <td><div class="glyphicon glyphicon-arrow-down movie-action right" ng-show="!$last"></div></td>
                  <td><div class="glyphicon glyphicon-arrow-up movie-action right" ng-show="!$first"></div></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="linkModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="myModalLabel">Link Edit</h4>
            </div>
            <div class="modal-body">
              <form id="link-edit" ng-controller="todayController" ng-subimt="editLink" data-abide>
                <div class="row">
                  <div class="col-lg-10 col-lg-offset-1 input-group">
                    <label class="input-group-addon">Name:</label>
                    <input class="form-control" type="text" ng-model="editing_link.name" />
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-10 col-lg-offset-1 input-group">
                    <label class="input-group-addon">URL:</label>
                    <input class="form-control" type="text" ng-model="editing_link.link" />
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-10 col-lg-offset-1 input-group">
                    <label class="input-group-addon">Category:</label>
                    <select class="form-control" ng-model="editing_link.category">
                      <option ng-repeat="category in categories">@{{ category.category }}</option>
                    </select>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" ng-click="save()">Save</button>
            </div>
          </div>
        </div>
      </div>
      <!-- TODO: Create the book editing modal -->
    </div>
  @else
    <div class="row">
      <div class="col-lg-12 center-block">
        <h3>Nothing to see here, move along.</h3>
        <img src="/img/whos_awesome.jpg" /><br />
      </div>
    </div>
  @endif
@stop

@section('scripts')
  @if(Auth::check())
    <script src="/js/controllers/todayController.js"></script>
    <script src="/js/controllers/nextBookController.js"></script>
    <script src="/js/controllers/movieRaterController.js"></script>
    <script type="text/ng-template" id="link-info">
      <td class="fill-row"><a href="@{{ link.link }}" target="_blank">@{{ link.name }}</a></td>
      <td><div class="glyphicon glyphicon-pencil link-action" data-toggle="modal" data-target="#linkModal" ng-click="edit(link, $index)"></div></td>
      <td><div class="glyphicon glyphicon-off link-action" ng-click="postpone(link, $index)"></div></td>
      <td><div class="glyphicon glyphicon-ok link-action" ng-click="markAsRead(link, $index)"></div></td>
    </script>
  @endif
@stop