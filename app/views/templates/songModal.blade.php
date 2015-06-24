<div
  class="modal fade"
  id="addSongModal"
  astro-modal
  modal-visible="songModalOpen">
  <div class="modal-dialog" ng-controller="SongController">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add a Song</h4>
      </div>
      <div class="modal-body">
        <song-form song="newSong" editing="true"></song-form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->