<div class="row" ng-show="errorMessage">
  <div class="alert alert-danger">{{ errorMessage }}</div>
</div>
<div class="row" ng-show="!editing && book.title">
  <div class="col-md-12">
    <div class="alert alert-danger row" ng-show="deleting == true">
      <div class="col-md-12">
        <h4>Delete Book</h4>
        <p>Deleting the book cannot be undone.</p>
        <p>
          <a class="btn btn-danger" ng-click="delete()">Yes I want to delete</a>
          <a class="btn btn-default" ng-click="deleting = false">No, do not delete</a>
        </p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-10">
        <u>{{ book.title }}</u>
        <span ng-show="book.author_fname || book.author_lname" ng-cloak>{{ book.author_fname }} {{ book.author_lname }}</span>
        <span class="small" ng-show="book.series" ng-cloak>{{ book.series }} {{ book.series_order }}</span>
      </div>
      <div class="col-md-2">
        <div class="pull-right">
          <span class="glyphicon glyphicon-pencil tool" ng-click="editing = true"></span>
          <span class="glyphicon  tool" ng-click="toggleRead()" ng-class="!(book.is_read | boolparse) ? 'glyphicon-ok' : 'glyphicon-book'"></span>
          <span class="glyphicon glyphicon-remove tool" ng-click="deleting = true"></span>
        </div>
      </div>
    </div>
  </div>
</div>
<div ng-show="editing">
  <form role="form" name="book_form" ng-submit="save()" novalidate>
    <div class="form-group">
      <label for="title">Title</label>
      <input type="text" name="title" ng-model="book.title" class="form-control" placeholder="Title" required />
    </div>
    <div class="form-group">
      <label for="author_fname">Author First Name</label>
      <input type="text" name="author_fname" ng-model="book.author_fname" class="form-control" placeholder="Author First Name" />
    </div>
    <div class="form-group">
      <label for="author_lname">Author Last Name</label>
      <input type="text" name="author_lname" ng-model="book.author_lname" class="form-control" placeholder="Author Last Name" />
    </div>
    <div class="form-group">
      <label for="series">Series</label>
      <input type="text" name="series" ng-model="book.series" class="form-control" placeholder="Series" />
    </div>
    <div class="form-group" ng-show="book.series">
      <label for="series">Series Number</label>
      <input type="number" name="series" ng-model="book.series_order" class="form-control" placeholder="Series Number" ng-required="book.series" />
    </div>
    <div class="form-group">
      <label for="category">Category</label>
      <select name="category" ng-model="book.category" ng-init="setCategories(<%% $categories %%>)" ng-options="category for category in categories" class="form-control"></select>
    </div>
    <div class="form-group" ng-show="book.category == 'New'">
      <input type="text" name="new_category" class="form-control" ng-model="new_category" placeholder="Category Name" ng-required="book.category == 'New'" />
    </div>
    <div class="form-group">
      <label class="checkbox-inline">
        <input type="checkbox" ng-model="book.owned" ng-checked="book.owned | boolparse" /> Owned
      </label>
      <label class="checkbox-inline">
        <input type="checkbox" ng-model="book.is_read" ng-checked="book.is_read | boolparse" /> Read
      </label>
    </div>
    <div class="form-group">
      <button class="btn btn-primary" ng-disabled="!book_form.$valid">Save</button>
      <a class="btn btn-default" ng-click="cancelEdit()">Cancel</a>
    </div>
  </form>
</div>
