angular.module('astroApp').controller 'ArticleFormController', [
  '$scope'
  '$log'
  '$uibModalInstance'
  'ArticleResource'
  'article'
  ($scope,
  $log,
  $uibModalInstance,
  ArticleResource,
  article) ->

    $scope.article = article

    $scope.init = ->
      category_promise = ArticleResource.categories().$promise

      category_promise.then (response)->
        $scope.categories = response.categories
#       Make sure they are the same objects, angular does exact matches
        $scope.article.categories = $scope.categories.filter (category)->
          category.id in $scope.article.categories.map (article_category)-> article_category.id

    $scope.save = ->
      save_promise = ArticleResource.save($scope.article).$promise

      save_promise.then ->
        $uibModalInstance.dismiss()

    $scope.closeWindow = ->
      $uibModalInstance.dismiss()
]