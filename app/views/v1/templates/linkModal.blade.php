<div
  class="modal fade"
  id="addLinkModal"
  astro-modal
  modal-visible="linkModalOpen">
  <div class="modal-dialog" ng-controller="ArticleController">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add a Link</h4>
      </div>
      <div class="modal-body">
        <link-form editing="true" link="newLink"></link-form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->