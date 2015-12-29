angular.module('astroApp').factory 'Game', [(userId)->

  Game = (userId)->
    @.id = 0
    @.title = ''
    @.platform = ''
    @.completed = false
    @.user_id = userId

  Game
]
