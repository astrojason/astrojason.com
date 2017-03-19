describe 'compareTo directive unit tests', ->
  $scope = null
  $compile = null
  directiveElement = null
  getCompliledDirective = ()->
    element = angular.element """
      <form name="myForm">
      <input name="main" ng-model="main" compare-to="second" />
      <input name="second" ng-model="second" compare-to="main" />
      </form>
    """
    compiledElement = $compile(element)($scope)
    $scope.$digest()
    return compiledElement

  beforeEach ->
    module 'astroApp'
    inject ($rootScope, _$compile_)->
      $scope = $rootScope.$new()
      $compile = _$compile_

  it 'both models should be valid when they match', ->
    directiveElement = getCompliledDirective()
    $scope.$digest()
    $scope.main = 'changed'
    $scope.second = 'changed'
    $scope.$digest()
    expect($scope.myForm.main.$valid).toEqual true
    expect($scope.myForm.second.$valid).toEqual true

  it 'both models should be invalid when they do not match', ->
    directiveElement = getCompliledDirective()
    $scope.$digest()
    $scope.main = 'changed'
    $scope.second = 'notthesame'
    $scope.$digest()
    expect($scope.myForm.main.$valid).toEqual false
    expect($scope.myForm.second.$valid).toEqual false