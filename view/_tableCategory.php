<script>

      console.log("u scriptu");
      var fl = <?php if ( isset($message)) echo json_encode($_SESSION['flag']); else echo "2";?>;
      var mess = <?php if ( isset($message)) echo json_encode($message); else echo "undefined";?>;

        if(fl.toString() === '0')
            $.notify(mess, "error");

        else if(fl.toString() === '1')
            $.notify(mess, "success");


</script>


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
        <table class="table" >
          <thead>
            <tr>
              <th class="sortable" id="incomeTable" style="text-align:center; border:none;">  NAME  </th>
              <th style="text-align:center; border:none;"> <!-- EDIT --> </th>
              <th style="text-align:center; border:none;"> <!-- REMOVE --> </th>
            </tr>
          </thead>
          <tbody id="inT">
            <?php
              if( empty($inc_catList) )
                  echo '<tr><td colspan="3">There are currently no subscribed expenses/incomes.</td></tr>';
              else{
                foreach($inc_catList as $t){
                  echo '<tr>';
                  echo '<td>'.$t.'</td>';
                  echo '<td><button class="IconButtonEC" id="EditIcon" name="Income" data-toggle="modal" data-target="#EditCategory" value="'.$t.'" ><i class="far fa-edit"></i></button></td>';
                  echo '<td><form action="'.__SITE_URL.'/index.php?rt=category/removeCategory" method="post"  onclick="return checkDelete()"> <input type="hidden" name="name" value="'.$t.'"> <input type="hidden" name="type" value="Income"><button type=submit class="IconButton" > <i class="far fa-trash-alt"></i> </button> </form> </td>';
                  echo '</tr>';
                  }
                }
            ?>
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
              <th class="sortable"  id="expenseTable" style="text-align:center; border:none;">  NAME  </th>
              <th style="text-align:center; border:none;"> <!-- EDIT --> </th>
              <th style="text-align:center; border:none;"> <!-- REMOVE --> </th>
            </tr>
          </thead>
          <tbody id="exT">
            <?php
              if( empty($exp_catList) )
                  echo '<tr><td colspan="3">There are currently no subscribed expenses/incomes.</td></tr>';
              else{
                foreach($exp_catList as $t){
                  echo '<tr>';
                  echo '<td>'.$t.'</td>';
                  echo '<td><button class="IconButtonEC" id="EditIcon" name="Expense" data-toggle="modal" data-target="#EditCategory" value="'.$t.'" ><i class="far fa-edit"></i></button></td>';
                  echo '<td><form action="'.__SITE_URL.'/index.php?rt=category/removeCategory" method="post"  onclick="return checkDelete()"> <input type="hidden" name="name" value="'.$t.'"> <input type="hidden" name="type" value="Expense"><button type=submit class="IconButton" > <i class="far fa-trash-alt"></i> </button> </form> </td>';
                  echo '</tr>';
                  }
                }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script language="JavaScript" type="text/javascript">
  function checkDelete(){
      return confirm('By pressing OK you will delete selected category.');
  }
</script>

<?php require_once __SITE_PATH . '/view/modal_addCategory.php'; ?>
<?php require_once __SITE_PATH . '/view/modal_editCategory.php'; ?>
