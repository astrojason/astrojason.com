/**
 * Created by jasonsylvester on 5/29/14.
 */
var app = angular.module('astroApp', []);

app.factory('linkSvc', ['$http', '$rootScope', function($http, $rootScope){
  var editing_link = {};
  return {
    remove: function(category, index, controller) {
      var category_name = category.toLowerCase().replace(/ /g, '');
      controller[category_name].splice(index, 1);
    },

    postpone: function(link, index, controller) {
      this.remove(link.category, index, controller)
    },

    get: function() {
      return editing_link;
    },

    set: function(link) {
      editing_link = link;
    },

    new: function() {
      editing_link = {id: 0, name: '', link: '', category: 'Unread', read: 0};
      return editing_link;
    },

    markAsRead: function(link) {
      $http.get('/api/link/' + link.id + '/read').success(function(data){
        if(data.success) {
          $rootScope.$broadcast('LINK_READ');
        }
      });
    },

    edit: function(link) {
      this.set(link);
      $rootScope.$broadcast('EDITING_LINK');
    }
  }
}]);