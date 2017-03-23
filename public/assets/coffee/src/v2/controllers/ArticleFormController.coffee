angular.module('astroApp').controller 'ArticleFormController', [
  '$scope'
  '$log'
  '$controller'
  'ArticleResource'
  'article'
  ($scope, $log, $controller, ArticleResource, article)->
    $scope.article = article
    $scope.form_article = angular.copy article

    $scope.init = ->
      $scope.fetchCategories()

    $scope.fetchCategories = ->

      categoryPromise = ArticleResource.categories().$promise

      categoryPromise.then (categories)->
        $scope.categories = categories
        $log.debug categories
]