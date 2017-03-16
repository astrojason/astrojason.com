@extends('layouts.layout')

@section('title')
  Books :: GoodReads
@stop

@section('content')
  <div ng-controller="GoodReadsController" ng-init="getGoodReadsBooks()">
    <table class="table table-condensed table-striped table-hover">
      <tbody>
        <tr ng-repeat="book in books" ng-class="{read: book.is_read}">
          <td ng-class="{new: book.new}"><book-form book="book" editing="false"></book-form></td>
        </tr>
      </tbody>
      <tfoot ng-show="pages > 1">
        <tr>
          <td>
            <paginator
              page="page"
              pages="pages"
              nav-pages="nav_pages"
              page-change-func="getGoodReadsBooks()">
            </paginator>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
@stop