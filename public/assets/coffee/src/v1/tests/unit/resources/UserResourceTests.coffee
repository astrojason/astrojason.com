describe 'UserResource test', ->
  UserResource = null
  $httpBackend = null

  beforeEach ->
    module 'astroApp'
    inject (_$httpBackend_, _UserResource_)->
      $httpBackend = _$httpBackend_
      UserResource = _UserResource_

  it 'should GET to the user endpoint', ->
    $httpBackend.expectGET('/api/user').respond 200
    UserResource.query()
    $httpBackend.flush()

  it 'should call PUT to the user endpoint', ->
    $httpBackend.expectPUT('/api/user').respond 200
    UserResource.add()
    $httpBackend.flush()

    it 'should call POST to the checkemail endpoint', ->
    $httpBackend.expectPOST('/api/user/checkemail').respond 200
    UserResource.checkemail()
    $httpBackend.flush()

  it 'should call POST to the checkusername endpoint', ->
    $httpBackend.expectPOST('/api/user/checkusername').respond 200
    UserResource.checkusername()
    $httpBackend.flush()

  it 'should POST to the login endpoint', ->
    $httpBackend.expectPOST('/api/user/login').respond 200, user: {}
    UserResource.login()
    $httpBackend.flush()

  it 'should POST to the logout endpoint', ->
    $httpBackend.expectPOST('/api/user/logout').respond 200
    UserResource.logout()
    $httpBackend.flush()

  it 'should call POST to the user endpoint', ->
    $httpBackend.expectPOST('/api/user/21').respond 200
    UserResource.save id: 21
    $httpBackend.flush()