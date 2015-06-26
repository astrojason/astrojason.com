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
          </thead>
          <tbody>
            <tr>
              <td class="input-group">
                <input type="text" ng-model="book_query" class="form-control" placeholder="Search Query" />
                <div class="input-group-addon"><input type="checkbox" ng-model="is_read" /> <label>Include read</label></div>
              </td>
            </tr>
            <tr>
              <td class="input-group">
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
              </td>
            </tr>
            <tr ng-show="books.length == 0 && !searching_books">
              <td>No books matching the filtered values</td>
            </tr>
            <tr ng-repeat="book in books" ng-class="{read: book.is_read}">
              <td ng-class="{new: book.new}"><book-form book="book" editing="false"></book-form></td>
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