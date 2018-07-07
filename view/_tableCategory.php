<div class="plusCategory">
  <button class="AddButton" type="submit" name="AddCategory" data-toggle="modal" data-target="#AddCategory" >
      <i class="fas fa-plus"></i>
      <span style="font-size: 0.9em">Category</span>
  </button>
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-6 pull-left">
      <span class="nameTable">INCOME CATEGORIES</span>
      <div class="panel" style="margin:0px">
        <table class="table">
          <thead>
            <tr>
              <th class="sortable" style="text-align:center; border:none;">  NAME  </th>
              <th style="text-align:center; border:none;"> <!-- EDIT --> </th>
              <th style="text-align:center; border:none;"> <!-- REMOVE --> </th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
    </div>

    <div class="col-6 pull-right">
      <span class="nameTable">EXPENSE CATEGORIES</span>
      <div class="panel" style="margin:0px">
        <table class="table">
          <thead>
            <tr>
              <th class="sortable" style="text-align:center; border:none;">  NAME  </th>
              <th style="text-align:center; border:none;"> <!-- EDIT --> </th>
              <th style="text-align:center; border:none;"> <!-- REMOVE --> </th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php require_once __SITE_PATH . '/view/modal_addCategory.php'; ?>
