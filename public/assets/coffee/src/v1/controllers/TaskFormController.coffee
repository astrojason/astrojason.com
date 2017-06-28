angular.module('astroApp').controller 'TaskFormController', [
  '$scope'
  '$log'
  'TaskResource'
  'task'
  ($scope,
    $log,
    TaskResource,
    task)->

    $scope.init = ->
      $scope.task = task

      projects_promise = TaskResource.projects().$promise

      projects_promise.then (projects)->
        $log.debug projects
        $scope.projects = projects
]
