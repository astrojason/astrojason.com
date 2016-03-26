angular.module('astroApp').directive 'linkForm', ->
  templateUrl: 'templates/link-form'
  restrict: 'E'
  controller: 'LinkController'
  scope:
    link: '='
    editing: '='
    showCategory: '='