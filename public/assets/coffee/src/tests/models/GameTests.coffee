describe 'Game', ->
  Game = null

  beforeEach ->
    module 'astroApp'
    inject (_Game_)->
      Game = _Game_

  it 'should create a new game object', ->
    new_game = new Game 1
    expect(new_game instanceof Game).toEqual true
    expect(new_game.id).toEqual 0
    expect(new_game.title).toEqual ''
    expect(new_game.platform).toEqual ''
    expect(new_game.completed).toEqual false
    expect(new_game.user_id).toEqual 1