describe 'SongResource test', ->
  SongResource = null
  $httpBackend = null
  mockSongQueryResponse = readJSON 'public/assets/coffee/src/tests/data/songs.json'

  beforeEach ->
    module 'astroApp'
    inject (_$httpBackend_, _SongResource_)->
      $httpBackend = _$httpBackend_
      SongResource = _SongResource_

  it 'should call the default endpoint', ->
    $httpBackend.expectGET('/api/song').respond 200, mockSongQueryResponse
    SongResource.query()
    $httpBackend.flush()