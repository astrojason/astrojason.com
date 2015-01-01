<div class="row" ng-show="errorMessage">
  <div class="alert alert-danger">@{{ errorMessage }}</div>
</div>
<div class="row" ng-show="!editing && book.title">
  <div class="col-lg-12">
    <div class="alert alert-danger" ng-show="deleting == true">
      <h4>Delete Link</h4>
      <p>Deleting the link cannot be undone.</p>
      <p>
        <a class="btn btn-danger" ng-click="delete()">Yes I want to delete</a>
        <a class="btn btn-default" ng-click="deleting = false">No, do not delete</a>
      </p>
    </div>
    @{{ book.title }}
    <span ng-show="book.author_fname || book.author_lname" ng-cloak>@{{ book.author_fname }} @{{ book.author_lname }}</span>
    <span class="small" ng-show="book.series" ng-cloak>@{{ book.series }} @{{ book.series_order }}</span>
    <div class="pull-right">
      <span class="glyphicon glyphicon-pencil tool" ng-click="editing = true"></span>
      <span class="glyphicon glyphicon-ok tool" ng-click="markAsRead()" ng-show="!(book.is_read | boolparse)"></span>
      <span class="glyphicon glyphicon-book tool" ng-click="markAsUnread()" ng-show="(book.is_read | boolparse)"></span>
      <span class="glyphicon glyphicon-remove tool" ng-click="deleting = true"></span>
    </div>
  </div>
</div>
<form role="form" name="book_form" ng-submit="save()" novalidate ng-show="editing">
  <div class="form-group">
    <label for="title">Title</label>
    <input type="text" name="title" ng-model="book.title" class="form-control" placeholder="Title" required>
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
  <div class="form-group">
    <label for="series">Series Number</label>
    <input type="number" name="series" ng-model="book.series_order" class="form-control" placeholder="Series Number" ng-required="book.series" />
  </div>
  <div class="form-group">
    <label for="category">Category</label>
    <select name="category" ng-model="book.category" ng-init="setCategories({{ $categories }})" ng-options="category for category in categories" class="form-control"></select>
  </div>
  <div class="form-group" ng-show="book.category == 'New'">
    <input type="text" name="new_category" class="form-control" ng-model="new_category" placeholder="Category Name" ng-required="book.category == 'New'" />
  </div>
  <div class="form-group">
    <input type="checkbox" ng-model="link.is_read" /> Read
  </div>
  <div class="form-group">
    <button class="btn btn-primary" ng-disabled="!book_form.$valid">Save</button>
    <a class="btn btn-default" ng-click="editing = false">Cancel</a>
  </div>
</form>
