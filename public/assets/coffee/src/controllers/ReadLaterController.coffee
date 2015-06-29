window.app.controller 'ReadLaterController', ['$scope', '$timeout', ($scope, $timeout)->

  $scope.newLink = null
  $scope.success = false
  $scope.editing = true
  $scope.error = false

  $scope.createLink = (userId, name, link)->
    $scope.newLink = new window.Link(userId)
    $scope.newLink.name = name
    $scope.newLink.link = link

  $scope.$on 'closeModal', ->
    console.log 'Closing modal'
    $timeout ->
      window.parent.postMessage 'closeWindow', '*'
    , 1000

  $scope.saveError = (message)->
    $scope.error = message
    $scope.editing = false
]