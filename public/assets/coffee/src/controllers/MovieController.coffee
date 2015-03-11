window.app.controller 'MovieController', ['$scope', '$http', ($scope, $http)->

  if not $scope.movie?.id
    $scope.movie = new Movie()

  $scope.$on 'dateChanged', (e, m)->
    $scope.movie.date_watched = m

  $scope.getWidget = ->
    widget_promise = $http.get '/api/movies/widget'
    widget_promise.success (response) ->
      $scope.movies = response.movies

  $scope.changeRating = (movie, rating)->
    new_rating = movie.rating_order + rating
    current_movie = $scope.movies.filter (v)->
      return v.rating_order == new_rating
    current_movie[0].rating_order = movie.rating_order
    movie.rating_order = new_rating
    $scope.save(movie)

  $scope.save = (movie)->
    movie_promise = $http.post '/api/movies/save', $.param movie
    movie_promise.success (response)->
      if response.success
        if $scope.$parent.movieSaved
          $scope.$parent.movieSaved()
      else
        $scope.errorMessage = response.error

  $scope.all = ->
    movie_promise = $http.get '/api/movies'
    movie_promise.success (response)->
      if response.success
        $scope.movies = response.movies
]
