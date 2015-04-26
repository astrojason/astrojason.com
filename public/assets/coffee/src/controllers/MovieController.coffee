window.app.controller 'MovieController', ['$scope', '$http', '$controller', ($scope, $http, $controller)->

  $controller 'FormMasterController', $scope: $scope

  $scope.modal = '#addMovieModal'

  $scope.$on 'dateChanged', (e, m)->
    $scope.movie.date_watched = m

  $scope.getWidget = ->
    widget_promise = $http.get '/api/movies/widget'
    widget_promise.success (response) ->
      $scope.movies = response.movies

  $scope.changeRating = (movie, rating)->
    new_rating = movie.rating_order + rating
    if $scope.movies
      console.log 'calling update display order in current scope'
      $scope.updateDisplayOrder(movie, new_rating)
    else
      if $scope.$parent.movies
        console.log 'calling update display order in parent'
        $scope.$parent.updateDisplayOrder movie, new_rating
    $scope.save(movie)

  $scope.updateDisplayOrder = (movie, new_rating)->
    console.log 'updating display order'
    current_movie = $scope.movies.filter (v)->
      return v.rating_order == new_rating
    current_movie[0].rating_order = movie.rating_order
    movie.rating_order = new_rating

  $scope.save = (movie)->
    movie_promise = $http.post '/api/movies/save', $.param movie
    movie_promise.success (response)->
      if response.success
        if $scope.$parent.movieSaved
          $scope.$parent.movieSaved()
      else
        $scope.errorMessage = response.error

  $scope.delete = ->
    movie_promise = $http.post '/api/movies/delete/' + $scope.movie.id
    movie_promise.success (response)->
      if response.success
        if $scope.$parent.deleteMovie
          $scope.$parent.deleteMovie($scope.movie)

  $scope.all = ->
    movie_promise = $http.get '/api/movies'
    movie_promise.success (response)->
      if response.success
        $scope.movies = response.movies

  $scope.deleteMovie = (movie)->
    current_index = $scope.movies.indexOf movie
    $scope.movies.splice current_index, 1
]
