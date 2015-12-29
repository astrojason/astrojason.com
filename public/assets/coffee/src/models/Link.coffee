angular.module('astroApp').factory 'Link', [(userId)->

  Link = (userId)->
    @.id = 0
    @.name = ''
    @.link = ''
    @.category = 'Unread'
    @.read = false
    @.instapaper_id = ''
    @.user_id = userId
    @.deleting = false

  Link
]