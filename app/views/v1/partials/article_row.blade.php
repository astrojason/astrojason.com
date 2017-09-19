<td class="col-xs-6 col-md-6">
  <ul class="list-unstyled">
    <li><a ng-href="{{ article.url }}" target="_blank">{{ article.title }}</a></li>
    <li ng-show="article.lastRead()">Last read: {{ article.lastRead() | date }}</li>
  </ul>
</td>
<td>
  <div ng-repeat="category in article.categories" class="badge float-left" style="margin-right: 5px">
    {{ category.name }}
  </div>
</td>
<td>
  @if($detail_view)
    <div
      class="col-md-2 hidden-xs col-md-6"
      ng-hide="display_category.id > 0">
      {{ article.recommended.length }}
    </div>
  @endif
<td>
  @include('v1.partials.article_controls')
</td>