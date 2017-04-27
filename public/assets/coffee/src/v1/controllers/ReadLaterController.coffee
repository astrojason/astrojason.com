angular.module('astroApp').controller 'ReadLaterController', [
  '$scope'
  '$timeout'
  '$window'
  '$log'
  'AlertifyService'
  'ArticleResource'
  ($scope,
    $timeout,
    $window,
    $log,
    AlertifyService,
    ArticleResource)->
      $scope.success = false
      $scope.editing = true
      $scope.error = false

      $scope.init = ->
        category_promise = ArticleResource.categories().$promise

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
          , 1000

      $scope.addCategory = ->
        exists = false
        angular.forEach $scope.categories, (category) ->
          if category.name == $scope.new_category
            exists = true
            $scope.article.categories.push category
        if !exists
          newCategory =
            id: 0
            name: $scope.new_category

          $scope.categories.unshift newCategory
          $scope.article.categories.push newCategory
        $scope.new_category = ''
        $scope.article_form.new_category.$setPristine()
]