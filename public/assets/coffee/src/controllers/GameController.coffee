window.app.controller 'GameController', ['$scope', '$http', ($scope, $http)->
  $scope.setPlatforms = (platforms)->
    $scope.platforms = platforms
]
