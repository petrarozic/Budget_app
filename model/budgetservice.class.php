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



	function getExpenseById( $user_id ){
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
	function getIncomeById( $user_id ){
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
function getTransactionById($user_id){
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
		    return strcmp($a->tr_date, $b->tr_date);
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
};





?>
