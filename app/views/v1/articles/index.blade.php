@extends('v1.layouts.layout')

@section('title')
  Articles
@stop

@section('content')
  <div ng-controller="ArticleController">
    <div class="row">
      <div class="col-md-12">
        <loader ng-show="loading_articles" ng-cloak></loader>
        <table class="table table-condensed table-striped table-hover" ng-init="initList()">
          <thead ng-hide="loading_articles">
            <tr>
              <th colspan="4">
                Articles
                <ul class="list-inline pull-right">
                  <li>
                    <button class="btn btn-success btn-xs pull-right"
                            ng-click="importModalOpen = true">Import from OneTab
                    </button>
                  </li>
                </ul>
              </th>
            </tr>
            <tr>
              <th colspan="4">
                <div class="input-group col-sm-12">
                  <div class="search-wrapper">
                    <input type="text" ng-model="article_query" class="form-control"
                           placeholder="Search Query"/>
                    <small
                      class="clear-input glyphicon glyphicon-remove-circle"
                      ng-show="article_query"
                      ng-click="article_query = ''"></small>
                  </div>
                  <div class="input-group-addon"><input type="checkbox" ng-model="include_read"/>
                    <label>Include read</label></div>
                </div>
              </th>
            </tr>
            <tr>
              <th colspan="4">
                <div class="input-group col-sm-12">
                  <div class="input-group-addon">Category</div>
                  <select
                    name="category"
                    ng-model="display_category"
                    ng-options="category as category.name for category in categories"
                    class="form-control">
                  </select>
                </div>
              </th>
            </tr>
            <tr>
              <th>
                <a href="#" ng-click="toggleSort('title')">Title</a>
              </th>
              <th>
                <div class="hidden-xs">
                  Category
                  <!--a href="#" ng-click="toggleSort('category')">Category</a-->
                </div>
              </th>
              <th>
                <div class="hidden-xs">
                  <a href="#" ng-click="toggleSort('times_recommended')">Times Recommended</a>
                </div>
              </th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-show="article_query && articles.length == 0 && !loading_articles">
              <td colspan="4">No results for <strong>{{ article_query }}</strong>
            </tr>
            <tr
              ng-repeat="article in articles"
              ng-class="{read: article.read.length > 0}">
              @include('v1.partials.article_row', ['detail_view' => true])
            </tr>
          </tbody>
          <tfoot ng-show="pages > 1 && !loading_articles">
            <tr>
              <td colspan="4">
                <paginator page="page" pages="pages" nav-pages="nav_pages"></paginator>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
    <div
      class="modal fade"
      id="imporLinksModal"
      astro-modal
      modal-visible="importModalOpen">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Import from OneTab</h4>
          </div>
          <div class="modal-body">
            <uib-alert
              ng-repeat="alert in alerts"
              type="{{ alert.type }}"
              ng-bind-html="alert.msg"
              close="closeAlert($index)">
            </uib-alert>
            <textarea
              ng-model="articles_to_import"
              class="form-control"></textarea>
          </div>
          <div class="modal-footer">
            <button class="btn btn-primary" ng-click="importArticles()">Import</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  </div>
@stop
