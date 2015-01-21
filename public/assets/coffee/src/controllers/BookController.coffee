window.app.controller 'BookController', ['$scope', '$http', ($scope, $http)->

  $scope.setCategories = (categories)->
    $scope.book_categories = categories
    if categories.length > 0
      $scope.recommendation_category = categories[0]

  $scope.getRecommendation = ->
    reco_Promise = $http.get '/api/books/recommendation/' + $scope.recommendation_category
    reco_Promise.success (response)->
      $scope.recommended_book = response.book
]
