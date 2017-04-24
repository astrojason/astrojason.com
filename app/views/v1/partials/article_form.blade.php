<form
  role="form"
  name="article_form"
  novalidate>
  <div class="form-group" ng-init="init()">
    <label for="name">Name</label>
    <input
      type="text"
      name="name"
      ng-model="article.title"
      class="form-control"
      placeholder="Title"
      required/>
  </div>
  <div class="form-group">
    <label for="url">URL</label>
    <input
      type="text"
      name="url"
      ng-model="article.url"
      class="form-control"
      placeholder="URL"
      required/>
  </div>
  <div class="form-group">
    <label for="category">Category</label>
    <select
      class="form-control"
      ng-model="article.categories"
      ng-options="category as category.name for category in categories"
      multiple>
    </select>
  </div>
  <div class="form-group">
    <label for="new_category">New Category</label>
    <div class="input-group">
      <input
        ng-init="new_category = ''"
        type="text"
        name="new_category"
        ng-model="new_category"
        class="form-control"
        placeholder="New Category"/>
      <span class="input-group-btn">
        <button
          class="btn"
          ng-class="{'btn-primary': new_category != ''}"
          ng-click="addCategory()">Add</button>
      </span>
    </div>
  </div>
  <div class="form-group">
    <input type="checkbox" ng-model="article.is_read"/> Read
  </div>
  <div class="form-group">
    <button
      class="btn"
      ng-class="(article_form.$valid && new_category == '') ? 'btn-success' : 'btn-disabled'"
      ng-click="save()"
      ng-disabled="!(article_form.$valid) || (new_category != '')">
      Save
    </button>
    <a class="btn btn-default" ng-click="closeWindow()">Cancel</a>
  </div>
</form>