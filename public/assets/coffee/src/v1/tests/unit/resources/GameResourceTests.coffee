describe 'GameResource test', ->
  GameResource = null
  $httpBackend = null

  beforeEach ->
    module 'astroApp'
    inject (_$httpBackend_, _GameResource_)->
      $httpBackend = _$httpBackend_
      GameResource = _GameResource_

  it 'should POST to the default endpoint', ->
    $httpBackend.expectPOST('/api/game').respond 200
    GameResource.save()
    $httpBackend.flush()

  it 'should GET to the default endpoint', ->
    $httpBackend.expectGET('/api/game').respond 200
    GameResource.query()
    $httpBackend.flush()

  it 'should POST to the specific endpoint', ->
    $httpBackend.expectPOST('/api/game/1234').respond 200
    GameResource.save id: 1234
    $httpBackend.flush()

  it 'should GET to the specific endpoint', ->
    $httpBackend.expectGET('/api/game/1234').respond 200
    GameResource.get id: 1234
    $httpBackend.flush()

  it 'should DELETE to the specific endpoint', ->
    $httpBackend.expectDELETE('/api/game/1234').respond 200
    GameResource.delete id: 1234
    $httpBackend.flush()

  it 'should GET to the recommend endpoint', ->
    $httpBackend.expectGET('/api/game/recommendation').respond 200
    GameResource.recommend()
    $httpBackend.flush()