describe 'MasterController unit tests', ->
  $scope = null
  MasterController = null
  mockUserService = null
  mockUserResource = null
  mockUserResourceLoginDeferred = null
  mockUserResourceLogoutDeferred = null
  mockUser =
    name: 'myTestUser'

  beforeEach ->
    module 'astroApp'
    inject ($rootScope, $controller, $q)->
      $scope = $rootScope.$new()

      mockUserService =
        set: ->

      mockUserResource =
        login: ->
          mockUserResourceLoginDeferred = $q.defer()
          $promise: mockUserResourceLoginDeferred.promise

        logout: ->
          mockUserResourceLogoutDeferred = $q.defer()
          $promise: mockUserResourceLogoutDeferred.promise

      mockInjections =
        $scope: $scope
        UserService: mockUserService
        UserResource: mockUserResource

      MasterController = $controller 'MasterController', mockInjections

  it 'should set the initial values of the variables', ->
    expect($scope.initItems).toEqual 0
    expect($scope.init).toEqual false
    expect($scope.show_error).toEqual false
    expect($scope.error_message).toEqual ''

  it 'should set $scope.init to true when initStarted is broadcast', ->
    $scope.$broadcast 'initStarted'
    $scope.$digest()
    expect($scope.init).toEqual true

  it 'should increment $scope.initItems when initStarted is broadcast', ->
    $scope.$broadcast 'initStarted'
    $scope.$digest()
    expect($scope.initItems).toEqual 1

  it 'should decrement $scope.initItems when initComplete is broadcast', ->
    $scope.initItems = 20
    $scope.$broadcast 'initComplete'
    $scope.$digest()
    expect($scope.initItems).toEqual 19

  it 'should set $scope.init to false when there are no more pending $scope.initItems', ->
    $scope.init = true
    $scope.initItems = 1
    $scope.$broadcast 'initComplete'
    $scope.$digest()
    expect($scope.init).toEqual false

  it 'should set show_error to true when errorOccurred is broadcast', ->
    $scope.$broadcast 'errorOccurred', 'This is a test error'
    $scope.$digest()
    expect($scope.show_error).toEqual true
    expect($scope.error_message).toEqual 'This is a test error'

  it 'should call UserResource.login when $scope.login is called', ->
    spyOn(mockUserResource, 'login').and.callThrough()
    $scope.login()
    expect(mockUserResource.login).toHaveBeenCalled()

  it 'should call UserResource.logout when $scope.logout is called', ->
    spyOn(mockUserResource, 'logout').and.callThrough()
    $scope.logout()
    expect(mockUserResource.logout).toHaveBeenCalled()

  it 'should set the user to the passed user', ->
    $scope.initUser(mockUser)
    expect($scope.user).toEqual mockUser

  it 'should pass the user to UserService', ->
    spyOn mockUserService, 'set'
    $scope.initUser(mockUser)
    expect(mockUserService.set).toHaveBeenCalledWith mockUser