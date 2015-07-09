<div
  class="modal fade"
  id="addMovieModal"
  astro-modal
  modal-visible="movieModalOpen">
  <div class="modal-dialog" ng-controller="MovieController">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add a Movie</h4>
      </div>
      <div class="modal-body">
        <movie-form movie="newMovie" editing="true"></movie-form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->