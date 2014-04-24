/**
 * Created by jasonsylvester on 4/2/14.
 */
astroApp.controller('editMovieCtrl', function($scope, $http, movieSvc, $rootScope) {
  $scope.save = function() {
    $http({method: 'PUT', url: '/api/movie/', data: $scope.movie}).success(function(data){
      if(data.success) {
        $('#movieModal').modal('hide');
      } else {
        console.log($scope.movie.title, 'had a problem');
        console.log(data.message === 'movie exists');
      }
    });
  };
});