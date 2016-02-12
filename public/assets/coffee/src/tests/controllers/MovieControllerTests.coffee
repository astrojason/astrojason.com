describe 'MovieController unit tests', ->
  $scope = null
  $timeout = null
  Movie = null
  MovieController = null
  mockMovieResource = null
  mockAlertifyService = null
  mockMovieQueryDeferred = null
  mockMovieSaveDeferred = null
  mockMovieRemoveDeferred = null
  mockMovieQueryResponse = readJSON 'public/assets/coffee/src/tests/data/movies.json'
  mockMovie = null

  beforeEach ->
    module 'astroApp'
    inject ($rootScope, $controller, _$timeout_, $q, _Movie_)->
      $scope = $rootScope.$new()
      $timeout = _$timeout_
      Movie = _Movie_

      mockAlertifyService =
        success: ->

      mockMovieResource =
        save: ->
          mockMovieSaveDeferred = $q.defer()
          $promise: mockMovieSaveDeferred.promise
        query: ->
          mockMovieQueryDeferred = $q.defer()
          $promise: mockMovieQueryDeferred.promise
        remove: ->
          mockMovieRemoveDeferred = $q.defer()
          $promise: mockMovieRemoveDeferred.promise

      mockInjections =
        $scope: $scope
        MovieResource: mockMovieResource
        AlertifyService: mockAlertifyService

      MovieController = $controller 'MovieController', mockInjections

    mockMovie = angular.copy mockMovieQueryResponse.movies[0]

  it 'should set the default variables', ->
    expect($scope.loading_movies).toEqual false

  it 'should filter the movie out when movieDeleted is $emitted', ->
    $scope.movies = angular.copy mockMovieQueryResponse.movies
    $scope.movie_results = angular.copy mockMovieQueryResponse.movies
    expect($scope.movies[0]).toEqual mockMovie
    expect($scope.movie_results[0]).toEqual mockMovie
    $scope.$emit 'movieDeleted', mockMovie.id
    $scope.$digest()
    expect($scope.movies[0]).not.toEqual mockMovie
    expect($scope.movie_results[0]).not.toEqual mockMovie

  it 'should set movie.date_watched when the dateChanged event is $emitted', ->
    $scope.movie = mockMovie
    $scope.$emit 'dateChanged', '2012-01-23'
    $scope.$digest()
    expect($scope.movie.date_watched).toEqual '2012-01-23'

  it 'should call $scope.query when $scope.initList is called', ->
    spyOn $scope, 'query'
    $scope.initList()
    expect($scope.query).toHaveBeenCalled()

  it 'should not call $scope.query when movie_query changes if $scope.initList has not been called', ->
    spyOn $scope, 'query'
    $scope.movie_query = 'test'
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should call $scope.query when movie_query changes if $scope.initList has been called', ->
    spyOn $scope, 'query'
    $scope.initList()
    $scope.$digest()
    $scope.query.calls.reset()
    $scope.movie_query = 'changed'
    $scope.$digest()
    $timeout.flush()
    expect($scope.query).toHaveBeenCalled()

  it 'should not call $scope.query when the page changes if $scope.initList has not been called', ->
    spyOn $scope, 'query'
    $scope.page = 23
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should call $scope.query when the page changes if $scope.initList has been called', ->
    spyOn $scope, 'query'
    $scope.initList()
    $scope.$digest()
    $scope.query.calls.reset()
    $scope.page = 23
    $scope.$digest()
    expect($scope.query).toHaveBeenCalled()

  it 'should not call $scope.query when the page changes if $scope.initList has been called but $scope.loading_movies is true', ->
    spyOn $scope, 'query'
    $scope.initList()
    $scope.$digest()
    $scope.query.calls.reset()
    $scope.loading_movies = true
    $scope.page = 23
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should set $scope.loading_movies to true when $scope.query is called', ->
    $scope.query()
    expect($scope.loading_movies).toEqual true

  it 'should set $scope.loading_movies to false when MovieResource.query succeeds', ->
    $scope.query()
    mockMovieQueryDeferred.resolve mockMovieQueryResponse
    $scope.$digest()
    expect($scope.loading_movies).toEqual false

  it 'should set the movies to the passed values when MovieResource.query succeeds', ->
    $scope.query()
    mockMovieQueryDeferred.resolve angular.copy(mockMovieQueryResponse.movies)
    $scope.$digest()
    expect($scope.movies).toEqual mockMovieQueryResponse.movies

  it 'should call $scope.generatePages when MovieResource.query succeeds', ->
    spyOn $scope, 'generatePages'
    $scope.query()
    mockMovieQueryDeferred.resolve angular.copy(mockMovieQueryResponse.movies)
    $scope.$digest()
    expect($scope.generatePages).toHaveBeenCalled()

  it 'should set $scope.loading_movies to false when MovieResource.query fails', ->
    $scope.query()
    mockMovieQueryDeferred.reject()
    $scope.$digest()
    expect($scope.loading_movies).toEqual false

  it 'should set $scope.errorMessage to the default value when MovieResource.query fails and no message is passed', ->
    $scope.query()
    mockMovieQueryDeferred.reject()
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'There was a problem getting the movies.'

  it 'should set $scope.errorMessage to the passed value when MovieResource.query fails and a message is passed', ->
    $scope.query()
    mockMovieQueryDeferred.reject data: error: 'This is a passed message'
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'This is a passed message'

  it 'should not call $scope.generatePages when MovieResource.query fails', ->
    spyOn $scope, 'generatePages'
    $scope.query()
    mockMovieQueryDeferred.reject()
    $scope.$digest()
    expect($scope.generatePages).not.toHaveBeenCalled()

  it 'should toggle the is_watched value', ->
    $scope.movie = mockMovie
    $scope.movie.is_watched = false
    $scope.toggleWatched()
    expect($scope.movie.is_watched).toEqual true
    $scope.toggleWatched()
    expect($scope.movie.is_watched).toEqual false

  it 'should call $scope.save when $scope.toggleWatched is called', ->
    spyOn $scope, 'save'
    $scope.movie = mockMovie
    $scope.toggleWatched()
    expect($scope.save).toHaveBeenCalled()

  it 'should call MovieResource.save when $scope.save is called', ->
    spyOn(mockMovieResource, 'save').and.callThrough()
    $scope.movie = mockMovie
    $scope.save()
    expect(mockMovieResource.save).toHaveBeenCalled()

  it 'should call AlertifyService.success when MovieResource.save succeeds with the new movie message', ->
    spyOn mockAlertifyService, 'success'
    $scope.movie = new Movie()
    $scope.save()
    mockMovieSaveDeferred.resolve movie: mockMovie
    $scope.$digest()
    expect(mockAlertifyService.success).toHaveBeenCalledWith 'Movie added successfully'

  it 'should call $scope.$emit when MovieResource.save succeeds and it is a new movie', ->
    spyOn $scope, '$emit'
    $scope.movie = new Movie()
    $scope.save()
    mockMovieSaveDeferred.resolve movie: mockMovie
    $scope.$digest()
    expect($scope.$emit).toHaveBeenCalledWith 'closeModal', mockMovie

  it 'should call AlertifyService.success when MovieResource.save succeeds with the updated movie message', ->
    spyOn mockAlertifyService, 'success'
    $scope.movie = mockMovie
    $scope.save()
    mockMovieSaveDeferred.resolve movie: mockMovie
    $scope.$digest()
    expect(mockAlertifyService.success).toHaveBeenCalledWith 'Movie updated successfully'

  it 'should set $scope.editing to false when MovieResource.save succeeds and it is an update', ->
    $scope.editing = true
    $scope.movie = mockMovie
    $scope.save()
    mockMovieSaveDeferred.resolve movie: mockMovie
    $scope.$digest()
    expect($scope.editing).toEqual false

  it 'should set the error message to the default if none is passed from MovieResource.save failure', ->
    $scope.movie = mockMovie
    $scope.save()
    mockMovieSaveDeferred.reject()
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'There was a problem saving the movie.'

  it 'should set the error message to the passed error from MovieResource.save failure', ->
    $scope.movie = mockMovie
    $scope.save()
    mockMovieSaveDeferred.reject data: error: 'This is a passed message'
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'This is a passed message'

  it 'should call MovieResource.remove when $scope.delete is called', ->
    $scope.movie = mockMovie
    spyOn(mockMovieResource, 'remove').and.callThrough()
    $scope.delete()
    expect(mockMovieResource.remove).toHaveBeenCalled()

  it 'should call AlertifyService.success when MovieResource.remove succeeds', ->
    spyOn mockAlertifyService, 'success'
    $scope.movie = mockMovie
    $scope.delete()
    mockMovieRemoveDeferred.resolve()
    $scope.$digest()
    expect(mockAlertifyService.success).toHaveBeenCalledWith 'Movie deleted successfully'

  it 'should call set the deleting and editing values when MovieResource.remove succeeds', ->
    $scope.deleting = true
    $scope.editing = true
    $scope.movie = mockMovie
    $scope.delete()
    mockMovieRemoveDeferred.resolve()
    $scope.$digest()
    expect($scope.deleting).toEqual false
    expect($scope.editing).toEqual false

  it 'should $emit movieDeleted when a MovieResource.remove succeeds', ->
    spyOn $scope, '$emit'
    $scope.movie = mockMovie
    $scope.delete()
    mockMovieRemoveDeferred.resolve()
    $scope.$digest()
    expect($scope.$emit).toHaveBeenCalledWith 'movieDeleted', mockMovie.id

  it 'should return false if it is a new movie', ->
    expect($scope.checkEditing()).toEqual false

  it 'should return true if it is an existing movie', ->
    $scope.movie = mockMovie
    expect($scope.checkEditing()).toEqual true