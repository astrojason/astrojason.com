window.app.directive 'linkForm', ->
  templateUrl: 'templates/link-form'
  restrict: 'E',
  controller: 'LinkController',
  scope: {
    id: '@',
  },
  link: (scope, element, attrs) ->
    scope.id = attrs.id
