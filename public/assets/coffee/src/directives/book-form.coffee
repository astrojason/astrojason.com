window.app.directive 'bookForm', ->
  templateUrl: 'templates/book-form'
  restrict: 'E',
  controller: 'BookController',
  scope: {
    book: '=',
    editing: '=',
    index: '='
  }
