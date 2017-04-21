<td>
  <a ng-href="{{ article.url }}" target="_blank">{{ article.title }}</a>
  <div class="pull-right">
    <span
      class="glyphicon glyphicon-ok-circle text-success"
      ng-click="readArticle(article, 'daily_articles')">
    </span>
    <span
      class="glyphicon glyphicon-calendar text-info postpone-button"
      ng-click="postponeArticle(article)">
    </span>
    <span
      class="glyphicon glyphicon-remove-circle text-danger"
      ng-click="article.warnDelete()">
    </span>
  </div>
</td>