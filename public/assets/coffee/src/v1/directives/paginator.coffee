angular.module('astroApp').directive 'paginator', [ ->
  restrict: 'E'
  scope:
    page: '='
    pages: '='
    navPages: '='
    pageChangeFunc: '&?'
  templateUrl: 'templates/paginator'
  link: (scope) ->
    scope.setCurrentPage = (page)->
      scope.page = page
      if scope.pageChangeFunc
        scope.pageChangeFunc()
]