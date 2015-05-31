window.app.controller 'MovieController', ['$scope',  '$controller', '$timeout', '$filter', 'Movie', ($scope, $controller, $timeout, $filter, Movie)->

  $controller 'FormMasterController', $scope: $scope

  $scope.$on 'movieDeleted', (event, message)->
    $scope.movies = $filter('filter')($scope.movies, {id: '!' + message})
    $scope.movie_results = $filter('filter')($scope.movie_results, {id: '!' + message})

  $scope.$on 'dateChanged', (e, m)->
    if $scope.movie
      $scope.movie.date_watched = m

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

  $scope.initList = ->

    $scope.query()

    $scope.$watch 'movie_query', ->
      $timeout.cancel $scope.search_timeout
      if !$scope.loading_movies
        $scope.search_timeout = $timeout ->
          $scope.query()
        , 500

  $scope.query = ->
    $scope.loading_movies = true
    data = []
    if $scope.movie_query
      data['q'] = $scope.movie_query
    Movie.query data, (response)->
      $scope.movies = response.movies
      $scope.loading_movies = false

  $scope.checkEditing = ->
    return $scope.movie?.id
]
