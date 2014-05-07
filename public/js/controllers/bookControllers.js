/**
 * Created by jasonsylvester on 4/19/14.
 */
astroApp.controller('nextBookCtrl', function($scope, $http, $rootScope, bookSvc) {
  $scope.getNextBook = function() {
    $http.get('/api/book/next').success(function(data) {
      $scope.book = data.book;
      $scope.book_categories = data.categories;
    });
  };

  $scope.getNextBook();

  $scope.edit = function(book) {
    bookSvc.set(book);
    $rootScope.$broadcast('EDITING_BOOK', 'existing');
  };

  $scope.read = function(book) {
    bookSvc.set(book);
    $rootScope.$broadcast('READ_BOOK', 'existing');
  };

  $scope.$on('BOOK_READ', function(response) {
    $scope.getNextBook();
  });
});

astroApp.controller('editBookCtrl', function($scope, $http, $rootScope, bookSvc){
  $http.get('/api/book/categories').success(function(data){
    $scope.categories = data.categories;
  });

  $scope.$on('EDITING_BOOK', function(response) {
    $scope.book = bookSvc.get();
  });

  $scope.$on('READ_BOOK', function(response) {
    $scope.book = bookSvc.get();
    $http.get('/api/book/' + $scope.book.id + '/read').success(function(data){
      $scope.book.read = true;
      $rootScope.$broadcast('BOOK_READ', 'yep');
    });
  });

  $scope.save = function() {
    $http({method: 'PUT', url: '/api/book/', data: $scope.book}).success(function(data){
      if(data.success) {
        $('#bookModal').modal('hide');
        $('#book-error').addClass('hidden');
      } else {
        $('#book-error').html(data.error);
        $('#book-error').removeClass('hidden');
      }
    });
  };

  $scope.new = function() {
    $scope.book.id = 0;
    $scope.book.title = '';
    $scope.book.author_fname = '';
    $scope.book.author_lname = '';
    $scope.book.category = 'Fiction';
    $scope.book.series = '';
    $scope.book.seriesorder = '';
  };
});

astroApp.controller('allBooksListCtrl', function($scope, $http, bookSvc, $rootScope) {
  $http.get('/api/books').success(function(data) {
    $scope.books = data.books;
    $scope.sortOrder = 'title';
  });

  $scope.edit = function(book){
    bookSvc.set(book);
    $rootScope.$broadcast('EDITING_BOOK', 'existing');
  };

  $scope.markAsRead = function(book) {
    bookSvc.set(book);
    $rootScope.$broadcast('READ_BOOK', 'existing');
  };

  $scope.delete = function(book) {
    $http.get('/api/book/' + book.id + '/delete').success(function(data) {
      if(data.success) {
        $scope.books.splice($scope.books.indexOf(book), 1);
      }
    });
  };
});