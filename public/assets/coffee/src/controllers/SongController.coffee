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

  $scope.save = (song)->
    Song.save $.param song


  $scope.toggleLearned = ->
    $scope.song.learned = !$scope.song.learned
    $scope.save $scope.song
]
