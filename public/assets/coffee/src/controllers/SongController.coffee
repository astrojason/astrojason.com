angular.module('astroApp').controller 'SongController', ['$scope', '$timeout', '$controller', '$filter', '$location',
  'Song', 'SongResource', 'AlertifyService', ($scope, $timeout, $controller, $filter, $location, Song, SongResource, AlertifyService)->

    $controller 'FormMasterController', $scope: $scope

    $scope.loading_songs = false
    $scope.saving = false

    $scope.$on 'songDeleted', (event, message)->
      $scope.songs = $filter('filter')($scope.songs, {id: '!' + message})
      $scope.song_results = $filter('filter')($scope.song_results, {id: '!' + message})

    $scope.triggerRecommender = ->
      $scope.$watch 'recommendingSong', (newValue)->
        if newValue
          $scope.getRecommendation()

    $scope.initList = ->
      $scope.query()

      $scope.$watch 'song_query', ->
        $timeout.cancel $scope.search_timeout
        if !$scope.loading_songs
          $scope.search_timeout = $timeout ->
            $scope.query()
          , 500

      $scope.$watch 'include_learned', ->
        if !$scope.loading_songs
          $scope.query()

      $scope.$watch 'page', (newValue, oldValue)->
        if !$scope.loading_songs
          if newValue != oldValue
            cur_opts = $location.search()
            cur_opts.page = newValue
            $location.search(cur_opts)
            $scope.query()

      $scope.$watch 'sort', ->
        if !$scope.loading_songs
          $scope.query()

      $scope.$watch 'descending', ->
        if !$scope.loading_songs
          $scope.query()

      $scope.$watch 'display_artist', ->
        if !$scope.loading_songs
          $scope.query()

      $scope.$on 'closeModal', (event, song)->
        $scope.songModalOpen = false
        if song
          song.new = true
          $scope.songs.splice(0, 0, song)
          $timeout ->
            song.new = false
          , 1000

    $scope.query = ->
      $scope.loading_songs = true

      data =
        limit: $scope.limit
        page: $scope.page
        include_learned: $scope.include_learned

      if $scope.song_query
        data['q'] = $scope.song_query

      if $scope.sort
        data['sort'] = $scope.sort

      if $scope.descending
        data['descending'] = true

      if $scope.display_artist
        data['artist'] = $scope.display_artist

      song_promise = SongResource.query(data).$promise

      song_promise.then (songs)->
        $scope.songs = songs
        $scope.total = songs.$total
        $scope.pages = songs.$pages
        $scope.generatePages()

      song_promise.catch (response)->
        $scope.errorMessage = response?.data.error || 'There was a problem getting the songs.'

      song_promise.finally ->
        $scope.loading_songs = false

    $scope.save = ()->
      $scope.saving = true
      song_promise = SongResource.save($scope.song).$promise

      song_promise.then (response)->
        AlertifyService.success 'Song ' + (if $scope.song.id then 'updated' else 'added') + ' successfully'
        if $scope.song.id
          $scope.editing = false
        else
          delete $scope.errorMessage
          $scope.song = new Song()
          $scope.song_form.$setPristine()
          $scope.$emit 'closeModal', response.song

      song_promise.catch (response)->
        $scope.errorMessage = response?.data.error || 'There was a problem saving the song.'

      song_promise.finally ->
        $scope.saving = false

    $scope.toggleLearned = ->
      $scope.song.learned = !$scope.song.learned
      $scope.save()

    $scope.delete = ->
      song_promise = SongResource.remove(id: $scope.song.id).$promise

      song_promise.then ->
        AlertifyService.success 'Song deleted successfully'
        $scope.deleting = false
        $scope.editing = false
        $scope.$emit 'songDeleted', $scope.song.id

      song_promise.catch (response)->
        $scope.errorMessage = response?.data.error || 'There was a problem deleting the song.'

    $scope.getRecommendation = ->
      $scope.loading_songs = true

      data =
        randomize: true
        limit: 1
      song_promise = SongResource.query(data).$promise

      song_promise.then (response)->
        $scope.song = response[0]

      song_promise.catch (response)->
        $scope.errorMessage = response?.data.error || 'There was a problem getting a song recommendation.'

      song_promise.finally ->
        $scope.loading_songs = false

    $scope.setArtists = (artists)->
      $scope.artists = artists

    $scope.populateSongs = ->
      $scope.loading_songs = true

      song_promise = SongResource.populate().$promise

      song_promise.then (songs)->
        $scope.songs = songs
        $scope.total = songs.$total
        $scope.pages = songs.$pages
        $scope.generatePages()

      song_promise.catch (response)->
        $scope.errorMessage = response?.data.error || 'There was a problem getting the songs.'

      song_promise.finally ->
        $scope.loading_songs = false
]