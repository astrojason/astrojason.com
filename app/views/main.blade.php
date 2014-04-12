@extends('layouts/layout')

@section('content')
  <div ng-controller="todayController">
    @if(Auth::check())
      <div class="row">
        <div class="col-md-8 col-md-offset-1">
          <div class="panel panel-default" ng-show="guitar.length > 0">
            <div class="panel-heading">
              <h3 class="panel-title">Guitar</h3>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in guitar" ng-include="'link-info'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="photography.length > 0">
            <div class="panel-heading">
              <h3 class="panel-title">Photography</h3>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in photography" ng-include="'link-info'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="programming.length > 0">
            <div class="panel-heading">
              <h3 class="panel-title">Programming</h3>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in programming" ng-include="'link-info'"></tr>
            </table>
          </div>
          <div class="panel panel-default" ng-show="links.length > 0">
            <div class="panel-heading">
              <h3 class="panel-title">Unread</h3>
            </div>
            <table class="table table-striped">
              <tr ng-repeat="link in links" ng-include="'link-info'"></tr>
            </table>
          </div>
        </div>
        <div class="col-md-3">
          <div class="caption" ng-controller="nextBookController">
            <h4>Next Book to Read</h4>
            <p>@{{ book.title }}<br /><small>by @{{ book.author_fname }}<span ng-show="book.author_lname" > @{{ book.author_lname }}</span></small></p>
            <p><div class="glyphicon glyphicon-ok book-action right" ng-click="read(book)"></div></p>
          </div>
          <div class="caption" ng-controller="movieRaterController">
            <h4>Order These Movies</h4>
            <table>
              <!-- TODO: Find out how to organize these -->
              <tr ng-repeat="movie in movies">
                <td class="fill-row">@{{ movie.title }}</td>
                <td><div class="glyphicon glyphicon-arrow-down movie-action right" ng-show="!$last"></div></td>
                <td><div class="glyphicon glyphicon-arrow-up movie-action right" ng-show="!$first"></div></td>
              </div>
            </table>
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
    @else
      <h3>Nothing to see here, move along.</h3>
      <img src="/img/whos_awesome.jpg" /><br />
    @endif
  </div>
@stop

@section('scripts')
  <script src="/js/controllers/todayController.js"></script>
  <script src="/js/controllers/nextBookController.js"></script>
  <script src="/js/controllers/movieRaterController.js"></script>
  <script type="text/ng-template" id="link-info">
    <td class="fill-row"><a href="@{{ link.link }}">@{{ link.name }}</a></td>
    <td><div class="glyphicon glyphicon-pencil link-action" data-toggle="modal" data-target="#linkModal" ng-click="edit(link)"></div></td>
    <td><div class="glyphicon glyphicon-off link-action" ng-click="postpone(link, $index)"></div></td>
    <td><div class="glyphicon glyphicon-ok link-action" ng-click="markAsRead(link, $index)"></div></td>
  </script>
@stop