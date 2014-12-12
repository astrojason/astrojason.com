window.app.filter 'boolparse', ->
  (input)->
    switch input
      when 1, '1', 'true', true
        true
      else
        false