describe 'UserService Test', ->
  UserService = null
  $httpBackend = null

  beforeEach ->
    module 'astroApp'
    inject (_$httpBackend_, _UserService_)->
      $httpBackend = _$httpBackend_
      UserService = _UserService_

  it 'should set and get the user', ->
    UserService.set 'This is a test'
    expect(UserService.get()).toEqual 'This is a test'