window.app.controller 'LinkController', ['$scope', '$http', ($scope, $http)->
  $scope.save = ->
    data = $scope.link
    if $scope.link.category == 'new'
      $scope.link.category = $scope.new_category
    link_Promise = $http.post '/api/links/add', $.param data
    link_Promise.success (response)->
      if response.success
        if $scope.link.category == $scope.new_category
          $scope.categories.push $scope.new_category
    link_Promise.error ->
      $scope.$emit 'errorOccurred', 'Problem ' + ($scope.link.id ? 'updating' : 'adding') + ' link'
]
