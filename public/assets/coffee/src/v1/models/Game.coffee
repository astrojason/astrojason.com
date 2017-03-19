angular.module('astroApp').factory 'Game', [(userId)->

  Game = (userId)->
    @.id = null
    @.title = ''
    @.platform = ''
    @.completed = false
    @.user_id = userId

  Game
]
