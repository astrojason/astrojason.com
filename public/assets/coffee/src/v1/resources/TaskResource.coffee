angular.module('astroApp').factory 'TaskResource', [
  '$resource'
  '$http'
  '$uibModal'
  '$log'
($resource, $http, $uibModal, $log) ->
    subTaskParser = (subTasks) ->
      subTasks.map (task)->
        if task.subTasks.length > 0
          task.subTasks = subTaskParser(task.subTasks)
        new TaskResource(task)

    arrayResponder =
      isArray: true
      transformResponse: (response)->
        wrappedResponse = angular.fromJson response
        if wrappedResponse.tasks
          angular.forEach wrappedResponse.tasks, (task) ->
            if task.subTasks.length > 0
              task.subTasks = subTaskParser(task.subTasks)
          # wrappedResponse.tasks.$page_count = wrappedResponse.page_count
          # wrappedResponse.tasks.$total = wrappedResponse.$total

          wrappedResponse.tasks
        else
          wrappedResponse

      interceptor:
        response: (response)->
          response.resource.$page_count = response.data.$page_count
          response.resource

    resourceMethods =
      daily: angular.merge {}, arrayResponder,
        method: 'GET'
        params:
          id: 'daily'

    TaskResource = $resource '/api/task/:id', {}, resourceMethods

    TaskResource.prototype.edit = ->
      task = @
      $uibModal.open
        templateUrl: "modals/task-form"
        controller: "TaskFormController"
        resolve:
          task: ->
            task

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
      task = @
      $uibModal.open
        template: """
          <div class="modal-header">
              <h3 class="modal-title" id="modal-title">Deleting {{ task.title }}</h3>
          </div>
          <div class="modal-body bg-danger" id="modal-body">
            <p class="text-white">You can either skip the task for today, or delete it permanently. This action cannot be undone.</p>
          </div>
          <div class="modal-footer">
              <button class="btn btn-warning" type="button" ng-click="task.skip(); cancel()">Skip</button>
              <button class="btn btn-danger" type="button" ng-click="task.delete(); cancel()">Delete</button>
              <button class="btn btn-default" type="button" ng-click="cancel()">Cancel</button>
          </div>
        """
        controller: [
          '$scope'
          '$uibModalInstance'
          'task'
          ($scope, $uibModalInstance, task)->
            $scope.task = task
            $scope.cancel = ->
              $uibModalInstance.dismiss()
        ]
        resolve:
          task: ->
            task

    TaskResource.prototype.delete = ->
      task = @
      task.deleting = true
      deletePromise = $http.delete "/api/task/#{task.id}"

      deletePromise.then ->
        task.deleted = true

      deletePromise.finally ->
        task.deleting = false

    TaskResource.prototype.subTasksCompleted = ->
      @hasSubTasks && !(@subTasks.filter((item) => !item.completed) > 0)

    TaskResource.prototype.hide = ->
      @completed || @skipped || @deleted || @subTasksCompleted()

    TaskResource
]
