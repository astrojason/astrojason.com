angular.module('astroApp').directive 'linkModal', ->
  templateUrl: 'templates/link-modal'
  restrict: 'E'
  controller: 'ArticleController'
  replace: true