if (!($ = window.jQuery)) {
  script = document.createElement( 'script' );
  script.src = 'http://www.astrojason.com/js/libs/jquery.min.js';
  script.onload=addTheLink;
  document.body.appendChild(script);
}
else {
  addTheLink();
}

function addTheLink() {
  var save_data = {
    user_id: 1,
    link: location.href,
    name: document.title
  };
  $.ajax({
    url: 'http://www.astrojason.com/api/link/add',
    data: save_data,
    type: 'PUT',
    headers: {'X-Requested-With': 'XMLHttpRequest'},
    success: function(data) {
      if(data.success) {
        alert('Added');
      } else {
        alert('There was a problem.\n' + data.error);
      }
    }
   });
}