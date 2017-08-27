angular.module('astroApp').controller 'ArticleFormController', [
  '$rootScope'
  '$scope'
  '$log'
  '$timeout'
  '$uibModalInstance'
  'ArticleResource'
  'ArticleCategoryResource'
  'AlertifyService'
  'article'
  (
  $rootScope,
  $scope,
  $log,
  $timeout,
  $uibModalInstance,
  ArticleResource,
  ArticleCategoryResource,
  AlertifyService,
  article) ->

    $scope.article = article

    $scope.init = ->
      category_promise = ArticleCategoryResource.get().$promise

      category_promise.then (response)->
        $scope.categories = response.categories
#       Make sure they are the same objects, angular does exact matches
        $scope.article.categories = $scope.categories.filter (category)->
          category.id in $scope.article.categories.map (article_category)-> article_category.id

    $scope.save = ->
      if $scope.article.id
        save_promise = ArticleResource.save($scope.article).$promise
      else
        save_promise = ArticleResource.add($scope.article).$promise

      save_promise.then ->
        AlertifyService.success "Article #{$scope.actionPerformed()} successfully."
        if !$scope.article.id
          $timeout ->
            $scope.article = new ArticleResource()
            $scope.article_form.$setPristine()
            $uibModalInstance.dismiss()
          , 1000
        else
          $uibModalInstance.dismiss()
        if $scope.article.is_read
          $rootScope.$broadcast 'article_read', $scope.article

    $scope.closeWindow = ->
      $uibModalInstance.dismiss()

    $scope.actionPerformed = ->
      if $scope.article.id
        'updated'
      else
        'added'

    $scope.categoryExists = ->
      $scope.categories.some (category) ->
        category.name == $scope.new_category

    $scope.addCategory = ->
      if not $scope.categoryExists()
        data =
          name: $scope.new_category
        category_add_promise = ArticleCategoryResource.add(data).$promise

        category_add_promise.then (response)->
          $scope.categories.push response.category
          $scope.article.categories.push response.category
          $scope.new_category = ''
      else
        alert('Category already exists')
]
