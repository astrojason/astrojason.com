@extends('layouts.layout')

@section('title')
  Books
@stop

@section('content')
  <div ng-controller="BookController">
    <table class="table table-condensed table-striped table-hover">
      <thead>
        <tr>
          <th class="input-group">
            <input type="text" ng-model="search_query" class="form-control" placeholder="Search Query" />
            <div class="input-group-addon"><input type="checkbox" ng-model="is_read" /> <label>Include read</label></div>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr ng-show="search_query && search_results.length == 0 && !searching">
          <td>No results for <strong>{{ search_query }}</strong>
        </tr>
        <tr ng-repeat="book in search_results" ng-show="search_results.length > 0" ng-cloak>
          <td ng-class="(book.is_read | boolparse) ? 'read' : ''"><book-form book="book" editing="false"></book-form></td>
        </tr>
      </tbody>
    </table>
    <table class="table table-condensed table-striped table-hover" ng-init="all()">
      <tbody>
        <tr ng-repeat="book in books track by $index">
          <td><book-form book="book" editing="false" index="$index"></book-form></td>
        </tr>
      </tbody>
    </table>
  </div>
@stop