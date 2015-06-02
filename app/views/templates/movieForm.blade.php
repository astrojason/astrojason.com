<div class="row" ng-show="errorMessage">
  <div class="alert alert-danger">{{ errorMessage }}</div>
</div>
<div class="row" ng-show="!editing && movie.title">
  <div class="col-md-12">
    <div class="row alert alert-danger" ng-show="deleting == true">
      <div class="col-md-12">
        <h4>Delete "{{ movie.title }}"?</h4>
        <p>Deleting the movie cannot be undone.</p>
        <p>
          <a class="btn btn-danger" ng-click="delete()">Yes I want to delete</a>
          <a class="btn btn-default" ng-click="deleting = false">No, do not delete</a>
        </p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-10">
        {{ movie.title }}
      </div>
      <div class="col-md-2">
        <div class="pull-right">
          <span class="glyphicon glyphicon-pencil tool" ng-click="editing = true"></span>
          <span class="glyphicon tool" ng-click="toggleWatched()" ng-class="!(movie.is_watched | boolparse) ? 'glyphicon-eye-open' : 'glyphicon-eye-close'"></span>
          <span class="glyphicon glyphicon-remove tool" ng-click="deleting = true"></span>
        </div>
      </div>
    </div>
  </div>
</div>
<form role="form" name="movie_form" ng-submit="save(movie)" novalidate ng-show="editing">
  <div class="form-group">
    <label for="title">Title</label>
    <input type="text" name="title" ng-model="movie.title" class="form-control" placeholder="Movie Title" required />
  </div>
  <div class="form-group" style="position: relative">
    <label for="date_watched">Date watched</label>
    <input type="text" name="link" ng-model="movie.date_watched" class="form-control" placeholder="Date Watched" datetime-picker />
  </div>
  <div class="form-group">
    <input type="checkbox" ng-model="movie.is_watched" ng-checked="movie.is_watched | boolparse" /> Seen
  </div>
  <div class="form-group">
    <button class="btn btn-primary" ng-disabled="!movie_form.$valid">Save</button>
    <a class="btn btn-default" ng-click="cancelEdit()">Cancel</a>
  </div>
  <div class="form-group" ng-show="error">
    <div class="alert alert-danger"></div>
  </div>
</form>
