describe 'Song', ->
  Song = null

  beforeEach ->
    module 'astroApp'
    inject (_Song_)->
      Song = _Song_

  it 'should create a new song object', ->
    new_song = new Song 1
    expect(new_song instanceof Song).toEqual true
    expect(new_song.id).toEqual 0
    expect(new_song.title).toEqual ''
    expect(new_song.artist).toEqual ''
    expect(new_song.location).toEqual ''
    expect(new_song.user_id).toEqual 1