<div class="modal-header">
    <h3 class="modal-title" id="modal-title">Deleting {{ task_due.task.name }}</h3>
</div>
<div class="modal-body bg-danger" id="modal-body">
  <p class="text-white">You can either skip the task for today, or delete it permanently. This action cannot be undone.</p>
</div>
<div class="modal-footer">
    <button
      ng-show="task_due.task.frequency == 'daily'"
      class="btn btn-warning"
      type="button"
      ng-click="task_due.skip(); cancel()">
      Skip
    </button>
    <button
      ng-show="task_due.task.frequency != 'daily'"
      class="btn btn-warning"
      type="button"
      ng-click="task_due.postpone(); cancel()">
      Postpone
    </button>
    <button class="btn btn-danger" type="button" ng-click="task_due.delete(); cancel()">Delete</button>
    <button class="btn btn-default" type="button" ng-click="cancel()">Cancel</button>
</div>
