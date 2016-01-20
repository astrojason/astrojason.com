describe 'Movie', ->
  Movie = null

  beforeEach ->
    module 'astroApp'
    inject (_Movie_)->
      Movie = _Movie_

  it 'should create a new movie object', ->
    new_movie = new Movie 1
    expect(new_movie instanceof Movie).toEqual true
    expect(new_movie.id).toEqual 0
    expect(new_movie.title).toEqual ''
    expect(new_movie.date_watched).toEqual null
    expect(new_movie.is_watched).toEqual false
    expect(new_movie.user_id).toEqual 1