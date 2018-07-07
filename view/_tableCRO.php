
<script language="JavaScript" type="text/javascript">
  function checkDelete(){
      return confirm('Klikom na OK izbrisati ćete odabranu transakciju.');
  }
</script>

<?php //if ( isset($message) ) echo $message; ?>
<script>

      console.log("u scriptu");
      var fl = <?php if ( isset($message)) echo json_encode($_SESSION['flag']); else echo "2";?>;
      var mess = <?php if ( isset($message)) echo json_encode($message); else echo "undefined";?>;

        if(fl.toString() === '0')
            $.notify(mess, "error");

        else if(fl.toString() === '1')
            $.notify(mess, "success");


</script>


<div class="panel">
  <div class="table-responsive">
    <table class="table">
    <thead>
      <tr>
        <th class="removable sortable" style="text-align:center; border:none;">  KATEGORIJA  </th>
        <th class="sortable" style="text-align:center; border:none;">  NASLOV  </th>
        <th class="sortable" style="text-align:center; border:none;">IZNOS (HRK)</th>
        <th class="Removable2 sortable" style="text-align:center; border:none;">  DATUM  </th>
        <th class="removable sortable" style="text-align:center; border:none;"> OPIS  </th>
        <th style="text-align:center; border:none;"> <!-- EDIT --> </th>
        <th style="text-align:center; border:none;"> <!-- REMOVE --> </th>
      </tr>
    </thead>
    <tbody>
      <?php
        if( empty($transactionsList) )
            echo '<tr><td colspan="7">Trenutačno nemate ni jednu transakciju.</td></tr>';
        else{
          foreach($transactionsList as $t){
            if( $flag === "expense"  ){
             echo '<tr><td class="removable">'.$t->category_name.'</td><td>'.$t->expense_name.'</td><td class="tred">'.$t->expense_value.'</td><td class="Removable2">'.$t->expense_date.'</td><td class="removable">'.$t->expense_description.'</td><td>';
             echo '<button class="IconButtonE" id="EditIcon" name="'.$flag.'" data-toggle="modal" data-target="#EditTransactionCRO" value="'.$t->expense_id.'" ><i class="far fa-edit"></i></button></td>';
             echo '<td><form action="'.__SITE_URL.'/index.php?rt=transactions/removeExpense" method="post"  onclick="return checkDelete()"> <input type="hidden" name="transaction" value="'.$t->expense_id.'"> <button type=submit class="IconButton" > <i class="far fa-trash-alt"></i> </button> </form> </td> </tr>';
            }
            else if($flag === "income"){
              echo '<tr><td class="removable">'.$t->category_name.'</td><td>'.$t->income_name.'</td><td class="tgreen">'.$t->income_value.'</td><td class="Removable2">'.$t->income_date.'</td><td class="removable" >'.$t->income_description.'</td><td>';
              echo '<button class="IconButtonE" id="EditIcon" name="'.$flag.'" data-toggle="modal" data-target="#EditTransactionCRO" value="'.$t->income_id.'" ><i class="far fa-edit"></i></button></td>';
              echo '<td> <form action="'.__SITE_URL.'/index.php?rt=transactions/removeIncome" method="post"  onclick="return checkDelete()"> <input type="hidden" name="income" value="'.$t->income_id.'"> <button type=submit class="IconButton" >  <i class="far fa-trash-alt"> </i> </button> </form></td></tr>';
            }
            else /*if($flag === "transactions")*/{
              if($t->tr_type === 'e')
              echo '<tr><td class="removable" >'.$t->category_name.'</td><td>'.$t->tr_name.'</td><td class="tred">'.$t->tr_value.'</td><td class="Removable2">'.$t->tr_date.'</td><td class="removable">'.$t->tr_description.'</td><td>';
              else
              echo '<tr><td class="removable" >'.$t->category_name.'</td><td>'.$t->tr_name.'</td><td class="tgreen">'.$t->tr_value.'</td><td class="Removable2">'.$t->tr_date.'</td><td class="removable">'.$t->tr_description.'</td><td>';
              echo ' <button class="IconButtonE" id="EditIcon" name="'.$t->tr_type.'" data-toggle="modal" data-target="#EditTransactionCRO"  value="'.$t->tr_id.'" > <i class="far fa-edit"></i> </button> </td>';
              echo '<td> <form action="'.__SITE_URL.'/index.php?rt=transactions/removeTransaction" method="post" onclick="return checkDelete()"> <input type="hidden" name="transaction" value="'.$t->tr_id.'"> <input type="hidden" name="type" value="'.$t->tr_type.'"> <button class="IconButton" type=submit >  <i class="far fa-trash-alt"></i> </button> </form> </td></tr>';
            }
          }
        }
      ?>
      </tbody>
    </table>
  </div>
</div>

<?php
  $_SESSION['lang'] = 'CRO';
  $_SESSION['page'] = $flag;
  require_once __SITE_PATH . '/view/modal_editTransactionCRO.php';
?>
