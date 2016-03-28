<div class="row" ng-show="errorMessage != ''">
  <div class="alert alert-danger">{{ errorMessage }}</div>
</div>
<div class="row" ng-show="!editing">
  <div class="col-md-12">
    <div class="row alert alert-danger" ng-show="deleting == true">
      <div class="col-md-12">
        <h4>Delete "{{ link.name }}"?</h4>
        <p>Deleting the link cannot be undone.</p>
        <p>
          <a class="btn btn-danger" ng-click="delete()">Yes I want to delete</a>
          <a class="btn btn-default" ng-click="deleting = false">No, do not delete</a>
        </p>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-8" ng-class="showCategory ? 'col-md-6' : 'col-md-10'">
        <a ng-href="{{ link.link }}" ng-click="linkOpened()" target="_blank">{{ link.name }}</a>
      </div>
      <div class="col-md-2 hidden-xs" ng-show="showCategory">
        {{ link.category }}
      </div>
      <div class="col-md-2 hidden-xs" ng-show="showCategory">
        {{ link.times_loaded }}
      </div>
      <div class="col-md-2 hidden-xs">
        <div class="pull-right">
          <span class="glyphicon glyphicon-pencil tool" ng-click="editing = true"></span>
          <span class="glyphicon tool" ng-click="toggleRead()" ng-class="(link.is_read | boolparse) ? 'glyphicon-book' : 'glyphicon-ok'"></span>
          <span class="glyphicon glyphicon-remove tool" ng-click="deleting = true"></span>
        </div>
      </div>
      <div class="col-xs-2 hidden-lg hidden-md hidden-sm"><span class="glyphicon tool pull-right" ng-click="toggleRead()" ng-class="(link.is_read | boolparse) ? 'glyphicon-book' : 'glyphicon-ok'"></span></div>
      <div class="col-xs-2 hidden-lg hidden-md hidden-sm"><span class="glyphicon glyphicon-remove tool pull-right" ng-click="deleting = true"></span></div>
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
    <select name="category" ng-model="link.category" ng-init="setCategories(<%% $categories %%>)" ng-options="category for category in categories" class="form-control"></select>
  </div>
  <div class="form-group" ng-show="link.category == 'New'">
    <input type="text" name="new_category" class="form-control" ng-model="new_category" placeholder="Category Name" ng-required="link.category == 'New'" />
  </div>
  <div class="form-group">
    <input type="checkbox" ng-model="link.is_read" ng-checked="link.is_read | boolparse" /> Read
  </div>
  <div class="form-group">
    <button class="btn btn-primary" ng-disabled="!link_form.$valid">Save</button>
    <a class="btn btn-default" ng-click="cancelEdit()">Cancel</a>
  </div>
  <div class="form-group" ng-show="error">
    <div class="alert alert-danger"></div>
  </div>
</form>
