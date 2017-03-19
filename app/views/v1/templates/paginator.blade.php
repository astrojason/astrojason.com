<ul class="pagination pagination-sm">
  <li><a href="#" ng-class="{disabled: page == 1}" ng-click="setCurrentPage(page - 1)"><i>&lt;&lt;</i></a></li>
  <li ng-repeat="nav_page in navPages"><a href="#" ng-class="{current: page == nav_page}" ng-click="setCurrentPage(nav_page)">{{ nav_page }}</a></li>
  <li><a href="#" ng-class="{disabled: page == pages}" ng-click="setCurrentPage(page + 1)"><i>&gt;&gt;</i></a></li>
</ul>