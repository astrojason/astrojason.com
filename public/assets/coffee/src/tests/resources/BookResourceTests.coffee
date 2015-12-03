describe 'BookResource', ->
  BookResource = null
  $httpBackend = null

  beforeEach ->
    module 'astroApp'
    inject (_$httpBackend_, _BookResource_)->
      $httpBackend = _$httpBackend_
      BookResource = _BookResource_

  it 'should call the default endpoint', ->
    $httpBackend.expectGET('/api/book').respond 200
    BookResource.query()
    $httpBackend.flush()