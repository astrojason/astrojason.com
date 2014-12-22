<div class="clearfix"
<div class="row" ng-show="errorMessage">
  <div class="alert alert-danger">@{{ errorMessage }}</div>
</div>
<div class="row" ng-show="!editing && link.name">
  <div class="col-lg-12">
    <div class="alert alert-danger" ng-show="deleting == true">
      <h4>Delete Link</h4>
      <p>Deleting the link cannot be undone.</p>
      <p>
        <a class="btn btn-danger" ng-click="delete()">Yes I want to delete</a>
        <a class="btn btn-default" ng-click="deleting = false">No, do not delete</a>
      </p>
    </div>
    <a ng-href="@{{ link.link }}" ng-click="linkOpened()" target="_blank">@{{ link.name }}</a>
    <div class="pull-right">
      <span class="glyphicon glyphicon-pencil tool" ng-click="editing = true"></span>
      <span class="glyphicon glyphicon-ok tool" ng-click="markAsRead()" ng-show="!(link.is_read | boolparse)"></span>
      <span class="glyphicon glyphicon-book tool" ng-click="markAsUnread()" ng-show="(link.is_read | boolparse)"></span>
      <span class="glyphicon glyphicon-remove tool" ng-click="deleting = true"></span>
    </div>
  </div>
</div>
<form role="form" name="link_form" ng-submit="save()" novalidate ng-show="editing">
  <div class="form-group">
    <label for="name">Name</label>
    <input type="text" name="name" ng-model="link.name" class="form-control" placeholder="Link Name" required>
  </div>
  <div class="form-group">
    <label for="url">URL</label>
    <input type="text" name="link" ng-model="link.link" class="form-control" placeholder="Link URL" required>
  </div>
  <div class="form-group">
    <label for="category">Category</label>
    <select name="category" ng-model="link.category" ng-init="setCategories({{ $categories }})" ng-options="category for category in categories" class="form-control"></select>
  </div>
  <div class="form-group" ng-show="link.category == 'New'">
    <input type="text" name="new_category" class="form-control" ng-model="new_category" placeholder="Category Name" ng-required="link.category == 'New'" />
  </div>
  <div class="form-group">
    <input type="checkbox" ng-model="link.is_read" /> Read
  </div>
  <div class="form-group">
    <button class="btn btn-primary" ng-disabled="!link_form.$valid">Save</button>
    <a class="btn btn-default" ng-click="editing = false">Cancel</a>
  </div>
  <div class="form-group" ng-show="error">
    <div class="alert alert-danger"></div>
  </div>
</form>