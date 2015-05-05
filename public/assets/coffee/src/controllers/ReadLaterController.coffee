window.app.controller 'ReadLaterController', ['$scope', '$timeout', ($scope, $timeout)->

  $scope.newLink = null
  $scope.success = false
  $scope.editing = true
  $scope.error = false

  $scope.createLink = (userId, name, link)->
    $scope.newLink = new window.Link(userId)
    $scope.newLink.name = name
    $scope.newLink.link = link

  $scope.linkAdded = ->
    $scope.success = true
    $timeout(->
      $scope.closeWindow()
    , 1000)

  $scope.linkCancelled = ->
    $scope.closeWindow()

  $scope.closeWindow = ->
    $scope.newLink = null
    window.parent.postMessage 'closeWindow', '*'

  $scope.saveError = (message)->
    $scope.error = message
    $scope.editing = false
]