describe 'SongResource test', ->
  SongResource = null
  $httpBackend = null

  beforeEach ->
    module 'astroApp'
    inject (_$httpBackend_, _SongResource_)->
      $httpBackend = _$httpBackend_
      SongResource = _SongResource_

  it 'should call the default endpoint', ->
    $httpBackend.expectGET('/api/song').respond 200
    SongResource.query()
    $httpBackend.flush()