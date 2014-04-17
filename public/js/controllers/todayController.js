/**
 * Created by jasonsylvester on 4/2/14.
 */
astroApp.controller('todayController', ['$scope', '$http', function($scope, $http){
  $scope.items_to_migrate = [];

  $http.get('/api/links/today').success(function(data) {
    $scope.athome = data.athome;
    $scope.cooking = data.cooking;
    $scope.exercise = data.exercise;
    $scope.forreview = data.forreview;
    $scope.forthehouse = data.forthehouse;
    $scope.guitar = data.guitar;
    $scope.groups = data.groups;
    $scope.photography = data.photography;
    $scope.projects = data.projects;
    $scope.programming = data.programming;
    $scope.wishlist = data.wishlist;
    $scope.wordpress = data.wordpress;
    $scope.unread = data.links;
    $scope.categories = data.categories;
  });
  $scope.postpone = function(link, index) {
    $scope.removeLink(link.category, index);
  }
  $scope.edit = function(link, index) {
    $scope.editing_link = link;
    $scope.editing_index = index;
    $scope.editing_category = link.category;
  }
  $scope.removeLink = function(category, index) {
    var category_name = category.toLowerCase().replace(/ /g, '');
    $scope[category_name].splice(index, 1);
  }
  $scope.refreshCategory = function(category) {
    $http.get('/api/links/' + category + '/1').success(function(data){
      var category_name = category.toLowerCase().replace(/ /g, '');
      console.log(category_name);
      $scope[category_name] = data.links;
    });
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
    $scope.saveLink(save_data);
    if($scope.editing_category != $scope.editing_link.category) {
      $scope.removeLink($scope.editing_category, $scope.editing_index);
    }
  }
  $scope.migrate = function() {
    $http.get('/api/books').success(function(data){
      var existing_books = data.books;
      $http.get('/js/data/books.json').success(function(data){
        var books_to_add = data.books;
        var books_to_add_length = books_to_add.length;
        for(var i = 0; i < books_to_add_length; i++) {
          var this_book = books_to_add[i];
          delete this_book.id;
          delete this_book.updated_at;
          if(!this_book.goodreads_id) {
            delete this_book.goodreads_id;
          }
          var found = false;
          var existing_books_length = existing_books.length;
          for(var j = 0; j < existing_books_length; j++) {
            var existing_book = existing_books[j];
            if((this_book.title === existing_book.title) && (this_book.author_lname === existing_book.author_lname)) {
              found = true;
              break;
            }
          }
          if(!found) {
            if(this_book.title) {
              console.log('Adding ' + this_book.title + ' to update array.');
              $scope.items_to_migrate.push(this_book);
            } else {
              console.log('Problem with: ');
              console.log(this_book);
            }
          }
        }
        $scope.migrateItem();
      });
    });
  }
  $scope.migrateItem = function() {
    var item_to_migrate = $scope.items_to_migrate.pop();
    if(item_to_migrate) {
      $scope.saveBook(item_to_migrate);
    }
    if($scope.items_to_migrate.length > 0) {
      setTimeout(function(){$scope.migrateItem()}, 5000);
    } else {
      console.log('Migration complete');
    }
  }
  $scope.saveLink = function(save_data) {
    $http({method: 'PUT', url: '/api/link/', data: save_data}).success(function(data){
      if(data.success) {
        $('#linkModal').modal('hide');
      } else {
        console.log('Link: ' + save_data.name + ' not saved.');
      }
    });
  }
  $scope.saveBook = function(save_data) {
    $http({method: 'PUT', url: '/api/book/', data: save_data}).success(function(data){
      if(data.success) {
        $('#bookModal').modal('hide');
      } else {
        console.log('Book: ' + save_data.title + ' not saved.');
      }
    });
  }
}]);