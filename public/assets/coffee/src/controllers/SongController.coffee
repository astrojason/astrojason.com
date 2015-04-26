window.app.controller 'SongController', ['$scope', 'Song', '$controller', ($scope, Song, $controller)->

  $controller 'FormMasterController', $scope: $scope

  $scope.modal = '#addSongModal'

  $scope.$watch '[song.title, song.artist]', ->
    $scope.errorMessage = ''

  $scope.all = ->
    Song.query (response)->
      $scope.songs = response.songs

  $scope.save = ()->
    success = ->
      alertify.success 'Song ' + (if $scope.song.id then 'updated' else 'added') + ' successfully'

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
      if $scope.$parent.removeSong
        $scope.$parent.removeSong $scope.index

    error = (response)->
      $scope.errorMessage = response.data.error

    song_promise = Song.remove id: $scope.song.id
    song_promise.$promise.then success, error

  $scope.removeSong = (index)->
    $scope.songs.splice index, 1
]
