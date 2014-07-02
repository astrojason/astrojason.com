/**
 * Created by jasonsylvester on 5/29/14.
 */
var app = angular.module('astroApp', []);

app.factory('linkSvc', ['$http', '$rootScope', function($http, $rootScope){
  this.editing_link = {};
  return {
    remove: function(category, index, controller) {
      var category_name = category.toLowerCase().replace(/ /g, '');
      controller[category_name].splice(index, 1);
    },

    postpone: function(link, index, controller) {
      this.remove(link.category, index, controller)
    },

    get: function() {
      return this.editing_link;
    },

    set: function(link) {
      this.editing_link = link;
    },

    create: function() {
      this.editing_link = {id: 0, name: '', link: '', category: 'Unread', read: 0};
      return this.editing_link;
    },

    markAsRead: function(link) {
      $http.get('/api/link/' + link.id + '/read').success(function(data){
        if(data.success) {
          $rootScope.$broadcast('LINK_READ');
        }
      });
    },

    edit: function(link) {
      this.set(link);
      $rootScope.$broadcast('EDITING_LINK');
    }
  }
}]);

app.factory('bookSvc', ['$http', '$rootScope', function($http, $rootScope){
  this.editing_book = {};
  return {
    set: function(book) {
      this.editing_book = book;
    },

    get: function() {
      return this.editing_book;
    },

    create: function() {
      this.editing_book = {id: 0, title: '', category: 'To Read'};
      return this.editing_book;
    },

    edit: function(book) {
      this.set(book);
      $rootScope.$broadcast('EDITING_BOOK');
    },

    markAsRead: function(book) {
      $http.get('/api/book/' + book.id + '/read').success(function(data){
        if(data.success) {
          $rootScope.$broadcast('BOOK_READ');
        }
      });
    },

    delete: function() {

    }
  }
}]);

app.factory('gameSvc', ['$http', '$rootScope', function($http, $rootScope){
  this.editing_game = {};
  return {
    set: function(game) {
      this.editing_game = game;
    },

    get: function() {
      return this.editing_game;
    },

    create: function() {
      this.editing_game = {id: 0, title: '', platform: ''};
      return this.editing_game;
    },

    edit: function(game) {
      this.set(game);
      $rootScope.$broadcast('EDITING_GAME');
    },

    markAsPlayed: function(game) {
      $http.get('/api/game/' + game.id + '/played').success(function(data){
        if(data.success) {
          $rootScope.$broadcast('GAME_PLAYED');
        }
      });
    },

    delete: function() {

    }
  }
}]);