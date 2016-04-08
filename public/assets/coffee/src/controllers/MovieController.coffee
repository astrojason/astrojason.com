angular.module('astroApp').controller 'MovieController', ['$scope',  '$controller', '$timeout', '$filter', '$location',
  'MovieResource', 'AlertifyService', ($scope, $controller, $timeout, $filter, $location, MovieResource, AlertifyService)->

    $controller 'FormMasterController', $scope: $scope

    $scope.loading_movies = false

    if !$scope.movie?.id?
      $scope.editing = true

    $scope.$on 'movieDeleted', (event, message)->
      $scope.movies = $filter('filter')($scope.movies, {id: '!' + message})
      $scope.movie_results = $filter('filter')($scope.movie_results, {id: '!' + message})

    $scope.$on 'dateChanged', (e, m)->
      if $scope.movie
        $scope.movie.date_watched = m

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

      moviePromise = MovieResource.query(data).$promise

      moviePromise.then (movies)->
        $scope.movies = movies
        $scope.total = movies.$total
        $scope.pages = movies.$pages
        $scope.generatePages()

      moviePromise.catch (response)->
        $scope.errorMessage = response?.data.error || 'There was a problem getting the movies.'

      moviePromise.finally ->
        $scope.loading_movies = false

    $scope.toggleWatched = ->
      $scope.movie.is_watched = !$scope.movie.is_watched
      $scope.save()

    $scope.save = ->
      movie_promise = MovieResource.save($scope.movie).$promise

      movie_promise.then (response)->
        AlertifyService.success "Movie " + (if $scope.movie.id then "updated" else "added") + " successfully"
        if $scope.movie.id
          $scope.editing = false
        else
          $scope.$emit 'closeModal', response.movie

      movie_promise.catch (response)->
        $scope.errorMessage = response?.data.error || 'There was a problem saving the movie.'

    $scope.delete = ->
      movie_promise = MovieResource.remove($scope.movie).$promise

      movie_promise.then ->
        AlertifyService.success 'Movie deleted successfully'
        $scope.deleting = false
        $scope.editing = false
        $scope.$emit 'movieDeleted', $scope.movie.id

      movie_promise.catch ->
        $scope.errorMessage = response?.data.error || 'There was a problem deleting the movie.'

    $scope.checkEditing = ->
      return if $scope.movie?.id then true else false
]