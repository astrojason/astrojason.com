@extends('layouts/main')

@section('content')
  <div ng-controller="allBooksController as bookCtrl">
    <div class="row">
      <div class="col-md-12 clearfix page-controls">
        <span class="pull-left" ng-show="filtered">@{{ filtered.length }} total books</span>
        <span class="pull-right">
          <form class="form-inline" role="form">
            <select ng-model="sortOrder" class="form-control">
              <option value="title">Title</option>
              <option value="seriesorder">Series</option>
            </select>
            <input type="text" class="form-control" ng-model="filter" />
          </form>
        </span>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <table class="table table-striped all-links">
          <tr ng-repeat="book in filtered = (bookCtrl.books | filter:filter) | orderBy: sortOrder" ng-class="book.read ? 'read' : ''">
            <td>@{{ book.title }}</td>
            <td style="white-space: nowrap">@{{ book.author_fname }} @{{ book.author_lname }}</td>
            <td style="white-space: nowrap">@{{ book.series }}</td>
            <td><span ng-show="book.series">@{{ book.seriesorder }}</span></td>
            <td><div class="glyphicon glyphicon-pencil book-action" data-toggle="modal" data-target="#bookModal" ng-click="bookCtrl.edit(book)"></div></td>
            <td><div class="glyphicon book-action" ng-click="bookCtrl.markAsRead(book)" ng-class="book.read ? '' : 'glyphicon-ok'"></div></td>
            <td><div class="glyphicon glyphicon-remove-sign book-action" ng-click="bookCtrl.delete(book, $index)"></div></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
@stop

@section('scripts')

@stop