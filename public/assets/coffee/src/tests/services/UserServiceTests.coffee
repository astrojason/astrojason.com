describe 'UserService Test', ->
  $rootScope = null
  $httpBackend = null
  UserService = null

  beforeEach ->
    module 'astroApp'
    inject (_$rootScope_, _$httpBackend_, _UserService_)->
      $rootScope = _$rootScope_
      $httpBackend = _$httpBackend_
      UserService = _UserService_

  it 'should set and get the user', ->
    UserService.set 'This is a test'
    expect(UserService.get()).toEqual 'This is a test'

  it 'should attempt to log the user in', ->
    $httpBackend.expectPOST('/api/login').respond user: 'This is my user'
    UserService.login()
    $httpBackend.flush()

  it 'should broadcast initStarted when login is called', ->
    spyOn $rootScope, '$broadcast'
    UserService.login()
    expect($rootScope.$broadcast).toHaveBeenCalledWith 'initStarted'

  it 'should broadcast initComplete when login is successful', ->
    spyOn $rootScope, '$broadcast'
    $httpBackend.expectPOST('/api/login').respond user: 'This is my user'
    UserService.login()
    $httpBackend.flush()
    expect($rootScope.$broadcast).toHaveBeenCalledWith 'initComplete'

  it 'should broadcast initComplete when login fails', ->
    spyOn $rootScope, '$broadcast'
    $httpBackend.expectPOST('/api/login').respond 500
    UserService.login()
    $httpBackend.flush()
    expect($rootScope.$broadcast).toHaveBeenCalledWith 'initComplete'

  it 'should broadcast errorOccurred when login fails', ->
    spyOn $rootScope, '$broadcast'
    $httpBackend.expectPOST('/api/login').respond 500
    UserService.login()
    $httpBackend.flush()
    expect($rootScope.$broadcast).toHaveBeenCalledWith 'errorOccurred', 'Problem logging in'

  it 'should attempt to log the user out', ->
    $httpBackend.expectPOST('/api/logout').respond 200
    UserService.logout()
    $httpBackend.flush()

  it 'should broadcast initStarted when logout is called', ->
    spyOn $rootScope, '$broadcast'
    UserService.logout()
    expect($rootScope.$broadcast).toHaveBeenCalledWith 'initStarted'

  it 'should broadcast initComplete when logout is successful', ->
    spyOn $rootScope, '$broadcast'
    $httpBackend.expectPOST('/api/logout').respond user: 'This is my user'
    UserService.logout()
    $httpBackend.flush()
    expect($rootScope.$broadcast).toHaveBeenCalledWith 'initComplete'

  it 'should broadcast initComplete when logout fails', ->
    spyOn $rootScope, '$broadcast'
    $httpBackend.expectPOST('/api/logout').respond 500
    UserService.logout()
    $httpBackend.flush()
    expect($rootScope.$broadcast).toHaveBeenCalledWith 'initComplete'

  it 'should broadcast errorOccurred when logout fails', ->
    spyOn $rootScope, '$broadcast'
    $httpBackend.expectPOST('/api/logout').respond 500
    UserService.logout()
    $httpBackend.flush()
    expect($rootScope.$broadcast).toHaveBeenCalledWith 'errorOccurred', 'Problem logging out'