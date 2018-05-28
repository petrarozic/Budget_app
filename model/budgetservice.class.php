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
		catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }

		if( $st->rowCount() !== 0 )
			return true;
		return false;
	}

	function unesiKorisnika($username, $password, $email, $reg_seq )
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
		catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }
	}

	function findWithNiz($niz )
	{
		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT * FROM User WHERE registration_sequence=:reg_seq' );
			$st->execute( array( 'reg_seq' => $niz ) );
		}
		catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }

		$row = $st->fetch();

		if( $st->rowCount() !== 1 )
			exit( 'Taj registracijski niz ima ' . $st->rowCount() . 'korisnika, a treba biti točno 1 takav.' );
		else
		{
			try
			{
				$db = DB::getConnection();
				$st = $db->prepare( 'UPDATE User SET has_registered=1 WHERE registration_sequence=:reg_seq' );
				$st->execute( array( 'reg_seq' => $niz ) );
			}
			catch( PDOException $e ) { exit( 'Greška u bazi: ' . $e->getMessage() ); }
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
			$arr[] = new expense( $row['expense_id'], $row['category_name'], $row['expense_name'], $row['expense_value'],
								$row['expense_date'], $row['expense_description']);
		}

		return $arr;
	}


	//vraca array sa svim primanjima za logiranog korisnika


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
			$arr[] = new income( $row['income_id'], $row['category_name'], $row['income_name'], $row['income_value'],
								$row['income_date'], $row['income_description']);
		}

		return $arr;

	}

};

//vraca array sa svim troskovima za logiranog korisnika





?>
