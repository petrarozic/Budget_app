<div id="AddCategory" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"> Add new category </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form method="post"  action="<?php echo __SITE_URL; ?>/index.php?rt=category/addCategory" >
            <div class="form-group">
              <label for="type_of_transaction">  Type of category </label>
              <select class="form-control" id="type" name="type" >
                <option disabled="disabled" selected="selected" value="null"> Choose type </option>
                <option value="Expense"> Expense </option>
                <option value="Income"> Income </option>
              </select>
            </div>
            <div class="form-group">
              <label for="category_name">  Name of category </label>
              <input type="text" class="form-control" id="name" name="name" >
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"> Cancel </button>
        <button type="submit" class="btn btn-primary" name="SubmitButton" value="<?php echo $flag; ?>" id="NewCategory"> Submit </button>
      </div>
        </form>
    </div>
  </div>
</div>
