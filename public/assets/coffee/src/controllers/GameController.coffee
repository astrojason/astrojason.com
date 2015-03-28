window.app.controller 'GameController', ['$scope', '$http', '$controller', ($scope, $http, $controller)->

  $controller 'FormMasterController', $scope: $scope

  $scope.setPlatforms = (platforms)->
    $scope.platforms = platforms
]
