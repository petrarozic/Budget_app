//obrisati user_id iz klasa income i expense (ne koristimo ih/smetaju)
<?php

//vraca array sa svim troskovima za logiranog korisnika
    class ExpenseService{

      function getExpenseByID( $user_id ){
          try{
            $db = DB::getConnection();
  			    $st = $db->prepare( 'SELECT * FROM  Expense WHERE user_id=:id ORDER BY date DESC' );
  			    $st->execute( array( 'id' => $user_id ) );
  		  }
  		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

      $arr = array();
  		while( $row = $st->fetch() )
  		{
  			$arr[] = new expense( $row['expense_id'] $row['category_name'], $row['expense_name'], $row['expense_value'],
                  $row['expense_date'], $row['expense_description']);
  		}

  		return $arr;

      }
    }

//vraca array sa svim primanjima za logiranog korisnika
    class IncomeService{

      function getIncomeByID( $user_id ){
          try{
            $db = DB::getConnection();
  			    $st = $db->prepare( 'SELECT * FROM  Incomes WHERE user_id=:id ORDER BY date DESC' );
  			    $st->execute( array( 'id' => $user_id ) );
  		  }
  		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

      $arr = array();
  		while( $row = $st->fetch() )
  		{
  			$arr[] = new income( $row['income_id'] $row['category_name'], $row['income_name'], $row['income_value'],
                  $row['income_date'], $row['income_description']);
  		}

  		return $arr;

      }
    }



 ?>
