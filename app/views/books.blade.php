@extends('layouts/main')

@section('content')
<div ng-controller="allBooksListCtrl">
  <div class="row">
    <div class="col-md-10 col-md-offset-1 clearfix">
      <span class="pull-left" ng-show="filtered">@{{ filtered.length }} total books</span>
      <span class="pull-right"><input type="text" class="form-control" ng-model="filter" /></span>
    </div>
  </div>
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <table class="table table-striped all-links">
        <tr ng-repeat="book in filtered = (books | filter:filter)" ng-class="book.read ? 'read' : ''">
          <td>@{{ book.title }}</td>
          <td>@{{ book.author_fname }} @{{ book.author_lname }}</td>
          <td>@{{ book.series }}</td>
          <td><span ng-show="book.series">@{{ book.seriesorder }}</span></td>
          <td><div class="glyphicon glyphicon-pencil book-action" data-toggle="modal" data-target="#bookModal" ng-click="edit(book)"></div></td>
          <td><div class="glyphicon book-action" ng-click="markAsRead(book)" ng-class="book.read ? '' : 'glyphicon-ok'"></div></td>
        </tr>
      </table>
    </div>
  </div>
</div>
<div ng-controller="editBookCtrl"></div>
@stop

@section('scripts')

@stop