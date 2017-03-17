<!doctype html>
<html lang="en" ng-app="astroApp">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome :: astrojason.com</title>
    <script src="https://use.fontawesome.com/9d7ddf6f44.js"></script>
    <link type="text/css" href="assets/sass/build/base-v2.css" rel="stylesheet" />
  </head>
  <body>
    <div class="d-flex flex-row-reverse">
      <div class="p-2">Logged in as: <em>Jason</em></div>
    </div>
    <div class="container-fluid" ng-controller="DashboardController" ng-init="init()">
      <div class="row">
        <div class="col-4">
          <table class="table table-sm table-striped">
            <thead class="thead-inverse">
              <tr>
                <th>Today's Links <a class="float-right mr-1"><small>manage</small></a></th>
              </tr>
            </thead>
            @include('partial.articles')
          </table>
        </div>
        <div class="col-4">
          <table class="table table-sm table-striped">
            <thead class="thead-inverse">
              <tr>
                <th>Habit Chain</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Read: (226 days)</td>
              </tr>
              <tr>
                <td>Practice Guitar: (30 days) <span class="badge badge-success float-right">Completed Today</span></td>
              </tr>
              <tr>
                <td>Mobility WOD: (9 days, missed 1 in the last 7 days)</td>
              </tr>
            </tbody>
          </table>
          @include('partial.tasks')
        </div>
        <div class="col-4">
          <table class="table table-sm table-striped">
            <thead class="thead-inverse">
              <tr>
                <th colspan="2">Practice</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="table-inverse">Core</td>
                <td>Two-finger legato</td>
              </tr>
              <tr>
                <td class="table-inverse">Awareness</td>
                <td>Notes on the fretboard</td>
              </tr>
              <tr>
                <td class="table-inverse">Creative</td>
                <td>Improv</td>
              </tr>
            </tbody>
          </table>
          <table class="table table-sm table-striped">
            <thead class="thead-inverse">
              <tr>
                <th colspan="2">Songs</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="table-inverse">Review</td>
                <td>Stray Cat Strut - Stray Cats</td>
              </tr>
              <tr>
                <td class="table-inverse">Review</td>
                <td>Simple Man - Lynyrd Skynyrd</td>
              </tr>
              <tr>
                <td class="table-inverse">New</td>
                <td>
                  Mama I'm Coming Home - Ozzy Osbourne
                  <span class="badge badge-primary float-right">Project</span>
                </td>
              </tr>
            </tbody>
          </table>

          <table class="table table-sm table-striped">
            <thead class="thead-inverse">
              <tr>
                <th colspan="2">Books</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="table-inverse">Current</td>
                <td>The Crossing - Michael Connelley</td>
              </tr>
              <tr>
                <td class="table-inverse">Next</td>
                <td>Fool Moon - Jim Butcher</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    @include('partial.js')
  </body>
</html>