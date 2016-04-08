angular.module('astroApp').directive 'bookForm', ->
  templateUrl: 'templates/book-form'
  restrict: 'E'
  controller: 'BookController'
  scope:
    book: '='
    showCategory: '='
    recommendation: '='
