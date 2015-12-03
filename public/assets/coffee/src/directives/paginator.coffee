angular.module('astroApp').directive 'paginator', [ ->
  restrict: 'E'
  scope:
    page: '='
    pages: '='
    navPages: '='
  templateUrl: 'templates/paginator'
  link: (scope) ->
    scope.setCurrentPage = (page)->
      scope.page = page
]