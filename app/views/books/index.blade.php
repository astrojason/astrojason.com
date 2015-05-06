@extends('layouts.layout')

@section('title')
  Books
@stop

@section('content')
  <div ng-controller="BookController">
    <div class="row">
      <div class="col-md-12">
        <loader ng-show="searching_books" ng-cloak></loader>
        <table class="table table-condensed table-striped table-hover">
          <thead>
            <tr>
              <th class="input-group">
                <input type="text" ng-model="book_query" class="form-control" placeholder="Search Query" />
                <div class="input-group-addon"><input type="checkbox" ng-model="is_read" /> <label>Include read</label></div>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr ng-show="book_query && (search_results | filter:{removed: '!' + true}).length == 0 && !searching_books">
              <td>No results for <strong>{{ book_query }}</strong>
            </tr>
            <tr ng-repeat="book in search_results | filter:{deleted: '!' + true}" ng-show="(search_results | filter:{removed: '!' + true}).length > 0" ng-cloak>
              <td ng-class="(book.is_read | boolparse) ? 'read' : ''"><book-form book="book" editing="false"></book-form></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <loader ng-show="loading_books" ng-cloak></loader>
      <table class="table table-condensed table-striped table-hover" ng-init="all()">
        <tbody>
          <tr ng-repeat="book in books | filter:{removed: '!' + true}">
            <td><book-form book="book" editing="false"></book-form></td>
          </tr>
        </tbody>
      </table>
      </div>
    </div>
  </div>
@stop