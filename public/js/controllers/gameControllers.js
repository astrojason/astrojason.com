/**
 * Created by jasonsylvester on 4/23/14.
 */
astroApp.controller('nextGameCtrl', function($scope, $http, $rootScope, gameSvc) {
  $scope.getNextGame = function() {
    $http.get('/api/game/next').success(function(data) {
      $scope.game = data.game;
    });
  };

  $scope.getNextGame();

  $scope.edit = function(game) {
    gameSvc.set(game);
    $rootScope.$broadcast('EDITING_GAME', 'existing');
  };

  $scope.played = function(game) {
    gameSvc.set(game);
    $rootScope.$broadcast('PLAYED_GAME', 'existing');
  };

  $scope.$on('GAME_PLAYED', function(response) {
    $scope.getNextBook();
  });
});

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

  $scope.new = function() {
    $scope.title = '';
    $scope.platform = '';
  };
});