angular.module('astroApp').directive 'dismissModal', [
  ->
    restrict: 'A'
    link: (scope, elem)->
      elem.on 'click', ->
        scope.$dismiss()
]