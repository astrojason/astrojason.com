/**
 * Created by jasonsylvester on 4/2/14.
 */
astroApp.controller('movieRaterController', ['$scope', '$http', function($scope, $http){
  $http.get('/api/movies/compare').success(function(data) {
    $scope.movies = data.movies;
  });
  $scope.rate = function(movie) {
    console.log(movie);
  }
}]);