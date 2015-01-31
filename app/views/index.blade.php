@extends('layouts/layout')

@section('title')
  Welcome
@stop
<!--TODO: Add form for new books -->
<!--TODO: Add the ability to populate the recommendations for a user without any-->
@section('content')
  <div ng-controller="DashboardController">
    <div class="row" ng-show="!user.id">
      Do you have too much stuff to read, paralyzed by choices. Let me decide! Create an account now.
    </div>
    <div class="row" ng-show="user.id">
      <div class="col-lg-10">
        <div ng-show="addingLink" ng-cloak>
          <link-form editing="addingLink" link="newLink"></link-form>
        </div>
        <div ng-show="total_links == 0" ng-cloak>
          You do not have any links, would you like me to <button class="btn btn-default">Randomize</button> some for you?
        </div>
        <div ng-show="total_links > 0" ng-cloak>
          <div id="search_links" class="link_container">
            <loader ng-show="loading_search" ng-cloak></loader>
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
                  <td>No results for <strong>@{{ search_query }}</strong>
                </tr>
                <tr ng-repeat="link in search_results" ng-show="search_results.length > 0" ng-cloak>
                  <td ng-class="(link.is_read | boolparse) ? 'read' : ''"><link-form link="link" editing="false"></link-form></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div id="daily_links" class="link_container">
            <table class="table table-condensed table-striped table-hover" ng-show="daily_links.length > 0" ng-cloak>
              <thead>
                <tr>
                  <th>Daily <small class="pull-right" ng-class="total_read < 10 ? (total_read < 5 ? 'text-danger' : 'text-warning') : 'text-success'">@{{ total_read }} marked as read today</small></th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="link in daily_links">
                  <td><link-form link="link" editing="false"></link-form></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div id="unread_links" class="link_container">
            <loader ng-show="loading_unread" ng-cloak></loader>
            <table class="table table-condensed table-striped table-hover" ng-show="unread_links.length > 0" ng-cloak>
              <thead>
                <tr>
                  <th>Unread<span class="glyphicon glyphicon-refresh tool pull-right" ng-click="refreshUnreadArticles()"></span></th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="link in unread_links">
                  <td><link-form link="link" editing="false"></link-form></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div id="category_links" class="link_container">
            <loader ng-show="loading_category" ng-cloak></loader>
            <table class="table table-condensed table-striped table-hover" ng-show="categories.length > 0" ng-cloak>
              <thead>
                <tr>
                  <th class="input-group">
                    <select name="category" ng-model="display_category" ng-options="category for category in categories" class="form-control">
                      <option value="">Select category</option>
                    </select>
                    <div class="input-group-addon"><span class="glyphicon glyphicon-refresh tool" ng-click="getCategoryArticles()"></span></div>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  ng-repeat="link in selected_links"
                  ng-class="link.times_loaded > 10 ? (link.times_loaded > 50 ? 'danger' : 'warning') : ''">
                  <td><link-form link="link" editing="false"></link-form></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-lg-2">
        <table class="table table-condensed table-hover">
          <thead>
            <tr>
              <th>Controls</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <a
                  href="javascript:{{ $bookmarklet }}"
                  class="btn btn-info btn-xs">Read Later</a>
              </td>
            </tr>
            <tr>
              <td><button ng-click="addLink()" class="btn btn-success btn-xs">Add Link</button></td>
            </tr>
            <tr>
              <td><button class="btn btn-success btn-xs" data-toggle="modal" data-target="#bookModal">Book Recommendation</button></td>
            </tr>
            <tr>
              <td>
                <h7>Links Read</h7><br />
                <small>@{{ links_read }} of @{{ total_links }} (@{{ (links_read / total_links) * 100 | number:2 }}%)</small>
                <h7>Books Read</h7><br />
                <small>@{{ books_read }} of @{{ total_books }} (@{{ (books_read / total_books) * 100 | number:2 }}%)</small>
              </td>
            </tr>
          </tbody>
      </div>
    </div>
  </div>

  <div class="modal fade" id="bookModal" ng-controller="BookController" ng-init="setCategories({{ $book_categories }})">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Get a Book Recommendation</h4>
        </div>
        <div class="modal-body">
          <loader ng-show="getting_recomendation" ng-cloak></loader>
          <form class="form-inline">
            <div class="row">
              <div class="col-md-8">
                <select ng-model="recommendation_category" class="form-control">
                  <option ng-repeat="category in categories">@{{ category }}</option>
                </select>
              </div>
              <div class="col-md-4">
                <button class="btn btn-primary" ng-click="getRecommendation()" ng-disabled="!recommendation_category">Get Recommendation</button>
              </div>
            </div>
            <div class="row" ng-show="book">
              <div class="col-md-12 top-margin">
                <book-form book="book" editing="false"></book-form>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
@stop
