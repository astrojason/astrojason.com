angular.module('astroApp').directive 'datetimePicker', ->
  link: (scope, elem, attrs)->
      elem.datetimepicker()
      elem.on 'blur', ->
        scope.$emit 'dateChanged', elem.val()
