angular.module('astroApp').controller 'MovieController', ['$scope',  '$controller', '$timeout', '$filter', '$location',
  'MovieResource', ($scope, $controller, $timeout, $filter, $location, MovieResource)->

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
      success = (response)->
        alertify.success "Movie " + (if movie.id then "updated" else "added") + " successfully"
        if $scope.movie.id
          $scope.editing = false
        else
          $scope.$emit 'closeModal', response.movie

      error = (response)->
        $scope.errorMessage = response.data.error

      movie_promise = MovieResource.save $.param movie
      movie_promise.$promise.then success, error

    $scope.delete = ->
      success = ->
        alertify.success 'Movie deleted successfully'
        $scope.deleting = false
        $scope.editing = false
        $scope.$emit 'movieDeleted', $scope.movie.id

      error = (response)->
        $scope.errorMessage = response.data.error

      movie_promise = MovieResource.remove id: $scope.movie.id
      movie_promise.$promise.then success, error

    $scope.initList = ->

      $scope.query()

      $scope.$watch 'movie_query', ->
        $timeout.cancel $scope.search_timeout
        if !$scope.loading_movies
          $scope.search_timeout = $timeout ->
            $scope.query()
          , 500

      $scope.$watch 'page', (newValue, oldValue)->
        if !$scope.loading_movies
          if newValue != oldValue
            cur_opts = $location.search()
            cur_opts.page = newValue
            $location.search(cur_opts)
            $scope.query()

      $scope.$on 'closeModal', (event, movie)->
        $scope.movieModalOpen = false
        if movie
          movie.new = true
          $scope.movies.splice(0, 0, movie)
          $timeout ->
            movie.new = false
          , 1000

    $scope.query = ->
      $scope.loading_movies = true
      data =
        limit: $scope.limit
        page: $scope.page
      if $scope.movie_query
        data['q'] = $scope.movie_query
      MovieResource.query data, (response)->
        $scope.loading_movies = false
        $scope.movies = response.movies
        $scope.total = response.total
        $scope.pages = response.pages
        $scope.generatePages()

    $scope.checkEditing = ->
      return $scope.movie?.id
]