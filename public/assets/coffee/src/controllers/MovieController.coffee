window.app.controller 'MovieController', ['$scope',  '$controller', 'Movie', ($scope, $controller, Movie)->

  $controller 'FormMasterController', $scope: $scope

  $scope.$on 'dateChanged', (e, m)->
    if $scope.movie
      $scope.movie.date_watched = m
      console.log $scope.movie

  $scope.toggleWatched = ->
    $scope.movie.is_watched = !$scope.movie.is_watched
    $scope.save $scope.movie

  $scope.save = (movie)->
    success = ->
      alertify.success "Movie " + (if movie.id then "updated" else "added") + " successfully"
      if $scope.movie.id
        $scope.editing = false
      else
        $scope.$emit 'closeModal'

    error = ->
      $scope.errorMessage = response.data.error

    movie_promise = Movie.save $.param movie
    movie_promise.$promise.then success, error

  $scope.delete = ->
    success = ->
      alertify.success 'Movie deleted successfully'
      $scope.deleting = false
      $scope.editing = false
      if $scope.$parent.removeMovie
        $scope.$parent.removeMovie $scope.index

    error = (response)->
      $scope.errorMessage = response.data.error

    movie_promise = Movie.remove id: $scope.movie.id
    movie_promise.$promise.then success, error

  $scope.all = ->
    Movie.query (response)->
      $scope.movies = response.movies

  $scope.removeMovie = (index)->
    $scope.movies.splice index, 1

  $scope.checkEditing = ->
    return $scope.movie?.id
]
