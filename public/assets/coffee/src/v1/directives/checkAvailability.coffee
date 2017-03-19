angular.module('astroApp').directive 'checkAvailability', ['UserResource', (UserResource)->
  require: "ngModel"
  link: (scope, element, attributes, ngModel)->
    element.on 'keyup', ->
      ngModel.$setValidity 'unique', true

    element.on 'blur', ->
      if ngModel.$dirty && ngModel.$valid

        scope.$broadcast 'checkingAvailability'

        if ngModel.$name == 'username'
          check_Promise = UserResource.checkusername(username: ngModel.$modelValue).$promise
        else
          check_Promise = UserResource.checkemail(email: ngModel.$modelValue).$promise

        check_Promise.then ->
          ngModel.$setValidity 'unique', true

        check_Promise.catch ->
          ngModel.$setValidity 'unique', false

        check_Promise.finally ->
          scope.$broadcast 'checkedAvailability'
]
