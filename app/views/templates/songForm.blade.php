<div class="row" ng-show="errorMessage">
  <div class="alert alert-danger">{{ errorMessage }}</div>
</div>
<div class="row" ng-show="!editing && song.title">
  <div class="col-md-12">
    <div class="row alert alert-danger" ng-show="deleting == true">
      <div class="col-md-12">
        <h4>Delete Song</h4>
        <p>Deleting the song cannot be undone.</p>
        <p>
          <a class="btn btn-danger" ng-click="delete()">Yes I want to delete</a>
          <a class="btn btn-default" ng-click="deleting = false">No, do not delete</a>
        </p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-10">
        {{ song.title }} by {{ song.artist }}
      </div>
      <div class="col-md-2">
        <div class="pull-right">
          <span class="glyphicon glyphicon-pencil tool" ng-click="editing = true"></span>
          <span class="glyphicon glyphicon-ok tool" ng-click="toggleLearned()" ng-class="!(song.learned | boolparse) ? 'glyphicon-ok' : 'glyphicon-flag'"></span>
          <span class="glyphicon glyphicon-remove tool" ng-click="deleting = true"></span>
        </div>
      </div>
    </div>
  </div>
</div>
<form role="form" name="song_form" ng-submit="save()" novalidate ng-show="editing">
  <div class="form-group">
    <label for="title">Title</label>
    <input type="text" name="title" ng-model="song.title" class="form-control" placeholder="Title" required />
  </div>
  <div class="form-group" style="position: relative">
    <label for="date_watched">Artist</label>
    <input type="text" name="artist" ng-model="song.artist" class="form-control" placeholder="Artist" />
  </div>
  <div class="form-group" style="position: relative">
    <label for="date_watched">Where is the sheet music?</label>
    <input type="text" name="location" ng-model="song.location" class="form-control" placeholder="Location" />
  </div>
  <div class="form-group">
    <input type="checkbox" ng-model="movie.learned" ng-checked="movie.learned | boolparse" /> Learned
  </div>
  <div class="form-group">
    <button class="btn btn-primary" ng-disabled="!song_form.$valid">Save</button>
    <a class="btn btn-default" ng-click="cancelEdit()">Cancel</a>
  </div>
  <div class="form-group" ng-show="error">
    <div class="alert alert-danger"></div>
  </div>
</form>
