angular.module('astroApp').service 'UserService', ['$rootScope', '$http', ($rootScope, $http)->
  UserService =
    this_user: {}

    set: (user)->
      @.this_user = user

    get: ->
      @.this_user

    login: (data)->
      self = @
      $rootScope.$broadcast 'initStarted'
      login_Promise = $http.post '/api/login', data

      login_Promise.then (response)->
        self.set response.user
        $rootScope.$broadcast 'userLoggedIn'

      login_Promise.catch ->
        $rootScope.$broadcast 'errorOccurred', 'Problem logging in'

      login_Promise.finally ->
        $rootScope.$broadcast 'initComplete'

    logout: ->
      self = @
      $rootScope.$broadcast 'initStarted'
      login_Promise = $http.post '/api/logout'

      login_Promise.success ->
        self.set null
        $rootScope.$broadcast 'userLoggedOut'

      login_Promise.error ->
        $rootScope.$broadcast 'errorOccurred', 'Problem logging out'

      login_Promise.finally ->
        $rootScope.$broadcast 'initComplete'

  return UserService
]