<div id="AddTransactionModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"> Add new transaction </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form method="post"  action="<?php echo __SITE_URL; ?>/index.php?rt=transactions/addTransaction" >
            <div class="form-group">
              <label for="type_of_transaction">  Type of transaction </label>
              <select class="form-control" id="CateType" name="type" >
                <option selected="selected" value="null" disabled="disabled" > Choose type </option>
                <option value="Expense">Expense</option>
                <option value="Income">Income</option>
              </select>
            </div>
            <div class="form-group">
              <label for="transaction_name">  Name of transaction </label>
              <input type="text" class="form-control" id="name" name="name" >
            </div>
            <div class="form-group">
              <label for="category"> Category </label>
              <select class="form-control" id="category" name="category">
              </select>
            </div>
            <div class="form-group">
              <label for="amount"> Amount </label>
              <input type="number" class="form-control" id="amount" value="0" min="0" step="0.01" name="amount"> HRK
            </div>
            <div class="form-group">
              <label for="date">Date</label>
              <input type="date" class="form-control" id="date" name="date">
            </div>
            <div class="form-group">
              <label for="description"> Description: </label>
              <input type="text" class="form-control" id="description" name="description">
            </div>
            <div class="form-group">
              <label for="repeating"> Repeating: </label>
              <input type="number" class="form-control" value="1" min="1" defaul="1" id="repeating" name="repeating">
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"> Cancel </button>
        <button type="submit" class="btn btn-primary" name="SubmitButton" value="<?php echo $flag; ?>" id="NewTransaction"> Submit </button>
      </div>
        </form>
    </div>
  </div>
</div>
