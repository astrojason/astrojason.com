window.app.controller 'MovieController', ['$scope',  '$controller', '$timeout', '$filter', 'Movie', ($scope, $controller, $timeout, $filter, Movie)->

  $controller 'FormMasterController', $scope: $scope

  $scope.$on 'movieDeleted', (event, message)->
    $scope.movies = $filter('filter')($scope.movies, {id: '!' + message})
    $scope.movie_results = $filter('filter')($scope.movie_results, {id: '!' + message})

  $scope.$on 'dateChanged', (e, m)->
    if $scope.movie
      $scope.movie.date_watched = m

  $scope.$watch 'movie_query', (newValue)->
    $timeout.cancel $scope.search_timeout
    if newValue?.length >= 3
      $scope.search_timeout = $timeout ->
        $scope.search_movies()
      , 500

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

    error = (response)->
      $scope.errorMessage = response.data.error

    movie_promise = Movie.save $.param movie
    movie_promise.$promise.then success, error

  $scope.delete = ->
    success = ->
      alertify.success 'Movie deleted successfully'
      $scope.deleting = false
      $scope.editing = false
      $scope.$emit 'movieDeleted', $scope.movie.id

    error = (response)->
      $scope.errorMessage = response.data.error

    movie_promise = Movie.remove id: $scope.movie.id
    movie_promise.$promise.then success, error

  $scope.all = ->
    $scope.loading_movies = true
    Movie.query (response)->
      $scope.movies = response.movies
      $scope.loading_movies = false

  $scope.search_movies = ->
    $scope.searching_movies = true
    data =
      q: $scope.movie_query
    Movie.query data, (response)->
      $scope.movie_results = response.movies
      $scope.searching_movies = false

  $scope.checkEditing = ->
    return $scope.movie?.id
]
