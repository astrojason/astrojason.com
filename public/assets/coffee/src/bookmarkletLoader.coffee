if window.location.protocol != 'https:'
  jsCode = document.createElement 'script'
  jsCode.setAttribute 'src', 'http://astrojason.com/assets/js/bookmarklet.min.js'
  document.body.appendChild jsCode
else
  alert 'Cannot load via https'