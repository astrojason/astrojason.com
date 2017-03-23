angular.module('astroApp').controller 'DashboardController', [
  '$scope'
  '$log'
  'ArticleResource'
  ($scope,
    $log,
    ArticleResource)->
    $scope.loadingArticles = false
    $scope.loadArticlesError = false
    $scope.articles = []

    $scope.init = ->
      $scope.getDailyArticles()

    $scope.getDailyArticles = ->
      $scope.loadArticlesError = false
      $scope.loadingArticles = true

      articlePromise = ArticleResource.daily().$promise

      articlePromise.then (articles)->
        filtered_articles = $scope.filterArticles articles
        $scope.articles = filtered_articles
        $scope.watchArticles()

      articlePromise.catch ->
        $scope.loadArticlesError = true

      articlePromise.finally ->
        $scope.loadingArticles = false

    $scope.watchArticles = ->
      $scope.$watch 'articles', ->
        if $scope.articles
          $scope.articles = $scope.filterArticles $scope.articles
      , true

    $scope.filterArticles = (articles)->
      articles.filter (article)->
        !article.readToday() && !article.postponedToday() && !article.deleted
]