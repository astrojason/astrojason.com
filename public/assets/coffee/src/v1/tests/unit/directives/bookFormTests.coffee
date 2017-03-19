describe 'bookForm directive unit tests', ->
  $scope = null
  $compile = null
  $httpBackend = null

  beforeEach ->
    module 'astroApp'
    inject ($rootScope, _$compile_, _$httpBackend_)->
      $scope = $rootScope.$new()
      $compile = _$compile_
      $httpBackend = _$httpBackend_

  it 'should get the book form template', ->
    $httpBackend.expectGET('templates/book-form').respond 200
    element = angular.element '<book-form></book-form>'
    $compile(element)($scope)
    $scope.$digest()
    $httpBackend.flush()