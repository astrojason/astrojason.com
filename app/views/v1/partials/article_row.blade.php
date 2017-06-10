<td>
  <div
    class="col-xs-6"
    ng-class="!display_category ? 'col-md-6' : 'col-md-10'">
    <a ng-href="{{ article.url }}" target="_blank">{{ article.title }}</a>
  </div>
  <div class="col-md-4 hidden-xs" ng-show="!display_category">
    <ul class="list-unstyled">
      <li ng-repeat="category in article.categories">
        <span class="badge category-badge">{{ category.name }}</span>
      </li>
    </ul>
  </div>
  @if($detail_view)
    <div class="col-md-2 hidden-xs" ng-show="!display_category">
      {{ article.recommended.length }}
    </div>
  @endif
  @include('v1.partials.article_controls')
</td>