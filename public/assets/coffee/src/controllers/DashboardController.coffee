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
        $scope.articles = articles
        $scope.watchArticles()

      articlePromise.catch ->
        $scope.loadArticlesError = true

      articlePromise.finally ->
        $scope.loadingArticles = false

    $scope.watchArticles = ->
      $scope.$watch 'articles', ->
        if $scope.articles
          $scope.filterArticles()
      , true

    $scope.filterArticles = ->
      $scope.articles = $scope.articles.filter (article)->
        !article.readToday() && !article.postponedToday()
]