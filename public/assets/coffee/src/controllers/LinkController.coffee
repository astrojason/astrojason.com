window.app.controller 'LinkController', ['$scope', '$http', '$controller', ($scope, $http, $controller)->

  $controller 'FormMasterController', $scope: $scope

  $scope.deleting = false
  $scope.errorMessage = false

  $scope.$watch 'link.link', (oldValue, newValue)->
    if oldValue != newValue
      $scope.errorMessage = false

  $scope.linkOpened = ->
    open_Promise = $http.post '/api/links/open/' + $scope.link.id
    open_Promise.success (response)->
      if response.success
        console.log 'Read count updated'
      else
        console.log 'Read count update problem'
    open_Promise.error ->
      $scope.$emit 'errorOccurred', 'Problem updating read count'

  $scope.markAsRead = ->
    read_Promise = $http.post '/api/links/read/' + $scope.link.id
    read_Promise.success (response)->
      if response.success
        $scope.link.is_read = true
        if $scope.$parent.markAsRead
          $scope.$parent.markAsRead $scope.link

  $scope.markAsUnread = ->
    read_Promise = $http.post '/api/links/unread/' + $scope.link.id
    read_Promise.success (response)->
      if response.success
        $scope.link.is_read = false

  $scope.delete = ->
    read_Promise = $http.post '/api/links/delete/' + $scope.link.id
    read_Promise.success (response)->
      if response.success
        if $scope.$parent.deleteItem
          $scope.$parent.deleteItem $scope.link

  $scope.save = ->
    data = $scope.link
    if $scope.link.category == 'New'
      $scope.link.category = $scope.new_category
    link_Promise = $http.post '/api/links/save', $.param data
    link_Promise.success (response)->
      if response.success
        if $scope.link_form.category.$dirty
          if $scope.$parent.changeCategory
            $scope.$parent.changeCategory $scope.link
        if $scope.link.category == $scope.new_category
          $scope.categories.push $scope.new_category
        $scope.editing = false
        if $scope.$parent.linkAdded
          $scope.$parent.linkAdded()
        alertify.success "Link " + (if 0 == parseInt $scope.link.id then "added" else "updated") + " successfully"
      else
        $scope.errorMessage = response.error
        if $scope.$parent.saveError
          $scope.$parent.saveError response.error
    link_Promise.error ->
      $scope.$emit 'errorOccurred', 'Problem ' + ($scope.link.id ? 'updating' : 'adding') + ' link.'

  $scope.setCategories = (categories)->
    $scope.categories = categories
]
