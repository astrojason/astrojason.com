window.app.directive 'checkAvailibility', ['$http', ($http)->
  require: "ngModel"
  link: (scope, element, attributes, ngModel)->
    element.on 'keyup', ->
      ngModel.$setValidity 'unique', true

    element.on 'blur', ->
      if ngModel.$dirty and ngModel.$valid
        scope.$broadcast 'checkingAvailibility'
        if ngModel.$name == 'username'
          check_Promise = $http.post '/api/checkusername', $.param username: ngModel.$modelValue
        else
          check_Promise = $http.post '/api/checkemail', $.param email: ngModel.$modelValue
        check_Promise.success((data)->
          scope.$broadcast 'checkedAvailibility'
          if data.success
            if data.available
              ngModel.$setValidity 'unique', true
            else
              ngModel.$setValidity 'unique', false
        )
]
