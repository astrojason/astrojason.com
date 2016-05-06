describe 'SongController unit tests', ->
  $scope = null
  $timeout = null
  Song = null
  SongController = null
  mockSongResource = null
  mockAlertifyService = null
  mockSongQueryDeferred = null
  mockSongSaveDeferred = null
  mockSongRemoveDeferred = null
  mockSongQueryResponse = readJSON 'public/assets/coffee/src/tests/data/songs.json'
  mockSong = null
  mockForm = """
    <form name="song_form">
      <input name="title" ng-model="song.title" />
    </form>
  """

  beforeEach ->
    module 'astroApp'
    inject ($rootScope, $controller, _$timeout_, $q, _Song_, $compile)->
      $scope = $rootScope.$new()
      $timeout = _$timeout_
      Song = _Song_

      mockAlertifyService =
        success: ->

      mockSongResource =
        save: ->
          mockSongSaveDeferred = $q.defer()
          $promise: mockSongSaveDeferred.promise
        query: ->
          mockSongQueryDeferred = $q.defer()
          $promise: mockSongQueryDeferred.promise
        remove: ->
          mockSongRemoveDeferred = $q.defer()
          $promise: mockSongRemoveDeferred.promise

      mockInjections =
        $scope: $scope
        SongResource: mockSongResource
        AlertifyService: mockAlertifyService

      SongController = $controller 'SongController', mockInjections

      mockSong = angular.copy mockSongQueryResponse.songs[0]

      element = angular.element mockForm
      linker = $compile element
      element = linker $scope

  it 'should set the default variables', ->
    expect($scope.loading_songs).toEqual false
    expect($scope.saving).toEqual false

  it 'should filter the song out when songDeleted is $emitted', ->
    $scope.songs = angular.copy mockSongQueryResponse.songs
    $scope.song_results = angular.copy mockSongQueryResponse.songs
    expect($scope.songs[0]).toEqual mockSong
    expect($scope.song_results[0]).toEqual mockSong
    $scope.$emit 'songDeleted', mockSong.id
    $scope.$digest()
    expect($scope.songs[0]).not.toEqual mockSong
    expect($scope.song_results[0]).not.toEqual mockSong

  it 'should call $scope.query when $scope.initList is called', ->
    spyOn $scope, 'query'
    $scope.initList()
    expect($scope.query).toHaveBeenCalled()

  it 'should not call $scope.query when song_query changes if $scope.initList has not been called', ->
    spyOn $scope, 'query'
    $scope.song_query = 'test'
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should call $scope.query when song_query changes if $scope.initList has been called', ->
    spyOn $scope, 'query'
    $scope.initList()
    $scope.$digest()
    $scope.query.calls.reset()
    $scope.song_query = 'changed'
    $scope.$digest()
    $timeout.flush()
    expect($scope.query).toHaveBeenCalled()

  it 'should not call $scope.query when include_learned changes if $scope.initList has not been called', ->
    spyOn $scope, 'query'
    $scope.include_learned = true
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should call $scope.query when include_learned changes if $scope.initList has been called', ->
    spyOn $scope, 'query'
    $scope.initList()
    $scope.$digest()
    $scope.query.calls.reset()
    $scope.include_learned = true
    $scope.$digest()
    expect($scope.query).toHaveBeenCalled()

  it 'should not call $scope.query when include_learned changes if $scope.initList has been called but $scope.loading_songs is true', ->
    spyOn $scope, 'query'
    $scope.initList()
    $scope.$digest()
    $scope.query.calls.reset()
    $scope.loading_songs = true
    $scope.include_learned = true
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

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

  it 'should not call $scope.query when the page changes if $scope.initList has been called but $scope.loading_songs is true', ->
    spyOn $scope, 'query'
    $scope.initList()
    $scope.$digest()
    $scope.query.calls.reset()
    $scope.loading_songs = true
    $scope.page = 23
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should set $scope.loading_songs to true when $scope.query is called', ->
    $scope.query()
    expect($scope.loading_songs).toEqual true

  it 'should set $scope.loading_songs to false when SongResource.query succeeds', ->
    $scope.query()
    mockSongQueryDeferred.resolve mockSongQueryResponse
    $scope.$digest()
    expect($scope.loading_songs).toEqual false

  it 'should set the songs to the passed values when SongResource.query succeeds', ->
    $scope.query()
    mockSongQueryDeferred.resolve angular.copy(mockSongQueryResponse.songs)
    $scope.$digest()
    expect($scope.songs).toEqual mockSongQueryResponse.songs

  it 'should call $scope.generatePages when SongResource.query succeeds', ->
    spyOn $scope, 'generatePages'
    $scope.query()
    mockSongQueryDeferred.resolve angular.copy(mockSongQueryResponse.songs)
    $scope.$digest()
    expect($scope.generatePages).toHaveBeenCalled()

  it 'should set $scope.loading_songs to false when SongResource.query fails', ->
    $scope.query()
    mockSongQueryDeferred.reject()
    $scope.$digest()
    expect($scope.loading_songs).toEqual false

  it 'should set $scope.errorMessage to the default value when SongResource.query fails and no message is passed', ->
    $scope.query()
    mockSongQueryDeferred.reject()
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'There was a problem getting the songs.'

  it 'should set $scope.errorMessage to the passed value when SongResource.query fails and a message is passed', ->
    $scope.query()
    mockSongQueryDeferred.reject data: error: 'This is a passed message'
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'This is a passed message'

  it 'should not call $scope.generatePages when SongResource.query fails', ->
    spyOn $scope, 'generatePages'
    $scope.query()
    mockSongQueryDeferred.reject()
    $scope.$digest()
    expect($scope.generatePages).not.toHaveBeenCalled()

  it 'should set $scope.saving to true when $scope.save is called', ->
    $scope.save()
    expect($scope.saving).toEqual true

  it 'should call SongResource.save when $scope.save is called', ->
    spyOn(mockSongResource, 'save').and.callThrough()
    $scope.save()
    expect(mockSongResource.save).toHaveBeenCalled()

  it 'should set $scope.saving to false when SongResource.save succeeds', ->
    $scope.song = mockSong
    $scope.save()
    mockSongSaveDeferred.resolve()
    $scope.$digest()
    expect($scope.saving).toEqual false

  it 'should call AlerifyService.success with the updated message when SongResource.save succeeds for an existing song', ->
    spyOn mockAlertifyService, 'success'
    $scope.song = mockSong
    $scope.save()
    mockSongSaveDeferred.resolve()
    $scope.$digest()
    expect(mockAlertifyService.success).toHaveBeenCalledWith 'Song updated successfully'

  it 'should call AlerifyService.success with the updated message when SongResource.save succeeds for an existing song', ->
    spyOn mockAlertifyService, 'success'
    newSong = angular.copy mockSong
    newSong.id = null
    $scope.song = newSong
    $scope.save()
    mockSongSaveDeferred.resolve song: angular.copy(mockSong)
    $scope.$digest()
    expect(mockAlertifyService.success).toHaveBeenCalledWith 'Song added successfully'

  it 'should set $scope.saving to false when SongResource.save fails', ->
    $scope.song = mockSong
    $scope.save()
    mockSongSaveDeferred.reject()
    $scope.$digest()
    expect($scope.saving).toEqual false

  it 'should set $scope.errorMessage to the default message if none is passed', ->
    $scope.song = mockSong
    $scope.save()
    mockSongSaveDeferred.reject()
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'There was a problem saving the song.'

  it 'should set $scope.errorMessage to the passed message if one exists', ->
    $scope.song = mockSong
    $scope.save()
    mockSongSaveDeferred.reject data: error: 'This is a passed message'
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'This is a passed message'

  it 'should call SongResource.remove when $scope.delete is called', ->
    $scope.song = mockSong
    spyOn(mockSongResource, 'remove').and.callThrough()
    $scope.delete()
    expect(mockSongResource.remove).toHaveBeenCalledWith id: mockSong.id

  it 'should call AlerifyService.success when SongResource.remove succeeds', ->
    $scope.song = mockSong
    spyOn mockAlertifyService, 'success'
    $scope.delete()
    mockSongRemoveDeferred.resolve()
    $scope.$digest()
    expect(mockAlertifyService.success).toHaveBeenCalledWith 'Song deleted successfully'

  it 'should set the deleting and editing values to false when SongResource.remove succeeds', ->
    $scope.deleting = true
    $scope.editing = true
    $scope.song = mockSong
    $scope.delete()
    mockSongRemoveDeferred.resolve()
    $scope.$digest()
    expect($scope.deleting).toEqual false
    expect($scope.editing).toEqual false

  it 'should $emit the songDeleted event when SongResource.remove succeeds', ->
    $scope.song = mockSong
    spyOn $scope, '$emit'
    $scope.delete()
    mockSongRemoveDeferred.resolve()
    $scope.$digest()
    expect($scope.$emit).toHaveBeenCalledWith 'songDeleted', mockSong.id

  it 'should not call AlerifyService.success when SongResource.remove fails', ->
    $scope.song = mockSong
    spyOn mockAlertifyService, 'success'
    $scope.delete()
    mockSongRemoveDeferred.reject()
    $scope.$digest()
    expect(mockAlertifyService.success).not.toHaveBeenCalled()

  it 'should not $emit the songDeleted event when SongResource.remove fails', ->
    $scope.song = mockSong
    spyOn $scope, '$emit'
    $scope.delete()
    mockSongRemoveDeferred.reject()
    $scope.$digest()
    expect($scope.$emit).not.toHaveBeenCalled()

  it 'should set $scope.errorMessage to the returned message when SongResource.remove fails and returns a message', ->
    $scope.song = mockSong
    $scope.delete()
    mockSongRemoveDeferred.reject data: error: 'This is a passed error'
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'This is a passed error'

  it 'should set $scope.errorMessage to the default message when SongResource.remove fails and does not return a message', ->
    $scope.song = mockSong
    $scope.delete()
    mockSongRemoveDeferred.reject()
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'There was a problem deleting the song.'

  it 'should set $scope.loading_songs to true when $scope.getRecommendation is called', ->
    $scope.getRecommendation()
    expect($scope.loading_songs).toEqual true

  it 'should call SongResource.query with the proper parameters when $scope.getRecommendation is called', ->
    spyOn(mockSongResource, 'query').and.callThrough()
    $scope.getRecommendation()
    expect(mockSongResource.query).toHaveBeenCalledWith
      randomize: true
      limit: 1

  it 'should set $scope.loading_songs to false when SongResource.query succeeds', ->
    $scope.getRecommendation()
    mockSongQueryDeferred.resolve angular.copy(mockSongQueryResponse)
    $scope.$digest()
    expect($scope.loading_songs).toEqual false

  it 'should set $scope.loading_songs to false when SongResource.query fails', ->
    $scope.getRecommendation()
    mockSongQueryDeferred.reject()
    $scope.$digest()
    expect($scope.loading_songs).toEqual false

  it 'should set $scope.errorMessage to the passed value when SongResource.query fails and does return an error', ->
    $scope.getRecommendation()
    mockSongQueryDeferred.reject data: error: 'This is a passed error'
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'This is a passed error'

  it 'should set $scope.errorMessage to the default value when SongResource.query fails and does not return an error', ->
    $scope.getRecommendation()
    mockSongQueryDeferred.reject()
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'There was a problem getting a song recommendation.'
