describe 'Book', ->
  Book = null

  beforeEach ->
    module 'astroApp'
    inject (_Book_)->
      Book = _Book_

  it 'should create a new book object', ->
    new_book = new Book 1
    expect(new_book instanceof Book).toEqual true
    expect(new_book.id).toEqual null
    expect(new_book.title).toEqual ''
    expect(new_book.author_fname).toEqual ''
    expect(new_book.author_lname).toEqual ''
    expect(new_book.series).toEqual ''
    expect(new_book.series_order).toEqual 0
    expect(new_book.category).toEqual 'To Read'
    expect(new_book.owned).toEqual false
    expect(new_book.is_read).toEqual false
    expect(new_book.user_id).toEqual 1