<div class="pull-right">
  <span
    class="glyphicon glyphicon-ok-circle text-success"
    ng-click="article.markRead()">
  </span>
  <span
    class="glyphicon glyphicon-edit"
    ng-click="article.edit()">
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