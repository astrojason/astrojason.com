app.controller('nextGameController', ['$http', '$scope', 'gameSvc', function($http, $scope, gameSvc) {
  var next = this;

  next.getNextGame = function() {
    $http.get('/api/game/next').success(function(data) {
      next.game = data.game;
    });
  };

  next.getNextGame();

  next.edit = function(game) {
    gameSvc.edit(game);
  };

  next.played = function(game) {
    gameSvc.markAsPlayed(game);
  };

  $scope.$on('GAME_PLAYED', function(){
    next.getNextGame();
  });
}]);

app.controller('editGameController', ['$http', 'gameSvc', '$scope', function($http, gameSvc, $scope){
  var editor = this;
  editor.game = gameSvc.create();

  $scope.$on('EDITING_GAME', function(){
    editor.game = gameSvc.get();
  });

  editor.create = function() {
    gameSvc.edit(gameSvc.create());
  };

  editor.save = function() {
    $http({method: 'PUT', url: '/api/game/', data: editor.game}).success(function(data){
      if(data.success) {
        $('#gameModal').modal('hide');
      } else {
        console.log(editor.game.title, 'had a problem');
        console.log(data.message);
      }
    });
  }
}]);

app.controller('allGamesController', ['$http', 'gameSvc', function($http, gameSvc){
  var all = this;
  $http.get('/api/games').success(function(data) {
    all.games = data.games;
  });

  all.edit = function(game){
    gameSvc.edit(game);
  };

  all.markAsRead = function(game) {
    gameSvc.markAsPlayed(game);
  };
}]);