@extends('layouts.layout')

@section('title')
  Welcome
@stop
@section('content')
  <div ng-controller="DashboardController">
    <div class="row" ng-show="!user.id">
      Do you have too much stuff to read, paralyzed by choices. Let me decide! Create an account now.
    </div>
    <div class="row" ng-show="user.id">
      <div class="col-lg-9">
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
                    <input type="text" ng-model="article_search" class="form-control" placeholder="Search Query" />
                    <div class="input-group-addon"><input type="checkbox" ng-model="is_read" /> <label>Include read</label></div>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr ng-show="article_search && link_results.length == 0 && !searching">
                  <td>No results for <strong>{{ article_search }}</strong>
                </tr>
                <tr ng-repeat="link in link_results" ng-show="link_results.length > 0" ng-cloak>
                  <td ng-class="(link.is_read | boolparse) ? 'read' : ''"><link-form link="link" editing="false"></link-form></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div id="daily_links" class="link_container">
            <table class="table table-condensed table-striped table-hover" ng-show="daily_links.length > 0" ng-cloak>
              <thead>
                <tr>
                  <th>Daily <small class="pull-right" ng-class="total_read < 10 ? (total_read < 5 ? 'text-danger' : 'text-warning') : 'text-success'">{{ total_read }} marked as read today</small></th>
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
      <div class="col-lg-3">
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
                  <small>{{ links_read }} of {{ total_links }} ({{ (links_read / total_links) * 100 | number:2 }}%)</small><br />
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
        ng-init="setCategories(<% $book_categories %>); recommendation_category = 'To Read'">
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
                <book-form book="book" editing="false"></book-form>
              </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div
      class="modal fade"
      id="addLinkModal"
      astro-modal
      modal-visible="linkModalOpen">
      <div class="modal-dialog" ng-controller="LinkController">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add a Link</h4>
          </div>
          <div class="modal-body">
            <link-form editing="true" link="newLink"></link-form>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div
      class="modal fade"
      id="addBookModal"
      astro-modal
      modal-visible="bookModalOpen">
      <div class="modal-dialog" ng-controller="BookController">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add a Book</h4>
          </div>
          <div class="modal-body">
            <book-form book="newBook" editing="true"></book-form>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div
      class="modal fade"
      id="addMovieModal"
      astro-modal
      modal-visible="movieModalOpen">
      <div class="modal-dialog" ng-controller="MovieController">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add a Movie</h4>
          </div>
          <div class="modal-body">
            <movie-form movie="newMovie" editing="true"></movie-form>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div
      class="modal fade"
      id="addGameModal"
      astro-modal
      modal-visible="gameModalOpen">
      <div class="modal-dialog" ng-controller="GameController">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add a Game</h4>
          </div>
          <div class="modal-body">
            <game-form game="newGame" editing="true"></game-form>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="recommendGameModal" astro-modal modal-visible="recommendingGame">
      <div class="modal-dialog" ng-controller="GameController">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Recommended Game <span class="glyphicon glyphicon-refresh tool" ng-click="getRecommendation()"></span></h4>
          </div>
          <div class="modal-body">
            <game-form game="game" editing="false"></game-form>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="recommendSongModal" astro-modal modal-visible="recommendingSong">
      <div class="modal-dialog" ng-controller="SongController">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Recommended Song <span class="glyphicon glyphicon-refresh tool" ng-click="getRecommendation()"></span></h4>
          </div>
          <div class="modal-body">
            <song-form song="song" editing="false"></song-form>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div
      class="modal fade"
      id="addSongModal"
      astro-modal
      modal-visible="songModalOpen">
      <div class="modal-dialog" ng-controller="SongController">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add a Song</h4>
          </div>
          <div class="modal-body">
            <song-form song="newSong" editing="true"></song-form>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

  </div>
@stop
