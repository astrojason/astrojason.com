<div class="modal-content">
  <div class="modal-header">
    <h5 class="modal-title">Editing {{ ::article.title }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close" dismiss-modal>
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
    <form ng-init="init()">
      <div class="form-group">
        <label for="title">Title</label>
        <input
          type="text"
          id="title"
          name="title"
          ng-model="form_article.title"
          class="form-control"
          placeholder="Article title" />
      </div>
      <div class="form-group">
        <label for="title">Url</label>
        <input
          type="text"
          id="url"
          name="url"
          ng-model="form_article.url"
          class="form-control"
          placeholder="Article url" />
      </div>
      <div class="form-group">
        <label for="categories">Category</label>
        <select
          class="form-control"
          id="categories"
          name="categories"
          multiple
          ng-options="cat.id as cat.name for cat in resources.categories"
          ng-model="form_article.categories">
        </select>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-primary">Save changes</button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal" dismiss-modal>Close</button>
  </div>
</div>