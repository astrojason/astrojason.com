window.app.controller 'SongController', ['$scope', 'Song', ($scope, Song)->

  $scope.cancelEdit = ->
    if $scope.song.id
      $scope.editing = false
    else
      angular.element('#addSongModal').modal('hide')
      false

  $scope.all = ->
    Song.query (response)->
      $scope.songs = response.songs

  $scope.save = ()->
    song_promise = Song.save $.param $scope.song
    song_promise.$promise.then (response)->
      alertify.success "Song " + (if 0 == parseInt $scope.song.id then "added" else "updated") + " successfully"
    song_promise.$promise.catch (response)->
      $scope.errorMessage = response.error

  $scope.toggleLearned = ->
    $scope.song.learned = !$scope.song.learned
    $scope.save $scope.song

  $scope.delete = ->
    song_promise = Song.remove id: $scope.song.id
    song_promise.$promise.then ->
      $scope.deleting = false
      $scope.editing = false
      if $scope.$parent.removeSong
        $scope.$parent.removeSong $scope.index
    song_promise.$promise.catch (response)->
      $scope.errorMessage = response.error

  $scope.removeSong = (index)->
    $scope.songs.splice index, 1
]
