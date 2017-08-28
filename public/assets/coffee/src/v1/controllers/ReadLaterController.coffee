angular.module('astroApp').controller 'ReadLaterController', [
  '$scope'
  '$timeout'
  '$window'
  '$log'
  'AlertifyService'
  'ArticleResource',
  'ArticleCategoryResource'
  ($scope,
    $timeout,
    $window,
    $log,
    AlertifyService,
    ArticleResource,
    ArticleCategoryResource)->
      $scope.success = false
      $scope.editing = true
      $scope.error = false

      new_article =
        title: ''
        url: ''
        categories: []
        is_read: false

      $scope.initArticle = (article)->
        $scope.article = angular.extend {}, new_article, article

      $scope.init = ->
        category_promise = ArticleCategoryResource.get().$promise

        category_promise.then (response)->
          $scope.categories = response.categories

      $scope.save = ->
        $scope.error = null
        save_promise = ArticleResource.add($scope.article).$promise

        save_promise.then ->
          AlertifyService.success "Article added successfully."
          $scope.closeWindow()

        save_promise.catch (response)->
          $scope.error = response?.data?.message || 'There was an error saving the selected article.'

      $scope.closeWindow = ->
          $timeout ->
            $window.parent.postMessage 'closeWindow', '*'
            $scope.article = angular.copy new_article
            $scope.article_form.$setPristine()
          , 1000

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