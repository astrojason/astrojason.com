angular.module('astroApp').controller 'ReadLaterController', ['$scope', '$timeout', '$window', 'Link',
  ($scope, $timeout, $window, Link)->

      $scope.newLink = null
      $scope.success = false
      $scope.editing = true
      $scope.error = false

      $scope.createLink = (userId, name, link)->
        $scope.newLink = new Link userId
        $scope.newLink.name = name
        $scope.newLink.link = link

      $scope.$on 'closeModal', ->
        $timeout ->
          $window.parent.postMessage 'closeWindow', '*'
        , 1000

      $scope.saveError = (message)->
        $scope.error = message
        $scope.editing = false
]