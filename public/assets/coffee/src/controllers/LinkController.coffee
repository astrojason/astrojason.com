window.app.controller 'LinkController', ['$scope', '$http', ($scope, $http)->
  $scope.save = ->
    console.log 'Save triggered'
]
