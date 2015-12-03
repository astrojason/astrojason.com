angular.module('astroApp').factory 'Book', [->

  Book = (userId)->
    @.id = 0
    @.title = ''
    @.author_fname = ''
    @.author_lname = ''
    @.series = ''
    @.series_order = 0
    @.category = 'To Read'
    @.owned = false
    @.is_read = false
    @.user_id = userId

  Book
]