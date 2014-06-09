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

app.controller('allBooksController', ['$http', 'bookSvc', '$scope', function($http, bookSvc, $scope){
  var bookCtrl = this;
  bookCtrl.editing = null;

  $http.get('/api/books').success(function(data) {
    bookCtrl.books = data.books;
    bookCtrl.sortOrder = 'title';
  });

  bookCtrl.edit = function(book) {
    bookCtrl.editing = book;
    bookSvc.edit(book);
  };

  bookCtrl.markAsRead = function(book) {
    bookCtrl.editing = book;
    bookSvc.markAsRead(book);
  };

  bookCtrl.delete = function(book) {
    $http.get('/api/book/' + book.id + '/delete').success(function(data) {
      if(data.success) {
        bookCtrl.books.splice(bookCtrl.books.indexOf(book), 1);
      }
    });
  };

  $scope.$on('BOOK_READ', function(){
    if(bookCtrl.editing != null) {
      bookCtrl.editing.read = true;
      bookCtrl.editing = null;
    }
  });
}]);