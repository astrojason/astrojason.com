<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <script src="https://use.fontawesome.com/9d7ddf6f44.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/react-select/dist/react-select.css">
    <link type="text/css" href="assets/sass/build/v2/base.css" rel="stylesheet" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome :: astrojason.com</title>
  </head>
  <body>
    <div id="user-root" data-user="<%% Auth::user() %%>"></div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-4 daily-articles" id="articles-root"></div>
        <div class="col-4">
          <div id="chain-root"></div>
          <div id="tasks-root" class="mt-2"></div>
        </div>
        <div class="col-4">
          <div id="practice-root"></div>
          <div id="songs-root" class="mt-2"></div>
          <div id="books-root" class="mt-2"></div>
        </div>
      </div>
    </div>
    <script type="text/javascript" src="assets/js/v2/app.js"></script>
  </body>
</html>