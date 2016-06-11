angular.module('astroApp').directive 'selectOnFocus', [->
  restrict: 'A'
  priority: -1
  link: (scope, element)->
    element.on 'focus', ->
      element.select()
]