/**
 * Created by jasonsylvester on 4/2/14.
 */
astroApp.controller('editMovieCtrl', function($scope, $http, movieSvc, $rootScope) {
  $scope.save = function() {
    if($scope.movies.length > 0) {
      $scope.movie = $scope.movies.pop();
      delete $scope.movie['id'];
      delete $scope.movie['user_id'];
      delete $scope.movie['created_at'];
      delete $scope.movie['updated_at'];
      $http({method: 'PUT', url: '/api/movie/', data: $scope.movie}).success(function(data){
        if(data.success) {
          $scope.save();
        } else {
          console.log($scope.movie.title, 'had a problem');
        }
      });
    } else {
      console.log('Migration complete');
    }
  };

  $scope.migrate = function() {
   $http.get('/js/data/movies.json').success(function(data){
     $scope.movies = data.movies;
     $scope.save();
   });
  }
});