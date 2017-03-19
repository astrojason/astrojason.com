describe 'MovieResource test', ->
  MovieResource = null
  $httpBackend = null
  mockMovieQueryResponse = readJSON 'public/assets/coffee/src/v1/tests/data/movies.json'

  beforeEach ->
    module 'astroApp'
    inject (_$httpBackend_, _MovieResource_)->
      $httpBackend = _$httpBackend_
      MovieResource = _MovieResource_

  it 'should GET to the main endpoint', ->
    $httpBackend.expectGET('/api/movie').respond mockMovieQueryResponse
    MovieResource.query()
    $httpBackend.flush()

  it 'should GET to the specifc endpoint', ->
    $httpBackend.expectGET('/api/movie/1').respond mockMovieQueryResponse
    MovieResource.get(id: 1)
    $httpBackend.flush()

  it 'should POST to the specific endpoint', ->
    $httpBackend.expectPOST('/api/movie/1').respond 200
    MovieResource.save(id: 1)
    $httpBackend.flush()

  it 'should DELETE to the specific endpoint', ->
    $httpBackend.expectDELETE('/api/movie/1').respond 200
    MovieResource.remove(id: 1)
    $httpBackend.flush()

  it 'should get to the widget endpoint', ->
    $httpBackend.expectGET('/api/movie/widget').respond 200
    MovieResource.widget()
    $httpBackend.flush()

  it 'should POST to the specific endpoint when called as an attribute on a movie', ->
    myMovie = new MovieResource angular.copy(mockMovieQueryResponse.movies[0])
    $httpBackend.expectPOST("/api/movie/#{myMovie.id}").respond 200
    myMovie.$save()
    $httpBackend.flush()

  it 'should DELETE to the specific endpoint when called as an attribute on a movie', ->
    myMovie = new MovieResource angular.copy(mockMovieQueryResponse.movies[0])
    $httpBackend.expectDELETE("/api/movie/#{myMovie.id}").respond 200
    myMovie.$remove()
    $httpBackend.flush()

  it 'should GET to the widget endpoint', ->
    $httpBackend.expectGET('/api/movie/widget').respond mockMovieQueryResponse
    MovieResource.widget()
    $httpBackend.flush()