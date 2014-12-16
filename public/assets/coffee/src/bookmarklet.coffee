console.log 'Pulled the bookmarklet'
if not $ = window.jQuery
  # TODO: Load jQuery
  console.log 'Need to load jQuery'
  script = document.createElement 'script'
  script.src = '//code.jquery.com/jquery-1.11.1.min.js'
  script.onload addTheLink
  document.body.appendChild script

addTheLink = ->
  # TODO: Build the bookmarklet
  console.log 'Adding the link'
  title = document.title
  link = location.href
  containerStyle =
    position: 'absolute'
    top: 0
    bottom: 0
    left: 0
    right: 0
    zIndex: 2147483638
    backgroundColor: rgba(0,0,0,0.6)
  console.log title, link
  container = $('<div />').css containerStyle
  $(document).append(container)