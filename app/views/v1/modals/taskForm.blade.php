<form
  role="form"
  name="task_form"
  ng-submit="save()"
  novalidate>
  <div class="modal-header">
    <h3 class="modal-title">Editing {{ task.name }}</h3>
  </div>
  <div class="modal-body">
    <div class="alert alert-danger" ng-show="error" ng-cloak>
      There was a problem saving the task. {{ error }}
    </div>
    <div class="form-group" ng-init="init()">
      <label for="project">Project</label>
      <input
        type="text"
        uib-typeahead="project as project.name for project in projects | filter:$viewValue | limitTo:8"
        ng-model="task.project"
        class="form-control"
        />
    </div>
    <div class="form-group">
      <label for="parent_task">Parent Task</label>
      <input
        type="text"
        uib-typeahead="parent_task as parent_task.name for parent_task in parent_tasks | filter:$viewValue | limitTo:8"
        ng-model="task.parent_task"
        class="form-control"
        />
    </div>
    <div class="form-group">
      <label for="name">Name</label>
      <input
        type="text"
        name="name"
        ng-model="task.name"
        class="form-control"
        placeholder="Name"
        required />
    </div>
    <div class="form-group">
      <label for="frequency">Frequency</label>
      <select
        class="form-control"
        ng-model="task.frequency"
        required>
        <option value="once">Once</option>
        <option value="daily">Daily</option>
        <option value="weekly">Weekly</option>
      </select>
    </div>
    <div class="form-group">
      <label for="due">Due</label>
      <p class="input-group">
        <input
          type="text"
          class="form-control"
          uib-datepicker-popup="{{ format }}"
          ng-model="task.due"
          is-open="dueDate.opened"
          datepicker-options="dateOptions"
          close-text="Close"
          alt-input-formats="altInputFormats" />
          <span class="input-group-btn">
            <button
              type="button"
              class="btn btn-default"
              ng-click="openDue()">
              <i class="glyphicon glyphicon-calendar"></i>
            </button>
          </span>
        </p>
    </div>
    <div class="form-group">
      <label for="subtasks_to_show">Subtasks to Show</label>
      <input
        type="number"
        name="subtasks_to_show"
        ng-model="task.subtasks_to_show"
        class="form-control" />
    </div>
    <div class="form-check form-check-inline">
      <label class="form-check-label">
        <input
          class="form-check-input"
          type="checkbox"
          ng-model="task.chained"
          id="chained"> Chained
      </label>
      <label class="form-check-label">
        <input
          class="form-check-input"
          type="checkbox"
          ng-model="task.cycle_subtasks"
          id="cycle_subtasks"> Cycle subtasks
      </label>
    </div>
  </div>
  <div class="modal-footer">
    <div class="form-group">
      <button class="btn btn-primary" ng-disabled="!task_form.$valid">Save</button>
      <a class="btn btn-default" ng-click="cancelEdit()">Cancel</a>
    </div>
  </div>
</form>
