describe 'checkAvailability directive unit tests', ->
  $scope = null
  $compile = null
  $httpBackend = null
  directiveElement = null
  getCompliledDirective = (model)->
    element = angular.element """
      <form name="myForm">
      <input name="#{model}" check-availability ng-model="#{model}" />
      </form>
    """
    compiledElement = $compile(element)($scope)
    $scope.$digest()
    return compiledElement

  beforeEach ->
    module 'astroApp'
    inject ($rootScope, _$compile_, _$httpBackend_)->
      $scope = $rootScope.$new()
      $compile = _$compile_
      $httpBackend = _$httpBackend_

  it 'should change the validity back to true when a keypress occurs', ->
    directiveElement = getCompliledDirective 'username'
    $scope.myForm.username.$setValidity 'unique', false
    expect($scope.myForm.username.$valid).toEqual false
    directiveElement.find('input').triggerHandler 'keyup'
    $scope.$digest()
    expect($scope.myForm.username.$valid).toEqual true

  it 'should broadcast checkingAvailability when the value of the model changes', ->
    $httpBackend.expectPOST('/api/user/checkusername').respond 200
    spyOn $scope, '$broadcast'
    directiveElement = getCompliledDirective 'username'
    $scope.myForm.username.$setViewValue 'test.user'
    directiveElement.find('input').triggerHandler 'blur'
    $scope.$digest()
    expect($scope.$broadcast).toHaveBeenCalledWith 'checkingAvailability'

  it 'should broadcast checkedAvailability when the check succeeds', ->
    $httpBackend.expectPOST('/api/user/checkusername').respond 200
    spyOn $scope, '$broadcast'
    directiveElement = getCompliledDirective 'username'
    $scope.myForm.username.$setViewValue 'test.user'
    directiveElement.find('input').triggerHandler 'blur'
    $httpBackend.flush()
    $scope.$digest()
    expect($scope.$broadcast).toHaveBeenCalledWith 'checkedAvailability'

  it 'should set the validity of the element to true when the endpoint returns success', ->
    $httpBackend.expectPOST('/api/user/checkusername').respond 200
    directiveElement = getCompliledDirective 'username'
    $scope.myForm.username.$setViewValue 'test.user'
    directiveElement.find('input').triggerHandler 'blur'
    $httpBackend.flush()
    $scope.$digest()
    expect($scope.myForm.username.$valid).toEqual true

  it 'should broadcast checkedAvailability when the check fails', ->
    $httpBackend.expectPOST('/api/user/checkusername').respond 403
    spyOn $scope, '$broadcast'
    directiveElement = getCompliledDirective 'username'
    $scope.myForm.username.$setViewValue 'test.user'
    directiveElement.find('input').triggerHandler 'blur'
    $httpBackend.flush()
    $scope.$digest()
    expect($scope.$broadcast).toHaveBeenCalledWith 'checkedAvailability'

  it 'should set the validity of the element to false when the endpoint returns an error', ->
    $httpBackend.expectPOST('/api/user/checkusername').respond 403
    directiveElement = getCompliledDirective 'username'
    $scope.myForm.username.$setViewValue 'test.user'
    directiveElement.find('input').triggerHandler 'blur'
    $httpBackend.flush()
    $scope.$digest()
    expect($scope.myForm.username.$valid).toEqual false

  it 'should check the validity of the emaol', ->
    $httpBackend.expectPOST('/api/user/checkemail').respond 403
    directiveElement = getCompliledDirective 'email'
    $scope.myForm.email.$setViewValue 'test.user'
    directiveElement.find('input').triggerHandler 'blur'
    $httpBackend.flush()
    $scope.$digest()
    expect($scope.myForm.email.$valid).toEqual false