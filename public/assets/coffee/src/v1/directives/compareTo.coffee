angular.module('astroApp').directive 'compareTo', ->
  require: "ngModel"
  scope:
    confirmValue: '=compareTo'
  link: (scope, element, attributes, ngModel)->

    ngModel.$validators.compareTo = (modelValue)->
      return modelValue == scope.confirmValue

    scope.$watch "confirmValue", ->
      ngModel.$validate()
