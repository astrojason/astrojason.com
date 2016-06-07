describe 'ReadLaterController unit tests', ->
  $scope = null
  $timeout = null
  $window = null
  ReadLaterController = null
  Link = null

  beforeEach ->
    module 'astroApp'
    inject ($rootScope, $controller, _$timeout_, _$window_, _Link_)->
      $scope = $rootScope.$new()
      $timeout = _$timeout_
      $window = _$window_
      Link = _Link_

      mockInjections =
        $scope: $scope
        $timeout: $timeout

      ReadLaterController = $controller 'ReadLaterController', mockInjections

  it 'should set the default values', ->
    expect($scope.newLink).toEqual null
    expect($scope.success).toEqual false
    expect($scope.editing).toEqual true
    expect($scope.error).toEqual false

  it 'should create a new link with the passed parameters', ->
    sampleLink = new Link()
    sampleLink.user_id = 1
    sampleLink.name = 'This is the name'
    sampleLink.link = 'http://www.google.com'
    $scope.createLink 1, 'This is the name', 'http://www.google.com'
    expect($scope.newLink).toEqual sampleLink

  it 'should call the window.parent.postMessage when closeModal is $emitted', ->
    spyOn $window.parent, 'postMessage'
    $scope.$emit 'closeModal'
    $scope.$digest()
    $timeout.flush()
    expect($window.parent.postMessage).toHaveBeenCalledWith 'closeWindow', '*'

  it 'should set the error message', ->
    $scope.saveError('This is the error message');
    expect($scope.error).toEqual 'This is the error message'
    expect($scope.editing).toEqual false