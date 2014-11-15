window.spin_opts =
  lines: 13
  length: 20
  width: 10
  radius: 30
  corners: 1
  rotate: 0
  direction: 1
  color: '#FFF'
  speed: 1
  trail: 60
  shadow: false
  hwaccel: false
  className: 'spinner'
  zIndex: 2e9
  top: '50%'
  left: '50%'

target = document.getElementById('overlay');
spinner = new Spinner(spin_opts).spin(target)

app = angular.module 'astroApp', []

app.config(['$httpProvider', ($httpProvider)->
  $httpProvider.defaults.xsrfCookieName = 'csrftoken'
  $httpProvider.defaults.xsrfHeaderName = 'X-CSRFToken'
  $httpProvider.defaults.withCredentials = true
  $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded'
])

app.controller 'MasterController', ['$scope', '$http', ($scope, $http) ->
  $scope.initItems = 0

  $scope.$on 'initStarted', ->
    $scope.init = true
    $scope.initItems++

  $scope.$on 'initComplete', ->
    $scope.initItems--
    if $scope.initItems == 0
      $scope.init = false

  $scope.$on 'errorOccurred', (event, data)->
    $scope.show_error = true
    $scope.error_message = data

  $scope.login = ->
    $scope.init = true
    data =
      username: $scope.username
      password: $scope.password
    login_Promise = $http.post '/api/login', $.param data
    login_Promise.success((data)->
      $scope.init = false
      $scope.user = data.user
    )

  $scope.logout = ->
    $scope.init = true
    login_Promise = $http.post '/api/logout'
    login_Promise.success(->
      $scope.init = false
      $scope.user = null
    )

]

app.directive 'compareTo', ->
  require: "ngModel"
  scope: {
    otherModelValue: "=compareTo"
  },
  link: (scope, element, attributes, ngModel)->
    ngModel.$validators.compareTo = (modelValue)->
      return modelValue == scope.otherModelValue

    scope.$watch "otherModelValue", ->
      ngModel.$validate()

app.directive 'checkAvailibility', ['$http', ($http)->
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

window.app = app