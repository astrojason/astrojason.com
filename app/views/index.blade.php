@extends('layouts.layout')

@section('title')
  Welcome
@stop
@section('content')
  <div ng-controller="DashboardController" ng-init="initDashboard()">
    <div class="row" ng-show="!user.id">
      Do you have too much stuff to read, paralyzed by choices. Let me decide! Create an account now.
    </div>
    <div class="row" ng-show="user.id">
      <div class="col-lg-9 col-sm-12">
        <div ng-show="total_links == 0" ng-cloak>
          You do not have any links, would you like me to <button class="btn btn-default" ng-click="populateLinks()">Randomize</button> some for you?
        </div>
        <div>
          <div id="search_links" class="link_container">
            <loader ng-show="loading_search" ng-cloak></loader>
            <table class="table table-condensed table-striped table-hover">
              <thead>
                <tr>
                  <th class="input-group">
                    <div class="search-wrapper">
                      <input type="text" ng-model="article_search" class="form-control" placeholder="Search Query" />
                      <small
                        class="clear-input glyphicon glyphicon-remove-circle"
                        ng-show="article_search"
                        ng-click="article_search = ''; link_results = []"></small>
                    </div>
                    <div class="input-group-addon"><input type="checkbox" ng-model="is_read" /> <label>Include read</label></div>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr ng-show="article_search && link_results.length == 0 && !searching">
                  <td>No results for <strong>{{ article_search }}</strong>
                </tr>
                <tr ng-repeat="link in link_results" ng-show="link_results.length > 0" ng-cloak>
                  <td ng-class="(link.is_read | boolparse) ? 'read' : ''"><link-form link="link"></link-form></td>
                </tr>
              </tbody>
            </table>
          </div>
          <uib-tabset active="active">
            <uib-tab
              index="$index"
              ng-repeat="display_category in display_categories"
              select="getArticlesForCategory(display_category.category, display_category.num_items, display_category.randomize, display_category.track)"
              active="tab.active"
              disable="tab.disabled">
              <uib-tab-heading>
                {{ display_category.category }}
                <small
                  class="glyphicon glyphicon-refresh tool"
                  ng-show="tab.active && display_category.num_items > 0"
                  ng-click="getArticlesForCategory(display_category.category, display_category.num_items, display_category.randomize, display_category.track)">
                </small>
              </uib-tab-heading>
            </uib-tab>
            <loader ng-show="loading_links" ng-cloak></loader>
            <table class="table table-condensed table-striped table-hover" ng-cloak>
              <tbody>
                <tr ng-repeat="link in links_list" ng-class="link.cssClass()">
                  <td><link-form link="link"></link-form></td>
                </tr>
              </tbody>
            </table>
          </uib-tabset>
          <div id="category_links" class="link_selected">
            <loader ng-show="loading_category" ng-cloak></loader>
            <table class="table table-condensed table-striped table-hover" ng-show="categories.length > 0" ng-cloak>
              <thead>
                <tr>
                  <th class="input-group">
                    <select name="category" ng-model="display_category" ng-options="category for category in categories" class="form-control">
                      <option value="">Select category</option>
                    </select>
                    <div class="input-group-addon">
                      <span
                        class="glyphicon glyphicon-refresh tool"
                        ng-click="getArticlesForCategory(display_category, 10, true, false, 'selected', true)">
                      </span>
                    </div>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="link in selected_links">
                  <td><link-form link="link"></link-form></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-lg-3 hidden-sm">
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
                  href="javascript:<% $bookmarklet %>"
                  class="btn btn-info btn-xs">Read Later</a>
              </td>
            </tr>
            <tr>
              <td>
                <button class="btn btn-success btn-xs" ng-click="linkModalOpen = true">Add Link</button>
                <button class="btn btn-success btn-xs" ng-click="bookModalOpen = true">Add Book</button>
                <button class="btn btn-success btn-xs" ng-click="movieModalOpen = true">Add Movie</button>
              </td>
            </tr>
            <tr>
              <td>
                <button class="btn btn-success btn-xs" ng-click="gameModalOpen = true">Add Game</button>
                <button class="btn btn-success btn-xs" ng-click="songModalOpen = true">Add Song</button>
              </td>
            </tr>
            <tr ng-show="books_toread" ng-cloak>
              <td><button class="btn btn-success btn-xs" ng-click="recommendingBook = true">Book Recommendation</button></td>
            </tr>
            <tr ng-show="games_toplay" ng-cloak>
              <td><button class="btn btn-success btn-xs" ng-click="recommendingGame = true">Game Recommendation</button></td>
            </tr>
            <tr ng-show="songs_toplay" ng-cloak>
              <td><button class="btn btn-success btn-xs" ng-click="recommendingSong = true">Song Recommendation</button></td>
            </tr>
            <tr ng-show="total_books || total_books" ng-cloak>
              <td>
                <div ng-show="total_links" ng-cloak>
                  <h7>Links Read</h7><br />
                  <small>{{ links_read }} of {{ total_links }} ({{ (links_read / total_links) * 100 | number:2 }}%) (<span ng-class="total_read < 10 ? (total_read < 5 ? 'text-danger' : 'text-warning') : 'text-success'">{{ total_read }} today <small class="glyphicon glyphicon-refresh tool" ng-click="refreshReadCount()"></small></span>)</small><br />
                </div>
                <div ng-show="total_books" ng-cloak>
                  <h7>Books Read</h7><br />
                  <small>{{ books_read }} of {{ total_books }} ({{ (books_read / total_books) * 100 | number:2 }}%)</small>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div
        class="modal fade"
        id="recommendBookModal"
        modal-visible="recommendingBook"
        astro-modal>
      <div
        class="modal-dialog"
        ng-controller="BookController"
        ng-init="setCategories(<% $book_categories %>); recommendation_category = 'To Read'; triggerRecommender()">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Get a Book Recommendation</h4>
          </div>
          <div class="modal-body">
            <loader ng-show="getting_recomendation" ng-cloak></loader>
            <form name="book_reco_form" class="form-inline" novalidate>
              <div class="row">
                <div class="col-md-8">
                  <select ng-model="recommendation_category" class="form-control">
                    <option ng-repeat="category in categories">{{ category }}</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <button class="btn btn-primary" ng-click="getRecommendation()" ng-disabled="!recommendation_category">Get Recommendation</button>
                </div>
              </div>
            </form>
            <div class="row" ng-show="book">
              <div class="col-md-12 top-margin">
                <div ng-class="{'alert alert-warning' : book.times_recommended > 5}">
                  <book-form book="book" recommendation="true"></book-form>
                </div>
              </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <link-modal></link-modal>

    <book-modal></book-modal>

    <movie-modal></movie-modal>

    <game-modal></game-modal>

    <div
      class="modal fade"
      id="recommendGameModal"
      astro-modal
      modal-visible="recommendingGame">
      <div class="modal-dialog" ng-controller="GameController" ng-init="triggerRecommender()">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Recommended Game <span class="glyphicon glyphicon-refresh tool" ng-click="getRecommendation()"></span></h4>
          </div>
          <div class="modal-body">
            <div ng-class="{'alert alert-warning' : game.times_recommended > 5}">
              <game-form game="game" recommendation="true" show-platform="true"></game-form>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div
      class="modal fade"
      id="recommendSongModal"
      astro-modal
      modal-visible="recommendingSong">
      <div class="modal-dialog" ng-controller="SongController" ng-init="triggerRecommender()">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Recommended Song <span class="glyphicon glyphicon-refresh tool" ng-click="getRecommendation()"></span></h4>
          </div>
          <div class="modal-body">
            <div ng-class="{'alert alert-warning' : song.times_recommended > 5}">
              <song-form song="song"></song-form>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <song-modal></song-modal>

  </div>
@stop
