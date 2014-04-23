/**
 * Created by jasonsylvester on 4/1/14.
 */

var astroApp = angular.module('astroApp', []);

astroApp.factory('linkSvc', function(){
  var editing_link = {};
  return {
    set: function(link) {
      editing_link = link;
    },
    get: function() {
      return editing_link;
    }
  }
});

astroApp.factory('bookSvc', function(){
  var editing_book = {};
  return {
    set: function(book) {
      editing_book = book;
    },
    get: function() {
      return editing_book;
    }
  }
});

astroApp.factory('movieSvc', function(){
  var editing_movie = {};
  return {
    set: function(movie) {
      editing_movie = movie;
    },
    get: function() {
      return editing_movie;
    }
  }
});