<div id="AddCategoryCRO" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"> Dodavanje nove kategorije </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form method="post"  action="<?php echo __SITE_URL; ?>/index.php?rt=category/addCategory" >
            <div class="form-group">
              <label for="type_of_transaction">  Tip transakcije  </label>
              <select class="form-control" id="type" name="type" >
                <option disabled="disabled" selected="selected" value="null"> Izaberite tip </option>
                <option> Trošak </option>
                <option> Prihod </option>
              </select>
            </div>
            <div class="form-group">
              <label for="category_name">  Ime kategorije </label>
              <input type="text" class="form-control" id="name" name="name" >
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"> Odustani </button>
        <button type="submit" class="btn btn-primary" name="SubmitButton" value="<?php echo $flag; ?>" id="NewCategory"> Spremi promjene </button>
      </div>
        </form>
    </div>
  </div>
</div>
