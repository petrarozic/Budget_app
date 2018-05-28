<?php require_once __SITE_PATH . '/view/_header.php'; ?>

<table class="table">
  <thead class="thead-light">
    <tr>
      <th>CATEGORY</th>
      <th>TITLE</th>
      <th>AMOUNT(HRK)</th>
      <th>DATE</th>
      <th>DESCRIPTION</th>
      <th>erase/change</th>
    </tr>
  </thead>
  <tbody>
    <?php

    //

    if( empty($transactionsList) )
        echo '<tr><td colspan="6">There are currently no subscribed expenses/incomes.</td></tr>';
    else{
      foreach($transactionsList as $t){
        if( $flag === "expense"  ){
         echo '<tr><td>'.$t->category_name.'</td><td>'.$t->expense_name.'</td><td>'.$t->expense_value.'</td><td>'.$t->expense_date.'</td><td>'.$t->expense_description.'</td><td>';
         echo '<button type="submit">Edit</button></td></tr>';
        }
        else{
          echo '<tr><td>'.$t->category_name.'</td><td>'.$t->income_name.'</td><td>'.$t->income_value.'</td><td>'.$t->income_date.'</td><td>'.$t->income_description.'</td><td>';
          echo '<button type="submit">Edit</button></td></tr>';
        }
      }
    }
    ?>

  </tbody>
</table>

<?php require_once __SITE_PATH . '/view/_footer.php'; ?>
