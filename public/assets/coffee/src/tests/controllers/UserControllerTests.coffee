describe 'UserController unit tests', ->
  $scope = null
  UserController = null
  mockUserResource = null
  mockUserResourceAddDeferred = null

  beforeEach ->
    module 'astroApp'
    inject ($rootScope, $controller, $q)->
      $scope = $rootScope.$new()

      mockUserResource =
        add: ->
          mockUserResourceAddDeferred = $q.defer()
          $promise: mockUserResourceAddDeferred.promise

      mockInjections =
        $scope: $scope
        UserResource: mockUserResource

      UserController = $controller 'UserController', mockInjections

  it 'should set the default values for the controller variables', ->
    expect($scope.submitting).toEqual false
    expect($scope.registrationSuccess).toEqual false

  it 'should set submitting to true when checkingAvailability is broadcast', ->
    $scope.$broadcast 'checkingAvailability'
    $scope.$digest()
    expect($scope.submitting).toEqual true

  it 'should set submitting to false when checkedAvailability is broadcast', ->
    $scope.submitting = true
    $scope.$broadcast 'checkedAvailability'
    $scope.$digest()
    expect($scope.submitting).toEqual false

  it 'should set submitting to true when registerUser is called', ->
    $scope.registerUser()
    expect($scope.submitting).toEqual true

  it 'should set submitting to false when UserResource.add succeeds', ->
    $scope.registerUser()
    mockUserResourceAddDeferred.resolve()
    $scope.$digest()
    expect($scope.submitting).toEqual false

  it 'should set registrationSuccess to true when UserResource.add succeeds', ->
    $scope.registerUser()
    mockUserResourceAddDeferred.resolve()
    $scope.$digest()
    expect($scope.registrationSuccess).toEqual true

  it 'should set submitting to false when UserResource.add fails', ->
    $scope.registerUser()
    mockUserResourceAddDeferred.reject()
    $scope.$digest()
    expect($scope.submitting).toEqual false

  it 'should $emit errorOccurred when UserResource.add fails', ->
    spyOn $scope, '$emit'
    $scope.registerUser()
    mockUserResourceAddDeferred.reject()
    $scope.$digest()
    expect($scope.$emit).toHaveBeenCalledWith 'errorOccurred', 'Problem with registration'
