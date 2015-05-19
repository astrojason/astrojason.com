@extends('layouts.layout')

@section('title')
  Books
@stop

@section('content')
  <div ng-controller="BookController" ng-init="setCategories(<% $book_categories %>)">
    <div class="row">
      <div class="col-md-12">
        <loader ng-show="loading_books" ng-cloak></loader>
        <table class="table table-condensed table-striped table-hover" ng-init="initList()">
          <thead>
          <tr>
            <th class="input-group">
              <input type="text" ng-model="book_query" class="form-control" placeholder="Search Query" />
              <div class="input-group-addon"><input type="checkbox" ng-model="is_read" /> <label>Include read</label></div>
            </th>
            <th class="input-group">
              <div class="input-group-addon">Sort</div>
              <select ng-model="sort" class="form-control">
                <option value="">None</option>
                <option value="author_lname">Author</option>
                <option value="series">Series</option>
                <option value="title">Title</option>
              </select>
              <div class="input-group-addon">Category</div>
              <select ng-model="filter_category" class="form-control">
                <option value="">All</option>
                <option ng-repeat="category in categories">{{ category }}</option>
              </select>
            </th>
          </thead>
          <tbody>
            <tr ng-show="books.length == 0 && !searching_books">
              <td>No books matching the filtered values</strong>
            </tr>
            <tr ng-repeat="book in books">
              <td><book-form book="book" editing="false"></book-form></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
@stop