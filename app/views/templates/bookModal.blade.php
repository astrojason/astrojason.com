<div
  class="modal fade"
  id="addBookModal"
  astro-modal
  modal-visible="bookModalOpen">
  <div class="modal-dialog" ng-controller="BookController">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add a Book</h4>
      </div>
      <div class="modal-body">
        <book-form book="newBook" editing="true"></book-form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->