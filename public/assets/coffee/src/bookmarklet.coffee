container = null

window.addEventListener 'message', (e)->
  container.remove()

addTheLink = ->
  title = document.title
  link = location.href
  containerStyle =
    position: 'absolute'
    top: 0
    bottom: 0
    left: 0
    right: 0
    zIndex: 2147483638
    backgroundColor: 'rgba(0,0,0,0.6)'
  frameStyle =
    backgroundColor: '#FFF'
    width: '400px'
    marginLeft: 'calc(50% - 200px)'
    marginTop: '100px'
    padding: '10px'
    height: '325px'
  container = $('<div />').attr('id', 'readlaterwrapper').css containerStyle
  container.on 'click', ->
    container.remove()
  iframe = $('<iframe />').attr 'src', "http://astrojason.com/readlater?title=" + title + "&link=" + link
  iframe.css frameStyle
  container.append iframe
  $('body').append container
if not $ == window.jQuery
  script = document.createElement 'script'
  script.src = '//code.jquery.com/jquery-1.11.1.min.js'
  script.onload addTheLink()
  document.body.appendChild script
else
  addTheLink()
