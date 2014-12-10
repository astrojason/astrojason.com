window.app.filter 'phpbool', ->
  (input)->
    switch input
      when 1, '1', 'true', true
        true
      else
        false