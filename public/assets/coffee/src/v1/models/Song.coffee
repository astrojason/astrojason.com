angular.module('astroApp').factory 'Song', [ (userId)->

  Song = (userId)->
    @.id = 0
    @.title = ''
    @.artist = ''
    @.location = ''
    @.user_id = userId

  Song
]
