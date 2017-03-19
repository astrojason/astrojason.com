describe 'ArticleResource test', ->
  ArticleResource = null
  $httpBackend = null

  beforeEach ->
    module 'astroApp'
    inject (_$httpBackend_, _ArticleResource_)->
      $httpBackend = _$httpBackend_
      ArticleResource = _ArticleResource_

  it 'should post to the default endpoint', ->
    $httpBackend.expectPUT('/api/article').respond 200
    ArticleResource.add name: 'New Item'
    $httpBackend.flush()

  it 'should call the default endpoint', ->
    $httpBackend.expectGET('/api/article').respond 200
    ArticleResource.query()
    $httpBackend.flush()

  it 'should call the specific endpoint', ->
    $httpBackend.expectGET('/api/article/1234').respond 200
    ArticleResource.get id: 1234
    $httpBackend.flush()

  it 'should post to the specific endpoint', ->
    $httpBackend.expectPOST('/api/article/1234').respond 200
    ArticleResource.save
      id: 1234
      name: 'Updated Item'
    $httpBackend.flush()

  it 'should call the delete endpoint', ->
    $httpBackend.expectDELETE('/api/article/1234').respond 200
    ArticleResource.delete id: 1234
    $httpBackend.flush()

  it 'should call daily endpoint when daily is called', ->
    $httpBackend.expectGET('/api/article/daily').respond 200
    ArticleResource.daily()
    $httpBackend.flush()

  it 'should call import endpoint when import is called', ->
    $httpBackend.expectPOST('/api/article/import').respond 200
    ArticleResource.import()
    $httpBackend.flush()

  it 'should call the read endpoint when article.markRead is called', ->
    $httpBackend.expectGET('/api/article/1234/read').respond 200
    article = new ArticleResource()
    article.id = '1234'
    article.markRead()
    $httpBackend.flush()

  it 'should return true when the article has been read today', ->
    article = new ArticleResource()
    article.read = [
        (new moment()).format('YYYY-MM-DD')
      ]
    expect(article.readToday()).toBe true

  it 'should return false when the article has not been read today', ->
    article = new ArticleResource()
    article.read = [
      '2017-03-02'
    ]
    expect(article.readToday()).toBe false

  it 'should call the postpone endpoing then article.postpone is called', ->
    $httpBackend.expectGET('/api/article/1234/postpone').respond 200
    article = new ArticleResource()
    article.id = '1234'
    article.postpone()
    $httpBackend.flush()

  it 'should return false when the article has not been postponed today', ->
    article = new ArticleResource()
    article.recommended = [
      date: (new moment()).format('YYYY-MM-DD')
      postponed: false
    ]
    expect(article.postponedToday()).toBe false

  it 'should return true when the article has been postponed today', ->
    article = new ArticleResource()
    article.recommended = [
      date: (new moment()).format('YYYY-MM-DD')
      postponed: true
    ]
    expect(article.postponedToday()).toBe true

