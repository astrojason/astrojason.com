angular.module('astroApp').directive 'linkForm', ->
  templateUrl: 'templates/link-form'
  restrict: 'E'
  controller: 'ArticleController'
  scope:
    link: '='
    showCategory: '='