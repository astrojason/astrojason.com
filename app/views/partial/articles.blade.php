<tbody ng-show="loadingArticles" ng-cloak>
  <tr>
    <td>
      <div class="progress">
        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
      </div>
    </td>
  </tr>
</tbody>
<tbody ng-show="loadArticlesError" ng-cloak>
  <tr>
    <td class="text-danger text-center">Could not load articles.</td>
  </tr>
</tbody>
<tbody ng-show="articles.length == 0 && !loadingArticles" ng-cloak>
  <td class="text-info text-center">There are no articles.</td>
</tbody>
<tbody>
  <tr
    ng-repeat="article in articles"
    ng-class="{'read': article.readToday()}">
    <td>
      <a ng-href="{{ article.url }}" target="_blank">
        {{ article.title }}
      </a>
      <div class="btn-group float-right">
        <button class="btn btn-sm btn-outline-success" ng-click="article.markRead()">
          <span class="fa fa-check"></span>
        </button>
        <button class="btn btn-sm btn-outline-info" ng-click="article.postpone()">
          <span class="fa fa-calendar-plus-o"></span>
        </button>
        <button class="btn btn-sm btn-outline-danger" ng-click="article.delete()">
          <span class="fa fa-trash"></span>
        </button>
      </div>
    </td>
  </tr>
</tbody>