/**
 * Created by jasonsylvester on 4/15/14.
 */

if (!($ = window.jQuery)) { // typeof jQuery=='undefined' works too
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
    success: function(data) {
      if(data.success) {
        alert('Added');
      } else {
        alert('There was a problem');
      }
    }
   });
}