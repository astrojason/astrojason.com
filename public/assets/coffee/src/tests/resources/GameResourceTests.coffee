describe 'GameResource test', ->
  GameResource = null
  $httpBackend = null

  beforeEach ->
    module 'astroApp'
    inject (_$httpBackend_, _GameResource_)->
      $httpBackend = _$httpBackend_
      GameResource = _GameResource_

  it 'should call the default endpoint', ->
    $httpBackend.expectGET('/api/game').respond 200
    GameResource.query()
    $httpBackend.flush()