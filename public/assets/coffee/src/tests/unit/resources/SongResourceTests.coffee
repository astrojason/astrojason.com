describe 'SongResource test', ->
  SongResource = null
  $httpBackend = null
  mockSongQueryResponse = readJSON 'public/assets/coffee/src/tests/data/songs.json'

  beforeEach ->
    module 'astroApp'
    inject (_$httpBackend_, _SongResource_)->
      $httpBackend = _$httpBackend_
      SongResource = _SongResource_

  it 'should GET the default endpoint', ->
    $httpBackend.expectGET('/api/song').respond 200, mockSongQueryResponse
    SongResource.query()
    $httpBackend.flush()

  it 'should GET the specific endpoint', ->
    $httpBackend.expectGET('/api/song/1234').respond 200, mockSongQueryResponse
    SongResource.get id: 1234
    $httpBackend.flush()

  it 'should POST the default endpoint', ->
    $httpBackend.expectPOST('/api/song').respond 200, mockSongQueryResponse
    SongResource.save()
    $httpBackend.flush()

  it 'should POST the specific endpoint', ->
    $httpBackend.expectPOST('/api/song/1234').respond 200, mockSongQueryResponse
    SongResource.save id: 1234
    $httpBackend.flush()

  it 'should DELETE the specific endpoint', ->
    $httpBackend.expectDELETE('/api/song/1234').respond 200, mockSongQueryResponse
    SongResource.delete id: 1234
    $httpBackend.flush()

  it 'should GET the recommendation endpoint', ->
    $httpBackend.expectGET('/api/song/recommendation').respond 200, mockSongQueryResponse
    SongResource.recommend()
    $httpBackend.flush()