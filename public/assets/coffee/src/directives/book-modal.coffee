angular.module('astroApp').directive 'bookModal', ->
  templateUrl: 'templates/book-modal'
  restrict: 'E'
  controller: 'BookController'
  replace: true