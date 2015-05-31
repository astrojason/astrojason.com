window.app.controller 'SongController', ['$scope', '$timeout', '$controller', '$filter', 'Song', ($scope, $timeout, $controller, $filter, Song)->

  $controller 'FormMasterController', $scope: $scope

  $scope.$on 'songDeleted', (event, message)->
    $scope.songs = $filter('filter')($scope.songs, {id: '!' + message})
    $scope.song_results = $filter('filter')($scope.song_results, {id: '!' + message})

  $scope.$watch '[song.title, song.artist]', ->
    $scope.errorMessage = ''

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

  $scope.query = ->
    $scope.loading_songs = true
    data = []
    if $scope.song_query
      data['q'] = $scope.song_query
    Song.query data, (response)->
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

  $scope.getRecommendation = ->
    data =
      randomize: true
      limit: 1
    Song.query data, (response)->
      $scope.song = response.songs[0]
      $scope.loading_songs = false

]
