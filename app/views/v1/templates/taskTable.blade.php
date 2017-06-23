<table class="table table-condensed table-striped table-hover" ng-cloak>
  <tbody>
    <tr
      ng-repeat="task in tasks"
      ng-hide="task.hide()">
      <td ng-class="{ 'task-header': task.subTasks.length > 0 }">
        {{ task.name }}
        <div ng-show="task.subTasks.length > 0" ng-cloak>
          <task-table tasks="task.subTasks"></task-table>
        </div>
      </td>
      <td>
        <span ng-hide="task.subTasks.length > 0">
          {{ task.time_remaining }} {{ task.overdue ? 'overdue' : 'remaining' }}
        </span>
      </td>
      <td>
        <div class="pull-right" ng-hide="task.subTasks.length > 0">
          <span
            class="glyphicon glyphicon-ok-circle text-success"
            ng-click="task.complete()">
          </span>
          <span
            class="glyphicon glyphicon-edit"
            ng-click="task.edit()">
          </span>
          <span
            class="glyphicon glyphicon-calendar text-info postpone-button"
            ng-click="task.postpone()">
          </span>
          <span
            class="glyphicon glyphicon-remove-circle text-danger"
            ng-click="task.warnDelete()">
          </span>
        </div>
      </td>
    </tr>
  </tbody>
</table>
