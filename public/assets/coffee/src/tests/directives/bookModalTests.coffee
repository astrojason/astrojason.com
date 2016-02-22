describe 'bookModal directive unit tests', ->
  $scope = null
  $compile = null
  $httpBackend = null

  beforeEach ->
    module 'astroApp'
    inject ($rootScope, _$compile_, _$httpBackend_)->
      $scope = $rootScope.$new()
      $compile = _$compile_
      $httpBackend = _$httpBackend_

  it 'should get the book modal template', ->
    $httpBackend.expectGET('templates/book-modal').respond 200, "<div></div>"
    element = angular.element '<book-modal></book-modal>'
    $compile(element)($scope)
    $scope.$digest()
    $httpBackend.flush()