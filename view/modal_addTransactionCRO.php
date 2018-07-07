<div id="AddTransactionModalCRO" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"> Dodavanje nove transakcije </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form method="post"  action="<?php echo __SITE_URL; ?>/index.php?rt=transactions/addTransaction" >
            <div class="form-group">
              <label for="type_of_transaction">  Tip transakcije </label>
              <select class="form-control" id="CateType" name="type" >
                <option selected="selected" value="null" disabled="disabled" > Izaberite tip </option>
                <option value="Expense">Trošak</option>
                <option value="Income">Prihod</option>
              </select>
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
              <input type="number" class="form-control" id="amount" value="0" min="0" step="0.01" name="amount"> HRK
            </div>
            <div class="form-group">
              <label for="date">Datum</label>
              <input type="date" class="form-control" id="date" name="date">
            </div>
            <div class="form-group">
              <label for="description"> Opis </label>
              <input type="text" class="form-control" id="description" name="description">
            </div>
            <div class="form-group">
              <label for="repeating"> Ponavljanje </label>
              <input type="number" class="form-control" value="1" min="1" defaul="1" id="repeating" name="repeating">
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"> Odustani </button>
        <button type="submit" class="btn btn-primary" name="SubmitButton" value="<?php echo $flag; ?>" id="NewTransaction"> Spremi promjene </button>
      </div>
        </form>
    </div>
  </div>
</div>
