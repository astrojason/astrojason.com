window.app.directive 'linkForm', ->
  templateUrl: 'templates/link-form'
  restrict: 'E',
  controller: 'LinkController',
  link: (scope, element, attrs) ->
    scope.id = attrs.id
