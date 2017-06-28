<div class="modal-header">
  <h3 class="modal-title">Editing {{ task.name }}</h3>
</div>
<div class="modal-body">
  <form
    role="form"
    name="task_form"
    novalidate>
    <div class="form-group" ng-init="init()">
      <label for="project">Project</label>
      <select
        class="form-control"
        ng-model="task.project"
        ng-options="project.name for project in projects">
      </select>
    </div>
    <div class="form-group">
      <label for="name">Name</label>
      <input
        type="text"
        name="name"
        ng-model="task.name"
        class="form-control"
        placeholder="Name"
        required/>
    </div>
  </form>
</div>
