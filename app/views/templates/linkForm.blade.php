<form role="form" name="link_form" ng-submit="save()" novalidate>
  <div class="form-group">
    <label for="name">Name</label>
    <input type="text" name="name" ng-model="link.name" class="form-control" placeholder="Link name" required>
    <div class="alert alert-danger" ng-show="link_form.name.$error.required">Link name is required</div>
  </div>
  <div class="form-group">
    <label for="url">URL</label>
    <input type="text" name="url" ng-model="link.link" class="form-control" placeholder="url" required>
    <div class="alert alert-danger" ng-show="link_form.link.$error.required">URL is required</div>
  </div>
  <div class="form-group">
    <label for="category">Category</label>
    <select name="category" ng-model="link.category" ng-init="categories = {{ $categories }}" ng-options="category for category in categories"></select>
    <input type="text" name="new_category" class="form-control" ng-model="new_category" placeholder="Category Name" ng-show="link.category == 'new'" ng-required="link.category == 'new'" />
    <div class="alert alert-danger" ng-show="link_form.new_category.$error.required">Category is required</div>
  </div>
  <div class="form-group">
    <input type="checkbox" ng-model="link.read" /> Read
  </div>
  <div class="form-group">
    <button class="btn btn-primary" ng-disabled="!link_form.$valid">Save</button>
    <button class="btn btn-default">Cancel</button>
  </div>
  <div class="form-group" ng-show="error">
    <div class="alert alert-danger"></div>
  </div>
</form>