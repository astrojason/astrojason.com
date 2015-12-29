angular.module('astroApp').factory 'Movie', [ (userId)->

  Movie = (userId)->
    @.id = 0
    @.title = ''
    @.date_watched = null
    @.is_watched = false
    @.user_id = userId

  Movie
]
