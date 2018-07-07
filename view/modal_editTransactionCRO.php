<div id="EditTransactionCRO" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"> Ažuriranje transakcije </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form method="post"  action="<?php echo __SITE_URL; ?>/index.php?rt=transactions/editTransaction" >
            <input id="TranId" name="TranId" type="hidden">
            <div class="form-group">
              <label for="type_of_transaction">  Tip transakcije </label>
              <input type="text" class="form-control" id="type" name="type" readonly>
            </div>
            <div class="form-group">
              <label for="transaction_name">  Ime transakcije </label>
              <input type="text" class="form-control" id="name" name="name" >
            </div>
            <div class="form-group">
              <label for="category"> Kategorija </label>
              <select class="form-control" id="category" name="category">
              </select>
            </div>
            <div class="form-group">
              <label for="amount"> Iznos </label>
              <input type="number" class="form-control" id="amount" min="0" step="0.01" name="amount"> HRK
            </div>
            <div class="form-group">
              <label for="date">Datum</label>
              <input type="date" class="form-control" id="date" name="date">
            </div>
            <div class="form-group">
              <label for="description"> Opis </label>
              <input type="text" class="form-control" id="description" name="description">
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"> Odustani </button>
        <button type="submit" class="btn btn-primary" name="editButton" value="<?php echo $flag; ?>" id="EditTransaction"> Spremi promjene </button>
      </div>
        </form>
    </div>
  </div>
</div>
