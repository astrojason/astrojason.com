Cdescribe 'CreditResource unit tests', ->
  CreditResource = null
  $httpBackend = null

  beforeEach ->
    module 'astroApp'
    inject (_$httpBackend_, _CreditResource_)->
      $httpBackend = _$httpBackend_
      CreditResource = _CreditResource_

  it 'should PUT to /api/credit/', ->
    $httpBackend.expectPUT('/api/credit').respond 200, account: {}
    CreditResource.add()
    $httpBackend.flush()

  it 'should DELETE to /api/credit/1234', ->
    $httpBackend.expectDELETE('/api/credit/1234').respond 200
    CreditResource.disable id: 1234
    $httpBackend.flush()

  it 'should GET to /api/credit', ->
    $httpBackend.expectGET('/api/credit').respond 200
    CreditResource.query()
    $httpBackend.flush()

  it 'should GET to /api/credit/report', ->
    $httpBackend.expectGET('/api/credit/report').respond 200
    CreditResource.report()
    $httpBackend.flush()

  it 'should POST to /api/credit/1234', ->
    $httpBackend.expectPOST('/api/credit/1234').respond 200
    CreditResource.save id: 1234
    $httpBackend.flush()
