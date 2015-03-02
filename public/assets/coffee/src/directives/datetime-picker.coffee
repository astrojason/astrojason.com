window.app.directive 'datetimePicker', ->
  return link: (scope, elem, attrs)->
      elem.datetimepicker()
      elem.on 'blur', ->
        scope.$emit 'dateChanged', elem.val()
