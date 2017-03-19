describe 'BookResource test', ->
  BookResource = null
  $httpBackend = null

  beforeEach ->
    module 'astroApp'
    inject (_$httpBackend_, _BookResource_)->
      $httpBackend = _$httpBackend_
      BookResource = _BookResource_

  it 'should post to the default endpoint', ->
    $httpBackend.expectPOST('/api/book').respond 200
    BookResource.save name: 'New Item'
    $httpBackend.flush()

  it 'should call the default endpoint', ->
    $httpBackend.expectGET('/api/book').respond 200
    BookResource.query()
    $httpBackend.flush()

  it 'should call the specific endpoint', ->
    $httpBackend.expectGET('/api/book/1234').respond 200
    BookResource.get id: 1234
    $httpBackend.flush()

  it 'should post to the specific endpoint', ->
    $httpBackend.expectPOST('/api/book/1234').respond 200
    BookResource.save
      id: 1234
      name: 'Updated Item'
    $httpBackend.flush()

  it 'should call the delete endpoint', ->
    $httpBackend.expectDELETE('/api/book/1234').respond 200
    BookResource.delete id: 1234
    $httpBackend.flush()

  it 'should call goodreads endpoint when goodreads is called', ->
    $httpBackend.expectGET('/api/book/goodreads').respond 200
    BookResource.goodreads()
    $httpBackend.flush()