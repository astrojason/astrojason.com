window.app.controller 'SongController', ['$scope', '$timeout', '$controller', '$filter', 'Song', ($scope, $timeout, $controller, $filter, Song)->

  $controller 'FormMasterController', $scope: $scope

  $scope.$on 'songDeleted', (event, message)->
    $scope.songs = $filter('filter')($scope.songs, {id: '!' + message})
    $scope.song_results = $filter('filter')($scope.song_results, {id: '!' + message})

  $scope.$watch '[song.title, song.artist]', ->
    $scope.errorMessage = ''

  $scope.$watch 'song_query', (newValue)->
    $timeout.cancel $scope.search_timeout
    if newValue?.length >= 3
      $scope.search_timeout = $timeout ->
        $scope.search_songs()
      , 500

  $scope.all = ->
    $scope.loading_songs = true
    Song.query (response)->
      $scope.songs = response.songs
      $scope.loading_songs = false

  $scope.save = ()->
    success = ->
      alertify.success 'Song ' + (if $scope.song.id then 'updated' else 'added') + ' successfully'
      if $scope.song.id
        $scope.editing = false
      else
        $scope.$emit 'closeModal'

    error = (response)->
      $scope.errorMessage = response.data.error

    song_promise = Song.save $.param $scope.song
    song_promise.$promise.then success, error

  $scope.toggleLearned = ->
    $scope.song.learned = !$scope.song.learned
    $scope.save()

  $scope.delete = ->
    success = ->
      alertify.success 'Song deleted successfully'
      $scope.deleting = false
      $scope.editing = false
      $scope.$emit 'songDeleted', $scope.song.id

    error = (response)->
      $scope.errorMessage = response.data.error

    song_promise = Song.remove id: $scope.song.id
    song_promise.$promise.then success, error

  $scope.search_songs = ->
    $scope.searching_songs = true
    data =
      q: $scope.song_query
    Song.query data, (response)->
      $scope.song_results = response.songs
      $scope.searching_songs = false
]
