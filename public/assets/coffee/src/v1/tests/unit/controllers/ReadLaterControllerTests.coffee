describe 'ReadLaterController unit tests', ->
  $scope = null
  $timeout = null
  $window = null
  ReadLaterController = null
  mockArticleResource = null
  mockArticleAddDeferred = null

  beforeEach ->
    module 'astroApp'
    inject ($rootScope, $controller, $q, $compile, _$timeout_, _$window_)->
      $scope = $rootScope.$new()
      $timeout = _$timeout_
      $window = _$window_

      mockArticleResource =
        add: ->
          mockArticleAddDeferred = $q.defer()
          $promise: mockArticleAddDeferred.promise

      mockInjections =
        $scope: $scope
        $timeout: $timeout
        ArticleResource: mockArticleResource

      ReadLaterController = $controller 'ReadLaterController', mockInjections

      formElem = angular.element('<form name="article_form"></form>')
      $compile(formElem)($scope)

      $scope.$apply()

  it '$scope.success should be false on init', ->
    expect($scope.success).toEqual false

  it '$scope.editing should be true on init', ->
    expect($scope.editing).toEqual true

  it '$scope.error should be false on init', ->
    expect($scope.error).toEqual false

  it 'should call the window.parent.postMessage when closeModal is $emitted', ->
    spyOn $window.parent, 'postMessage'
    $scope.closeWindow()
    $scope.$digest()
    $timeout.flush()
    expect($window.parent.postMessage).toHaveBeenCalledWith 'closeWindow', '*'

  it 'should set the error message', ->
    $scope.save()
    mockArticleAddDeferred.reject()
    $scope.$digest()
    expect($scope.error).toEqual 'There was an error saving the selected article.'