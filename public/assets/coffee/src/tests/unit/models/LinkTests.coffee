describe 'Link', ->
  Link = null

  beforeEach ->
    module 'astroApp'
    inject (_Link_)->
      Link = _Link_

  it 'should create a new link object', ->
    new_link = new Link 1
    expect(new_link instanceof Link).toEqual true
    expect(new_link.id).toEqual null
    expect(new_link.name).toEqual ''
    expect(new_link.link).toEqual ''
    expect(new_link.category).toEqual 'Unread'
    expect(new_link.read).toEqual false
    expect(new_link.instapaper_id).toEqual ''
    expect(new_link.user_id).toEqual 1
    expect(new_link.deleting).toEqual false