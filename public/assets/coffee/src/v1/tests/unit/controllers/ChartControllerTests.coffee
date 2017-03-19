describe 'ChartController unit tests', ->
  $scope = null
  ChartController = null
  mockLinkResource = null
  mockLinkReportDeferred = null

  beforeEach ->
    module 'astroApp'
    inject ($rootScope, $controller, $q)->
      $scope = $rootScope.$new()

      mockLinkResource =
        report: ->
          mockLinkReportDeferred = $q.defer()
          $promise: mockLinkReportDeferred.promise

      mockInjections =
        $scope: $scope
        LinkResource: mockLinkResource

      ChartController = $controller 'ChartController', mockInjections

  it 'should call LinkResource.report', ->
    spyOn(mockLinkResource, 'report').and.callThrough()
    $scope.init()
    expect(mockLinkResource.report).toHaveBeenCalled()
