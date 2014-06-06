app.controller('nextBookController', ['$http', 'bookSvc', '$scope', function($http, bookSvc, $scope){
  var next = this;

  next.getNextBook = function() {
    $http.get('/api/book/next').success(function(data) {
      next.next_book = data.book;
    });
  };

  next.edit = function(book) {
    bookSvc.edit(book);
  };

  next.read = function(book) {
    bookSvc.markAsRead(book);
  };

  $scope.$on('BOOK_READ', function(){
    next.getNextBook();
  });

  next.getNextBook();
}]);

app.controller('editBookController', ['$http', 'bookSvc', '$scope', function($http, bookSvc, $scope){
  var editor = this;
  editor.book = bookSvc.create();

  $http.get('/api/book/categories').success(function(data){
    editor.categories = data.categories;
  });

  $scope.$on('EDITING_BOOK', function(){
    editor.book = bookSvc.get();
  });

  editor.save = function() {
    if(!editor.book.read) {
      editor.book.read = false;
    }
    if(!editor.book.seriesorder) {
      editor.book.seriesorder = 0;
    }
    $http({method: 'PUT', url: '/api/book/', data: editor.book}).success(function(data){
      if(data.success) {
        $('#bookModal').modal('hide');
        $('#book-error').addClass('hidden');
      } else {
        $('#book-error').html(data.error);
        $('#book-error').removeClass('hidden');
      }
    });
  };

  editor.edit = function(book) {
    bookSvc.edit(book);
  };

  editor.create = function() {
    bookSvc.edit(bookSvc.create());
  };
}]);


//astroApp.controller('editBookCtrl', function($scope, $http, $rootScope, bookSvc){

//
//  $scope.$on('EDITING_BOOK', function(response) {
//    $scope.book = bookSvc.get();
//  });
//
//  $scope.$on('READ_BOOK', function(response) {
//    $scope.book = bookSvc.get();
//    $http.get('/api/book/' + $scope.book.id + '/read').success(function(data){
//      $scope.book.read = true;
//      $rootScope.$broadcast('BOOK_READ', 'yep');
//    });
//  });
//

//
//  $scope.new = function() {
//    //TODO: Determine how to clear this out without clearing out the recommended book
//    bookSvc.set({});
//  };
//});
//
//astroApp.controller('allBooksListCtrl', function($scope, $http, bookSvc, $rootScope) {
//  $http.get('/api/books').success(function(data) {
//    $scope.books = data.books;
//    $scope.sortOrder = 'title';
//  });
//
//  $scope.edit = function(book){
//    bookSvc.set(book);
//    $rootScope.$broadcast('EDITING_BOOK', 'existing');
//  };
//
//  $scope.markAsRead = function(book) {
//    bookSvc.set(book);
//    $rootScope.$broadcast('READ_BOOK', 'existing');
//  };
//
//  $scope.delete = function(book) {
//    $http.get('/api/book/' + book.id + '/delete').success(function(data) {
//      if(data.success) {
//        $scope.books.splice($scope.books.indexOf(book), 1);
//      }
//    });
//  };
//});