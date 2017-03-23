<tbody ng-show="loadingArticles" ng-cloak>
  <tr>
    <td class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading articles...</td>
  </tr>
</tbody>
<tbody ng-show="loadArticlesError" ng-cloak>
  <tr>
    <td class="text-danger text-center">Could not load articles.</td>
  </tr>
</tbody>
<tbody ng-show="articles.length == 0 && !loadingArticles && !loadArticlesError" ng-cloak>
  <td class="text-info text-center">There are no articles.</td>
</tbody>
<tbody ng-show="articles.length > 0" ng-cloak>
  <tr
    ng-repeat="article in articles"
    class="repeat-item"
    ng-class="{'read': article.readToday()}">
    <td>
      <a ng-href="{{ article.url }}" target="_blank">
        {{ article.title }}
      </a>
      <div class="btn-group float-right">
        <button class="btn btn-sm btn-outline-success" ng-click="article.markRead()">
          <span class="fa fa-check"></span>
        </button>
        <button class="btn btn-sm btn-outline-info postpone-button" ng-click="article.postpone()">
          <span class="fa fa-calendar-plus-o"></span>
        </button>
        <button class="btn btn-sm btn-outline-primary" ng-click="article.edit()">
          <span class="fa fa-edit"></span>
        </button>
        <button
          class="btn btn-sm btn-outline-danger"
          ng-click="article.warnDelete()">
          <span class="fa fa-trash"></span>
        </button>
      </div>
    </td>
  </tr>
</tbody>