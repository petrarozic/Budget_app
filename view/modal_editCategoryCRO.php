<div id="EditCategoryCRO" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"> Ažuriranje kategorije </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form method="post"  action="<?php echo __SITE_URL; ?>/index.php?rt=category/editCategory" >
            <input id="TranId" name="TranId" type="hidden">
            <div class="form-group">
              <label for="type_of_category">  Tip kategorije </label>
              <input type="text" class="form-control" id="type" name="type" readonly>
            </div>
            <div class="form-group">
              <label for="transaction_name">  Ime kategorije </label>
              <input type="text" class="form-control" id="name" name="name" >
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"> Odustani </button>
        <button type="submit" class="btn btn-primary" name="editButtonCa" value="" id="EditCategoryB"> Spremi promjene </button>
      </div>
        </form>
    </div>
  </div>
</div>
