<?php require_once __SITE_PATH . '/view/_header.php'; ?>

<script language="JavaScript" type="text/javascript">
function checkDelete(){
    return confirm('By pressing OK you will delete selected transaction.');
}
</script>

<?php if ( isset($message) ) echo $message; ?>

<div class="panel">
  <table class="table">
    <thead>
      <tr>
        <th style="text-align:center; border:none;">  CATEGORY  </th>
        <th style="text-align:center; border:none;">  TITLE  </th>
        <th style="text-align:center; border:none;">   AMOUNT(HRK)  </th>
        <th style="text-align:center; border:none;">  DATE  </th>
        <th style="text-align:center; border:none;"> DESCRIPTION  </th>
        <th style="text-align:center; border:none;"> <!-- EDIT --> </th>
        <th style="text-align:center; border:none;"> <!-- REMOVE --> </th>
      </tr>
    </thead>
    <tbody>
      <?php
        if( empty($transactionsList) )
            echo '<tr><td colspan="7">There are currently no subscribed expenses/incomes.</td></tr>';
        else{
          foreach($transactionsList as $t){
            if( $flag === "expense"  ){
             echo '<tr><td>'.$t->category_name.'</td><td>'.$t->expense_name.'</td><td class="tred">'.$t->expense_value.'</td><td>'.$t->expense_date.'</td><td>'.$t->expense_description.'</td><td>';
             echo '<button class="IconButtonE" id="EditIcon" name="'.$flag.'" data-toggle="modal" data-target="#EditTransaction" value="'.$t->expense_id.'" ><i class="far fa-edit"></i></button></td>';
             echo '<td><form action="'.__SITE_URL.'/index.php?rt=transactions/removeExpense" method="post"> <input type="hidden" name="transaction" value="'.$t->expense_id.'"> <button type=submit class="IconButton" > <i class="far fa-trash-alt"></i> </button> </form> </td> </tr>';
            }
            else if($flag === "income"){
              echo '<tr><td>'.$t->category_name.'</td><td>'.$t->income_name.'</td><td class="tgreen">'.$t->income_value.'</td><td>'.$t->income_date.'</td><td>'.$t->income_description.'</td><td>';
              echo '<button class="IconButtonE" id="EditIcon" name="'.$flag.'" data-toggle="modal" data-target="#EditTransaction" value="'.$t->income_id.'" ><i class="far fa-edit"></i></button></td>';
              echo '<td> <form action="'.__SITE_URL.'/index.php?rt=transactions/removeIncome" method="post"> <input type="hidden" name="income" value="'.$t->income_id.'"> <button type=submit class="IconButton" >  <i class="far fa-trash-alt"> </i> </button> </form></td></tr>';
            }
            else /*if($flag === "transactions")*/{
              if($t->tr_type === 'e')
              echo '<tr><td>'.$t->category_name.'</td><td>'.$t->tr_name.'</td><td class="tred">'.$t->tr_value.'</td><td>'.$t->tr_date.'</td><td>'.$t->tr_description.'</td><td>';
              else
              echo '<tr><td>'.$t->category_name.'</td><td>'.$t->tr_name.'</td><td class="tgreen">'.$t->tr_value.'</td><td>'.$t->tr_date.'</td><td>'.$t->tr_description.'</td><td>';
              echo ' <button class="IconButtonE" id="EditIcon" name="'.$t->tr_type.'" data-toggle="modal" data-target="#EditTransaction"  value="'.$t->tr_id.'" > <i class="far fa-edit"></i> </button> </td>';
              echo '<td> <form action="'.__SITE_URL.'/index.php?rt=transactions/removeTransaction" method="post" onclick="return checkDelete()"> <input type="hidden" name="transaction" value="'.$t->tr_id.'"> <input type="hidden" name="type" value="'.$t->tr_type.'"> <button class="IconButton" type=submit >  <i class="far fa-trash-alt"></i> </button> </form> </td></tr>';
            }
          }
        }
      ?>
    </tbody>
  </table>
</div>

<div id="AddTransaction" class="modal" tabindex="-1" role="dialog">
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
            <select class="form-control" id="type" name="type" >
              <option disabled="disabled" selected="selected"> Choose type </option>
              <option <?php if( $flag == "expense") echo 'selected="selected"'; ?> > Expense </option>
              <option <?php if( $flag == "income") echo 'selected="selected"'; ?> > Income </option>
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

<div id="EditTransaction" class="modal" tabindex="-1" role="dialog">
<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title"> Edit transaction </h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
        <form method="post"  action="<?php echo __SITE_URL; ?>/index.php?rt=transactions/editTransaction" >
          <input id="TranId" name="TranId" type="hidden">
          <div class="form-group">
            <label for="type_of_transaction">  Type of transaction </label>
            <input type="text" class="form-control" id="type" name="type" readonly>
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
            <input type="number" class="form-control" id="amount" min="0" step="0.01" name="amount"> HRK
          </div>
          <div class="form-group">
            <label for="date">Date</label>
            <input type="date" class="form-control" id="date" name="date">
          </div>
          <div class="form-group">
            <label for="description"> Description: </label>
            <input type="text" class="form-control" id="description" name="description">
          </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal"> Cancel </button>
      <button type="submit" class="btn btn-primary" name="editButton" value="<?php echo $flag; ?>" id="EditTransaction"> Submit </button>
    </div>
      </form>
  </div>
</div>
</div>




<?php require_once __SITE_PATH . '/view/_footer.php'; ?>
