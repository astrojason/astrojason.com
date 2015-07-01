<div
  class="modal fade"
  id="addGameModal"
  astro-modal
  modal-visible="gameModalOpen">
  <div class="modal-dialog" ng-controller="GameController">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add a Game</h4>
      </div>
      <div class="modal-body">
        <game-form game="newGame" editing="true"></game-form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->