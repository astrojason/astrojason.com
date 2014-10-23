/**
 * Created by jasonsylvester on 5/29/14.
 */
app.controller('todaysLinksController', ['$http', 'linkSvc', '$scope', function($http, linkSvc, $scope){
  var todays = this;
  todays.editing = null;
  todays.init = true;
  $http.get('/api/links/today').success(function(data){
    todays.success = data.success;
    if(todays.success) {
      todays.athome = data.athome;
      todays.cooking = data.cooking;
      todays.exercise = data.exercise;
      todays.forreview = data.forreview;
      todays.forthehouse = data.forthehouse;
      todays.guitar = data.guitar;
      todays.groups = data.groups;
      todays.photography = data.photography;
      todays.projects = data.projects;
      todays.programming = data.programming;
      todays.wishlist = data.wishlist;
      todays.wordpress = data.wordpress;
      todays.unread = data.links;
      todays.daily = data.daily;
      todays.hockeyexercise = data.hockeyexercise;
      todays.read = data.total_read;
      todays.added = data.total_added;
    } else {
      todays.error = data.error;
    }
    todays.init = false;
  });

  todays.refreshCategory = function(category, number) {
    $http.get('/api/links/' + category + '/' + number).success(function(data){
      var category_name = category.toLowerCase().replace(/ /g, '');
      todays[category_name] = data.links;
    });
  };

  todays.postpone = function(link, index) {
    linkSvc.postpone(link, index, todays);
  };

  todays.markAsRead = function(link, index) {
    todays.editing = { link: link, index: index };
    linkSvc.markAsRead(link);
  };

  todays.edit = function(link, index) {
    todays.editing = { link: link, index: index, category: link.category };
    linkSvc.edit(link);
  };

  $scope.$on('LINK_READ', function(){
    if(todays.editing != null) {
      todays.read++;
      linkSvc.remove(todays.editing.link.category, todays.editing.index, todays);
      todays.editing = null;
    }
  });

  $scope.$on('LINK_EDITED', function(){
    if(todays.editing != null) {
      if(todays.editing.link.category != todays.editing.category) {
        linkSvc.remove(todays.editing.category, todays.editing.index, todays);
      }
    }
    todays.editing = null;
  });
}]);

app.controller('editLinkController', ['$http', 'linkSvc', '$scope', '$rootScope', function($http, linkSvc, $scope, $rootScope){
  var editor = this;

  $http.get('/api/link/categories').success(function(data){
    editor.categories = data.categories;
  });

  $scope.$on('EDITING_LINK', function(){
    editor.link = linkSvc.get();
  });

  editor.create = function() {
    linkSvc.edit(linkSvc.create());
  };

  editor.save = function() {
    $http({method: 'PUT', url: '/api/link/', data: editor.link}).success(function(data){
      if(data.success) {
        $('#linkModal').modal('hide');
        $rootScope.$broadcast('LINK_EDITED');
      } else {
        $('#link-error').html(data.error);
        $('#link-error').show();
      }
    });
  };
}]);

app.controller('allLinksController', ['$http', 'linkSvc', '$scope', function($http, linkSvc, $scope, $timeout){
  var all = this;
  $scope.$watch('filter', function(){
    if($scope.filter.length > 2) {
      $http.get('/api/links/' + $scope.filter).success(function(data){
        all.links = data.links;
      });
    }
  });

  all.edit = function(link, index){
    all.editing = { link: link, index: index};
    linkSvc.edit(link);
  };

  all.markAsRead = function(link, index) {
    all.editing = { link: link, index: index};
    linkSvc.markAsRead(link);
  };

  $scope.$on('LINK_READ', function(){
    if(all.editing != null) {
      all.editing.link.read = true;
      all.editing = null;
    }
  });
}]);