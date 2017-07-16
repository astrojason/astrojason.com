angular.module('astroApp').controller 'TaskFormController', [
  '$scope'
  '$log'
  '$uibModalInstance'
  'TaskResource'
  'task'
  ($scope,
    $log,
    $uibModalInstance,
    TaskResource,
    task)->

    $scope.formats = ['shortDate', 'dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy']
    $scope.format = $scope.formats[0]
    $scope.altInputFormats = ['M!/d!/yyyy']
    $scope.dueDate =
      opened: false

    $scope.init = ->
      $scope.task = angular.copy task

      projects_promise = TaskResource.projects().$promise

      projects_promise.then (projects)->
        $scope.projects = projects
        if $scope.task.project_id
          task_project = $scope.projects.filter (project)->
            project.id == $scope.task.project_id
          if task_project[0]
            $scope.task.project = task_project[0]

      parent_tasks_promise = TaskResource.query().$promise

      parent_tasks_promise.then (tasks)->
        $scope.parent_tasks = tasks
        if $scope.task.parent_task_id
          task_parent = $scope.parent_tasks.filter (parent_task)->
            parent_task.id == $scope.task.parent_task_id
          if task_parent[0]
            $scope.task.parent_task = task_parent[0]

    $scope.dateOptions =
      formatYear: 'yy'
      maxDate: new Date(2020, 5, 22)
      minDate: new Date()
      startingDay: 1

    $scope.openDue = ->
      $scope.dueDate.opened = true

    $scope.save = ->
      task_params = {}
      if $scope.task.parent_task
        $scope.task.parent_task_id = $scope.task.parent_task.id
      if $scope.task.project
        $scope.task.project_id = $scope.task.project.id
      if $scope.task.due
        $scope.task.due = $scope.task.due
      save_task = angular.extend $scope.task, task_params

      save_promise = TaskResource.save(save_task).$promise

      save_promise.catch (err)->
        if err.message
          $scope.error = err.message
        else if err.statusText
          $scope.error = "#{err.status}: #{err.statusText}"
        else
          $scope.error = "Something went wrong"


    $scope.cancelEdit = ->
      $uibModalInstance.dismiss()

]
