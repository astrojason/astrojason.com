window.app.controller 'LinkController', ['$scope', '$http', ($scope, $http)->
  $scope.editing = false
  $scope.deleting = false

  $scope.linkOpened = ->
    console.log 'Opened the link'

  $scope.isRead = ->
    switch $scope.link.is_read
      when '1' then true
      else false

  $scope.markAsRead = ->
    read_Promise = $http.post '/api/links/read/' + $scope.link.id
    read_Promise.success (response)->
      if response.success
        $scope.link.is_read = true
        $scope.$parent.total_read++

  $scope.markAsUnread = ->
    read_Promise = $http.post '/api/links/unread/' + $scope.link.id
    read_Promise.success (response)->
      if response.success
        $scope.link.is_read = false

  $scope.delete = ->
    read_Promise = $http.post '/api/links/delete/' + $scope.link.id
    read_Promise.success (response)->
      if response.success
        $scope.link.deleted = true

  $scope.save = ->
    data = $scope.link
    if $scope.link.category == 'new'
      $scope.link.category = $scope.new_category
    link_Promise = $http.post '/api/links/save', $.param data
    link_Promise.success (response)->
      if response.success
        if $scope.link.category == $scope.new_category
          $scope.categories.push $scope.new_category
        $scope.editing = false
    link_Promise.error ->
      $scope.$emit 'errorOccurred', 'Problem ' + ($scope.link.id ? 'updating' : 'adding') + ' link'
]
