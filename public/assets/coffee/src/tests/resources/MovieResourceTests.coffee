describe 'MovieResource test', ->
  MovieResource = null
  $httpBackend = null

  beforeEach ->
    module 'astroApp'
    inject (_$httpBackend_, _MovieResource_)->
      $httpBackend = _$httpBackend_
      MovieResource = _MovieResource_

  it 'should call the default endpoint', ->
    $httpBackend.expectGET('/api/movie').respond 200
    MovieResource.query()
    $httpBackend.flush()