<div class="row" ng-show="errorMessage">
  <div class="alert alert-danger">{{ errorMessage }}</div>
</div>
<div class="row" ng-show="!editing && game.title">
  <div class="col-md-12">
    <div class="alert alert-danger row" ng-show="deleting == true">
      <div class="col-md-12">
        <h4>Delete "{{ game.title }}"?</h4>
        <p>Deleting the game cannot be undone.</p>
        <p>
          <a class="btn btn-danger" ng-click="delete()">Yes I want to delete</a>
          <a class="btn btn-default" ng-click="deleting = false">No, do not delete</a>
        </p>
      </div>
    </div>
    <div class="row">
      <div ng-class="showPlatform ? 'col-md-3' : 'col-md-9'">
        <span class="underline">{{ game.title }}</span>
      </div>
      <div ng-class="recommendation ? 'col-md-7' : 'col-md-6'" ng-show="showPlatform">
        {{ game.platform }}
      </div>
      <div class="col-md-1" ng-hide="recommendation">
        {{ game.times_recommended }}
      </div>
      <div class="col-md-2">
        <div class="pull-right">
          <span class="glyphicon glyphicon-pencil tool" ng-click="editing = true"></span>
          <span class="glyphicon glyphicon-ok tool" ng-click="togglePlayed()" ng-class="(game.completed | boolparse) ? 'glyphicon-book' : 'glyphicon-ok'"></span>
          <span class="glyphicon glyphicon-remove tool" ng-click="deleting = true"></span>
        </div>
      </div>
    </div>
  </div>
</div>
<form role="form" name="game_form" ng-submit="save()" novalidate ng-show="editing">
  <div class="form-group">
    <label for="title">Title</label>
    <input type="text" name="title" ng-model="game.title" class="form-control" placeholder="Title" required>
  </div>
  <div class="form-group">
    <label for="category">Platform</label>
    <select name="platform" ng-model="game.platform" ng-init="setPlatforms(<%% $platforms %%>)" ng-options="platform for platform in platforms" class="form-control"></select>
  </div>
  <div class="form-group" ng-show="game.platform == 'New'">
    <input type="text" name="new_platform" class="form-control" ng-model="new_platform" placeholder="Platform Name" ng-required="game.platform == 'New'" />
  </div>
  <div class="form-group">
    <label class="checkbox-inline">
      <input type="checkbox" ng-model="game.completed" ng-checked="game.completed | boolparse" /> Completed
    </label>
  </div>
  <div class="form-group">
    <button class="btn btn-primary" ng-disabled="!game_form.$valid">Save</button>
    <a class="btn btn-default" ng-click="cancelEdit()">Cancel</a>
  </div>
</form>
