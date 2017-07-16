<table class="table table-condensed table-striped table-hover" ng-cloak>
  <tbody>
    <tr
      ng-repeat="project in projects">
      <td>
        <span class="task-header">
          {{ project.name }}
        </span>
        <task-table tasks="projects.tasks"></task-table>
      </td>
    </tr>
    <tr
      ng-repeat="task_due in tasks"
      ng-hide="task_due.hide()">
      <td>
        <span ng-class="{ 'task-header': task_due.task.tasks.length > 0 }">
          {{ task_due.task.name }}
        </span>
        <span ng-hide="task_due.task.tasks.length > 0">
          <small ng-class="{ 'text-danger': task_due.overdue }">
            {{ task_due.due | date }}
          </small>
        </span>
        <div class="pull-right" ng-hide="task_due.task.tasks.length > 0">
          <span
            class="glyphicon glyphicon-ok-circle text-success"
            ng-click="task_due.complete()">
          </span>
          <span
            class="glyphicon glyphicon-edit"
            ng-click="task_due.edit()">
          </span>
          <span
            class="glyphicon glyphicon-calendar text-info postpone-button"
            ng-click="task_due.postpone()">
          </span>
          <span
            class="glyphicon glyphicon-remove-circle text-danger"
            ng-click="task_due.warnDelete()">
          </span>
        </div>
        <div ng-show="task_due.task.tasks.length > 0" ng-cloak>
          <task-table tasks="task_due.task.tasks"></task-table>
        </div>
      </td>
    </tr>
  </tbody>
</table>
