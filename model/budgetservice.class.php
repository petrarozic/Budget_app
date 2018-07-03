<?php

class BudgetService
{


	function isAlreadyInDB($username )
	{

		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT * FROM User WHERE username=:username' );
			$st->execute( array( 'username' => $username ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		if( $st->rowCount() !== 0 )
			return true;
		return false;
	}


	function insertUser($username, $password, $email, $reg_seq )
	{
		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'INSERT INTO User(username, password, email, registration_sequence, has_registered) VALUES ' .
												'(:username, :password, :email, :reg_seq, 0)' );
			$st->execute( array('username' => $username,
												 'password' => password_hash( $password, PASSWORD_DEFAULT ),
												 'email' => $email,
												 'reg_seq'  => $reg_seq ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
	}



	function findWithSequence($sequence )
	{
		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT * FROM User WHERE registration_sequence=:reg_seq' );
			$st->execute( array( 'reg_seq' => $sequence ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$row = $st->fetch();

		if( $st->rowCount() !== 1 )
			exit( 'That registration sequence has ' . $st->rowCount() . 'users and should be exactly one.' );
		else
		{
			try
			{
				$db = DB::getConnection();
				$st = $db->prepare( 'UPDATE User SET has_registered=1 WHERE registration_sequence=:reg_seq' );
				$st->execute( array( 'reg_seq' => $sequence ) );
			}
			catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
	  }
	}


  function isInDB( $username, $password ) //vraca false ako nije, inace user_id!
  {
    try
    {
      $db = DB::getConnection();
      $st = $db->prepare( 'SELECT password, user_id FROM User WHERE username=:username');
      $st->execute( array( 'username' => $username));
    }
    catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
		$row = $st->fetch();
		if( $row === false )
			return false;
		$hash = $row['password'];
		if( password_verify( $password, $hash ) )
		{
			return $row['user_id'];
		}
    return false;
  }



	function getExpensesById( $user_id ){
			try{
				$db = DB::getConnection();
				$st = $db->prepare( 'SELECT * FROM  Expense WHERE user_id=:id ORDER BY expense_date DESC' );
				$st->execute( array( 'id' => $user_id ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$arr = array();
		while( $row = $st->fetch() )
		{
			$arr[] = new Expense( $row['expense_id'], $row['category_name'], $row['user_id'], $row['expense_name'], $row['expense_value'],
								$row['expense_date'], $row['expense_description']);
		}

		return $arr;
	}


	//vraca array sa svim primanjima za logiranog korisnika
	function getIncomesById( $user_id ){
			try{
					$db = DB::getConnection();
					$st = $db->prepare( 'SELECT * FROM  Income WHERE user_id=:id ORDER BY income_date DESC' );
					$st->execute( array( 'id' => $user_id ) );
			}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$arr = array();
		while( $row = $st->fetch() )
		{
			$arr[] = new Income( $row['income_id'], $row['category_name'], $row['user_id'], $row['income_name'], $row['income_value'],
								$row['income_date'], $row['income_description']);
		}

		return $arr;

	}


	//sve transakcije (ne pozivam funkcije za income i expense zbog oznaka za razlikovanje u view-u)
function getTransactionsById($user_id){
	$arr = array();

	//incomes
	try{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT * FROM  Income WHERE user_id=:id' );
			$st->execute( array( 'id' => $user_id ) );
	}
	catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }


	while( $row = $st->fetch() )
	{
		$arr[] = new Transaction( $row['income_id'], $row['category_name'], $row['user_id'], $row['income_name'], $row['income_value'],
							$row['income_date'], $row['income_description'], 'i');
	}

//expences
	try{
		$db = DB::getConnection();
		$st = $db->prepare( 'SELECT * FROM  Expense WHERE user_id=:id ' );
		$st->execute( array( 'id' => $user_id ) );
	}
	catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }


	while( $row = $st->fetch() )
	{
	$arr[] = new Transaction( $row['expense_id'], $row['category_name'], $row['user_id'], $row['expense_name'], $row['expense_value'],
						$row['expense_date'], $row['expense_description'], 'e' );
	}

	//sortirati arr po datumu

		usort($arr, function($a, $b) {
		    return strcmp($b->tr_date,$a->tr_date);
		});

	return $arr;
	}

	function removeExpense($user_id, $expense_id ){
		try
    {
      $expenses = DB::getConnection();
			$expenses_ = $expenses->prepare('DELETE FROM `Expense` WHERE ( user_id LIKE :user_id ) AND ( expense_id LIKE :expense_id )');
			$expenses_->execute(array('user_id' => $user_id, 'expense_id' => $expense_id ));
    }
    catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
	}

	function removeIncome($user_id, $income_id ){

		try
		{
			$incomes = DB::getConnection();
			$incomes_ = $incomes->prepare('DELETE FROM `Income` WHERE ( user_id LIKE :user_id ) AND ( income_id LIKE :income_id )');
			$incomes_->execute(array('user_id' => $user_id, 'income_id' => $income_id ));
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
	}


	function getUserbById( $user_id){
		try
    {
      $user = DB::getConnection();
      $user_ = $user->prepare( 'SELECT * FROM User WHERE user_id LIKE :user_id');
      $user_->execute( array('user_id' => $user_id ) );
    }
    catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }


    $row = $user_->fetch();
  	$user = new User( $row['user_id'], $row['username'], $row['password'], $row['email'],
		 													$row['daily_limit'], $row['weekly_limit'], $row['monthly_limit'],
															$row['registration_sequence'], $row['has_registered'] );

    return $user;
	}



	function changeEmail($user_id, $new_email ){

		try{
			$user = DB::getConnection();
			$user_ = $user->prepare('UPDATE User SET email=:new_email WHERE user_id LIKE :user_id');
			$user_->execute( array( 'new_email' => $new_email, 'user_id' => $user_id) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		return;
	}

	function changeUsername($user_id, $new_username ){

		try{
			$user = DB::getConnection();
			$user_ = $user->prepare('UPDATE User SET username=:new_username WHERE user_id LIKE :user_id');
			$user_->execute( array( 'new_username' => $new_username, 'user_id' => $user_id) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		return;
	}

	function changePassword( $user_id, $new_pass ){
		try{
			$user = DB::getConnection();
			$user_ = $user->prepare('UPDATE User SET password=:new_pass WHERE user_id LIKE :user_id');
			$user_->execute( array( 'new_pass' => password_hash( $new_pass, PASSWORD_DEFAULT ), 'user_id' => $user_id) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		return;
	}

	function changeDaily($user_id, $new_limit ){

		try{
			$user = DB::getConnection();
			$user_ = $user->prepare('UPDATE User SET daily_limit=:new_limit WHERE user_id LIKE :user_id');
			$user_->execute( array( 'new_limit' => $new_limit, 'user_id' => $user_id) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		return;
	}

	function changeWeekly($user_id, $new_limit ){

		try{
			$user = DB::getConnection();
			$user_ = $user->prepare('UPDATE User SET weekly_limit=:new_limit WHERE user_id LIKE :user_id');
			$user_->execute( array( 'new_limit' => $new_limit, 'user_id' => $user_id) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		return;
	}

	function changeMonthly($user_id, $new_limit ){

		try{
			$user = DB::getConnection();
			$user_ = $user->prepare('UPDATE User SET monthly_limit=:new_limit WHERE user_id LIKE :user_id');
			$user_->execute( array( 'new_limit' => $new_limit, 'user_id' => $user_id) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		return;
	}

	function accountDelete( $user_id ){

		try{
			$user = DB::getConnection();
			$user_ = $user->prepare('DELETE FROM User WHERE user_id LIKE :user_id');
			$user_->execute( array( 'user_id' => $user_id ) );

			$expense_ = $user->prepare('DELETE FROM Expense WHERE user_id LIKE :user_id');
			$expense_->execute( array( 'user_id' => $user_id ) );

			$income_ = $user->prepare('DELETE FROM Income WHERE user_id LIKE :user_id');
			$income_->execute( array( 'user_id' => $user_id ) );

			$categ_ = $user->prepare('DELETE FROM Category WHERE user_id LIKE :user_id');
			$categ_->execute( array( 'user_id' => $user_id ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		return;
	}

	function getUserId( $username ){
		try{
			$user = DB::getConnection();
			$user_ = $user->prepare( 'SELECT user_id FROM User WHERE username LIKE :username');
			$user_->execute( array( 'username' => $username ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$str = $user_->fetch();
		return $str[0];
	}

	function getUserEmail( $username ){
		try{
			$user = DB::getConnection();
			$user_ = $user->prepare( 'SELECT email FROM User WHERE username LIKE :username');
			$user_->execute( array( 'username' => $username ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$str = $user_->fetch();
		return $str[0];
	}

	function getCategoriesById( $user_id, $tip ){
		if( $tip == "Income" )
			$type ="Primanja";
		else {
			$type = "Troskovi";
		}
		try{
				$db = DB::getConnection();
				$categories = $db->prepare( 'SELECT category_name FROM  Category WHERE (category_type =:type) AND ( user_id  = :user_id )' );
				$categories->execute( array( 'type' => $type, 'user_id' => $user_id ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$arr = array();

		$i = 0;
		while( $row = $categories->fetch() )
		{
			$arr[$i] = $row['category_name'];
			$i++;
		}
		return $arr;
	}

	 function addTransaction($user_id, $type, $name, $category, $amount, $date, $description, $repeating){

		 if( $type == "Expense" ){
			 try{
				 $db = DB::getConnection();
				 for( $i = 0; $i < $repeating ; ++$i ) {
					 $st = $db->prepare( 'INSERT INTO Expense(category_name, user_id, expense_name, expense_value, expense_date, expense_description )
					 VALUES (:category_name, :user_id, :expense_name, :expense_value, :expense_date, :expense_description)' );
					 $st->execute( array( "category_name" => $category, "user_id" => $user_id, "expense_name" => $name, "expense_value" => $amount, "expense_date" => $date, "expense_description" => $description ) );

					 // promjeniti date - +1 mjesec
					 $date = strtotime("+1 months", strtotime($date));
					 $date = strftime ( '%Y-%m-%d' , $date );
				 }
			 }
			 catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
		 }

		 else if( $type == "Income" ){
			 try{
				 $db = DB::getConnection();
				 for( $i = 0; $i < $repeating ; ++$i ) {
					 $st = $db->prepare( 'INSERT INTO Income(category_name, user_id, income_name, income_value, income_date, income_description )
					 VALUES (:category_name, :user_id, :income_name, :income_value, :income_date, :income_description)' );
					 $st->execute( array( "category_name" => $category, "user_id" => $user_id, "income_value" => $amount, "income_name" => $name, "income_date" => $date, "income_description" => $description ) );

					 $date = strtotime("+1 months", strtotime($date));
					 $date = strftime ( '%Y-%m-%d' , $date );
				 }
			 }
			 catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
		 }
		 return;
	 }

	 function getTransactionById($user_id, $transaction_id, $type ){

		 if( $type == "expense" || $type == "e" ){
			 try{
 					$db = DB::getConnection();
 					$st = $db->prepare( 'SELECT * FROM  Expense WHERE ( user_id=:id ) AND ( expense_id = :transaction_id )' );
 					$st->execute( array( 'id' => $user_id, "transaction_id" => $transaction_id) );
 			}
 			catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		 }
		 else if( $type == "income" || $type == "i" ){
			 try{
			 		$db = DB::getConnection();
			 		$st = $db->prepare( 'SELECT * FROM  Income WHERE (user_id=:id) AND ( income_id = :transaction_id)' );
			 		$st->execute( array( "id" => $user_id, "transaction_id" => $transaction_id ) );
			 }
			 catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
		 }

		 $arr = array();

		 $i = 0;

		 $podaci = $st->fetch();

		 $arr['id'] = $podaci[0];
		 $arr['category'] = $podaci[1];
 		 $arr['user_id'] = $podaci[2];
		 $arr['name'] = $podaci[3];
		 $arr['value'] = $podaci[4];
		 $arr['date'] = $podaci[5];
		 $arr['description'] = $podaci[6];

		 return $arr;
	 }


	 function editingTransaction($user_id, $type, $transaction_id, $name, $category, $amount, $date, $description){

		 if ( $type == "Income"){
			 try{
				 $user = DB::getConnection();
				 $user_ = $user->prepare('UPDATE Income SET income_name =:name, category_name = :category, income_value = :amount, income_date = :date, income_description = :description WHERE ( user_id LIKE :user_id) AND ( income_id LIKE :transaction_id)');
				 $user_->execute( array( 'name' => $name, 'category' => $category, 'amount' => $amount, 'date' => $date, 'description' => $description, 'user_id' => $user_id, 'transaction_id' => $transaction_id ) );
			 }
			 catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
		 }
		 else if ( $type == "Expense" ){
			 try{
				 $user = DB::getConnection();
				 $user_ = $user->prepare('UPDATE Expense SET expense_name =:name, category_name = :category, expense_value = :amount, expense_date = :date, expense_description = :description WHERE ( user_id LIKE :user_id) AND ( expense_id LIKE :transaction_id)');
				 $user_->execute( array( 'name' => $name, 'category' => $category, 'amount' => $amount, 'date' => $date, 'description' => $description, 'user_id' => $user_id, 'transaction_id' => $transaction_id ) );
			 }
			catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
		 }

	 }

};


?>
