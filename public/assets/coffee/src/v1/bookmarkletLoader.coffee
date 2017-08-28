if window.location.protocol != 'https:'
  jsCode = document.createElement 'script'
  jsCode.setAttribute 'src', '{{BASEURL}}/assets/js/v1/bookmarklet.min.js'
  document.body.appendChild jsCode
else
  alert 'Cannot load via https'