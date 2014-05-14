/**
 * Created by jasonsylvester on 5/12/14.
 */
(function() {
  var Book = function() {
    this.id = 0;
    this.title = '';
    this.author_fname = '';
    this.author_lname = '';
    this.series = '';
    this.seriesorder =  0;
    this.read = false;
    this.category = 'Fiction';
  }
  var module = angular.module("bookModels");
  module.value("Book", Book);
}());