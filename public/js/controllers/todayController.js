/**
 * Created by jasonsylvester on 4/2/14.
 */
astroApp.controller('todayController', ['$scope', '$http', function($scope, $http){
  $scope.items_to_migrate = [];

  $http.get('/api/links/today').success(function(data) {
    $scope.guitar = data.guitar;
    $scope.photography = data.photography;
    $scope.programming = data.programming;
    $scope.links = data.links;
    $scope.categories = data.categories;
  });
  $scope.postpone = function(link, index) {
    $scope.removeLink(link, index);
  }
  $scope.edit = function(link) {
    $scope.editing_link = link;
  }
  $scope.removeLink = function(link, index) {
    switch(link.category) {
      case('Guitar'):
        $scope.guitar.splice(index, 1);
        break;
      case('Photography'):
        $scope.photography.splice(index, 1);
        break;
      case('Programming'):
        $scope.programming.splice(index, 1);
        break;
      default:
        $scope.links.splice(index, 1);
        break;
    }
  }
  $scope.markAsRead = function(link, index) {
    $http.get('/api/link/' + link.id + '/read').success(function(data){
      if(data.success) {
        $scope.removeLink(link, index);
      } else {
        alert('There was a problem.')
      }
    });
  }
  $scope.save = function() {
    var save_data = {
      id: $scope.editing_link.id,
      name: $scope.editing_link.name,
      link: $scope.editing_link.link,
      category: $scope.editing_link.category,
      read: $scope.editing_link.read,
      instapaper_id: $scope.editing_link.instapaper_id
    };
    //TODO: If category is changed, remove this link
    $scope.saveLink(save_data);
  }
  $scope.migrate = function() {
    $http.get('/api/links').success(function(data){
      var existing_links = data.links;
      $http.get('/js/data/links.json').success(function(data){
        var links_to_add = data.links;
        var links_to_add_length = links_to_add.length;
        for(var i = 0; i < links_to_add_length; i++) {
          var this_link = data.links[i];
          delete this_link.id;
          delete this_link.updated_at;
          var found = false;
          for(var j = 0; j < existing_links.length; j++) {
            if(data.links[i].link == existing_links[j].link) {
              found = true;
              break;
            }
          }
          if(!found) {
            if(this_link.name) {
              console.log('Adding ' + this_link.name + ' to update array.');
              $scope.items_to_migrate.push(this_link);
            } else {
              console.log('Problem with: ');
              console.log(this_link);
            }
          }
        }
        $scope.migrateItem();
      });
    });
  }
  $scope.migrateItem = function() {
    var item_to_migrate = $scope.items_to_migrate.pop();
    $scope.saveLink(item_to_migrate);
    if($scope.items_to_migrate.length > 0) {
      setTimeout($scope.migrateItem(), 5000);
    } else {
      console.log('Migration complete');
    }
  }
  $scope.saveLink = function(save_data) {
    console.log('About to add: ');
    console.log(save_data);
    $http({method: 'PUT', url: '/api/link/', data: save_data}).success(function(data){
      if(data.success) {
        console.log('Link: ' + save_data.name + ' saved.');
        $('#linkModal').modal('hide');
      } else {
        console.log('Link: ' + save_data.name + ' not saved.');
      }
    });
  }
}]);