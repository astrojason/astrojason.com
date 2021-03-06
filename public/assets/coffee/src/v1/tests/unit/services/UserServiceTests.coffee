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