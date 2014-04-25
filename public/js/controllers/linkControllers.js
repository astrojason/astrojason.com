/**
 * Created by jasonsylvester on 4/1/14.
 */

astroApp.controller('searchLinksCtrl', function($scope, $http, $timeout, linkSvc, $rootScope) {
  $scope.$watch('filter', function(){
    if($scope.filter) {
      $timeout.cancel($scope.search);
      $scope.search = $timeout($scope.find, 500);
    }
  });

  $scope.find = function(){
    if($scope.filter) {
      $http.get('/api/links/' + $scope.filter).success(function(data) {
        $scope.links = data.links;
      });
    }
  };

  $scope.edit = function(link){
    linkSvc.set(link);
    $rootScope.$broadcast('EDITING_LINK', 'existing');
  };

  $scope.markAsRead = function(link, index) {
    linkSvc.set(link);
    $rootScope.$broadcast('READ_LINK', 'existing');
  };
});

astroApp.controller('todaysLinksListCtrl', function($scope, $http, $rootScope, linkSvc) {
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
  });

  $scope.postpone = function(link, index) {
    $scope.removeLink(link.category, index);
  };

  $scope.refreshCategory = function(category) {
    $http.get('/api/links/' + category + '/1').success(function(data){
      var category_name = category.toLowerCase().replace(/ /g, '');
      console.log(category_name);
      $scope[category_name] = data.links;
    });
  };

  $scope.markAsRead = function(link, index) {
    $scope.editing_index = index;
    $scope.editing_link = link;
    linkSvc.set(link);
    $rootScope.$broadcast('READ_LINK', 'existing');
  };

  $scope.edit = function(link, index){
    $scope.editing_index = index;
    $scope.editing_link = link;
    $scope.editing_category = link.category;
    linkSvc.set(link);
    $rootScope.$broadcast('EDITING_LINK', 'existing');
  };

  $scope.removeLink = function(category, index) {
    var category_name = category.toLowerCase().replace(/ /g, '');
    $scope[category_name].splice(index, 1);
  };

  $scope.$on('LINK_EDITED', function(response){
    var edited_link = linkSvc.get();
    if($scope.editing_link === edited_link && $scope.editing_category != edited_link.category) {
      $scope.removeLink($scope.editing_category, $scope.editing_index);
    }
  });

  $scope.$on('LINK_READ', function(response){
    var edited_link = linkSvc.get();
    if($scope.editing_link === edited_link) {
      $scope.removeLink(edited_link.category, $scope.editing_index);
    }
  });
});

astroApp.controller('editLinkCtrl', function($scope, $http, linkSvc, $rootScope) {
  $http.get('/api/link/categories').success(function(data){
    $scope.link_categories = data.categories;
  });

  $scope.$on('EDITING_LINK', function(response) {
    $scope.link = linkSvc.get();
    $scope.editing_category = $scope.link.category;
  });

  $scope.$on('READ_LINK', function(response) {
    $scope.link = linkSvc.get();
    $http.get('/api/link/' + $scope.link.id + '/read').success(function(data){
      if(data.success) {
        $scope.link.read = true;
        $rootScope.$broadcast('LINK_READ', 'existing');
      }
    });
  });

  $scope.save = function() {
    $http({method: 'PUT', url: '/api/link/', data: $scope.link}).success(function(data){
      if(data.success) {
        $('#linkModal').modal('hide');
        $rootScope.$broadcast('LINK_EDITED', 'existing');
      } else {
        console.log('Link: ' + $scope.link.name + ' not saved.');
        // TODO: Give a reason for the link to not be saved
      }
    });
  };

  $scope.new = function() {
    linkSvc.set({name: '', link: '', category: 'Unread', instapaper_id: null, read: 0});
    $rootScope.$broadcast('EDITING_LINK', 'new');
  };
});

astroApp.controller('allLinksListCtrl', function($scope, $http, linkSvc, $rootScope) {
  $http.get('/api/links').success(function(data) {
    $scope.links = data.links;
  });

  $scope.edit = function(link){
    linkSvc.set(link);
    $rootScope.$broadcast('EDITING_LINK', 'existing');
  };

  $scope.markAsRead = function(link, index) {
    linkSvc.set(link);
    $rootScope.$broadcast('READ_LINK', 'existing');
  };
});