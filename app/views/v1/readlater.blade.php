<!DOCTYPE html>
<html lang="en">
  <base href="/" />
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Read it Later :: astrojason.com</title>
    <link href="assets/bower/alertifyjs/dist/css/alertify.css" rel="stylesheet" />
    <link href="assets/bower/angular-bootstrap/ui-bootstrap-csp.css" rel="stylesheet" />
    <link href="assets/bower/typeahead.js-bootstrap3.less/typeaheadjs.css" rel="stylesheet" />
    <link href="assets/sass/build/vendor/loader.css" rel="stylesheet" />
    <link href="assets/sass/build/v1/base.css" rel="stylesheet" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script type="text/javascript" src="assets/bower/html5shiv/dist/html5shiv.min.js"></script>
      <script type="text/javascript" src="assets/bower/respond/dest/respond.min.js"></script>
    <![endif]-->
  </head>
  <body ng-app="astroApp" ng-controller="MasterController">
    <div id="overlay" ng-show="init" class="overlay"></div>
    <div ng-controller="ReadLaterController">
      @if(Auth::check())
        <div
          class="alert alert-danger"
          ng-show="error"
          ng-cloak>
          {{ error }}
          <span
            class="glyphicon glyphicon-remove-sign pull-right"
            ng-click="closeWindow()"></span>
        </div>
        <form
          role="form"
          name="article_form"
          novalidate>
          <div class="form-group" ng-init="init()">
            <label for="name">Name</label>
            <input
              ng-init="article.title = '<%% $title %%>'"
              type="text"
              name="name"
              ng-model="article.title"
              class="form-control"
              placeholder="Title"
              required />
          </div>
          <div class="form-group">
            <label for="url">URL</label>
            <input
              ng-init="article.url = '<%% $url %%>'"
              type="text"
              name="url"
              ng-model="article.url"
              class="form-control"
              placeholder="URL"
              required />
          </div>
          <div class="form-group">
            <label for="category">Category</label>
            <select
              ng-init="article.categories = []"
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
                placeholder="New Category" />
              <span class="input-group-btn">
                <button
                  class="btn"
                  ng-class="{'btn-primary': new_category != ''}"
                  ng-click="addCategory()">Add</button>
              </span>
            </div>
          </div>
          <div class="form-group">
            <input type="checkbox" ng-model="article.is_read" /> Read
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
      @else
        <div class="alert alert-warning">
          You must <a href="http://www.astrojason.com" target="_blank">Login</a> to use this widget.
        </div>
      @endif
    </div>
    @include('v1.partial.js')
  </body>