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
              <th>Books<button class="btn btn-success btn-xs pull-right" ng-click="bookModalOpen = true">Add</button></th>
            </tr>
            <tr>
              <th class="input-group">
                <input type="text" ng-model="book_query" class="form-control" placeholder="Search Query" />
                <div class="input-group-addon"><input type="checkbox" ng-model="is_read" /> <label>Include read</label></div>
              </th>
            </tr>
            <tr>
              <th class="input-group">
                <div class="input-group-addon">Category</div>
                <select ng-model="filter_category" class="form-control">
                  <option value="">All</option>
                  <option ng-repeat="category in categories">{{ category }}</option>
                </select>
              </th>
            </tr>
            <tr>
              <th>
                <div ng-class="!filter_category ? 'col-md-3' : 'col-md-5'">
                  <a href="#" ng-click="toggleSort('title')">Title</a>
                </div>
                <div class="col-md-2">
                  <a href="#" ng-click="toggleSort('author_lname')">Author</a>
                </div>
                <div class="col-md-2">
                  <a href="#" ng-click="toggleSort('series')">Series</a>
                </div>
                <div class="col-md-2" ng-show="!filter_category">
                  <a href="#" ng-click="toggleSort('category')">Category</a>
                </div>
                <div class="col-md-3">
                  <a href="#" ng-click="toggleSort('times_recommended')">Times Recommended</a>
                </div>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr ng-show="books.length == 0 && !searching_books">
              <td>No books matching the filtered values</td>
            </tr>
            <tr ng-repeat="book in books" ng-class="{read: book.is_read}">
              <td ng-class="{new: book.new}"><book-form book="book" editing="false" show-category="!filter_category"></book-form></td>
            </tr>
          </tbody>
          <tfoot ng-show="pages > 1">
            <tr>
              <td><paginator page="page" pages="pages" nav-pages="nav_pages"></paginator></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
    <book-modal></book-modal>
  </div>
@stop