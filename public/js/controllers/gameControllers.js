/**
 * Created by jasonsylvester on 4/23/14.
 */
astroApp.controller('editGameCtrl', function($scope, $http, gameSvc, $rootScope) {
  $scope.save = function() {
    $http({method: 'PUT', url: '/api/game/', data: $scope.game}).success(function(data){
      if(data.success) {
        $('#gameModal').modal('hide');
      } else {
        console.log($scope.game.title, 'had a problem');
        console.log(data.message === 'movie exists');
      }
    });
  };
});