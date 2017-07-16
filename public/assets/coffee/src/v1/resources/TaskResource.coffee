angular.module('astroApp').factory 'TaskResource', [
  '$resource'
  '$http'
  '$uibModal'
  '$log'
($resource, $http, $uibModal, $log) ->
    subTaskParser = (task_dues) ->
      task_dues.map (task_due)->
        if task_due.task.tasks.length > 0
          task_due.task.tasks = subTaskParser(task_due.task.tasks)
        new TaskResource(task_due)

    arrayResponder =
      isArray: true
      transformResponse: (response)->
        wrappedResponse = angular.fromJson response
        if wrappedResponse.tasks
          wrappedResponse.tasks.$page_count = wrappedResponse.page_count
          wrappedResponse.tasks.$total = wrappedResponse.total

          wrappedResponse.tasks
        else
          wrappedResponse

      interceptor:
        response: (response)->
          response.resource.$page_count = response.data.$page_count
          response.resource

    resourceMethods =
      projects:
        method: 'GET'
        params:
          id: 'projects'
        isArray: true
        transformResponse: (response)->
          wrappedResponse = angular.fromJson response
          if wrappedResponse.projects
            wrappedResponse.projects
          else
            wrappedResponse

      query: angular.merge {}, arrayResponder,
        method: 'GET'

      save:
        method: 'POST'
        params:
          id: '@id'
          
      today:
        method: 'GET'
        params:
          id: 'today'
        transformResponse: (response)->
          wrappedResponse = angular.fromJson response
          if wrappedResponse.projects && wrappedResponse.tasks
            parsedResponse =
              projects: wrappedResponse.projects
              tasks: wrappedResponse.tasks
            parsedResponse.projects.map (project)->
              project.tasks = subTaskParser project.tasks
            parsedResponse.tasks = subTaskParser parsedResponse.tasks

            parsedResponse
          else
            wrappedResponse

    TaskResource = $resource '/api/task/:id', {}, resourceMethods

    TaskResource.prototype.edit = ->
      task = @
      $uibModal.open
        templateUrl: "modals/task-form"
        controller: "TaskFormController"
        resolve:
          task: ->
            task.task

    TaskResource.prototype.complete = ->
      task = @
      completePromise = $http.get "/api/task/#{task.id}/complete"

      completePromise.then ->
        task.completed = true

      completePromise

    TaskResource.prototype.skip = ->
      task = @
      skip = $http.get "/api/task/#{task.id}/skip"

      skip.then ->
        task.skipped = true

      skip

    TaskResource.prototype.postpone = ->
      today = (new moment()).format('YYYY-MM-DD')
      task = @
      postponePromise = $http.get "/api/task/#{task.id}/postpone"

      postponePromise.then ->
        angular.forEach task.recommended, (recommended)->
          if recommended.date == today
            recommended.postponed = true

      postponePromise

    TaskResource.prototype.warnDelete = ->
      task_due = @
      $uibModal.open
        templateUrl: '/modals/task-delete'
        controller: [
          '$scope'
          '$uibModalInstance'
          'task_due'
          ($scope, $uibModalInstance, task_due)->
            $scope.task_due = task_due
            $scope.cancel = ->
              $uibModalInstance.dismiss()
        ]
        resolve:
          task_due: ->
            task_due

    TaskResource.prototype.delete = ->
      task = @
      task.deleting = true
      deletePromise = $http.delete "/api/task/#{task.id}"

      deletePromise.then ->
        task.deleted = true

      deletePromise.finally ->
        task.deleting = false

    TaskResource.prototype.subTasksCompleted = ->
      @task.tasks.length > 0 && !(@task.tasks.filter((task) => !task.completed) > 0)

    TaskResource.prototype.hide = ->
      @completed || @skipped || @deleted || @subTasksCompleted()

    TaskResource
]
