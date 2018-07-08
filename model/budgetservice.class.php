<?php

class BudgetService
{

	/****************************************************************************/
 	//PROVJERA JE POSTOJI LI USERNAME U TABLICI USER
 /*****************************************************************************/

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

	/****************************************************************************/
 	//DODAVANJE NOVOG KORISNIKA
 /*****************************************************************************/

	function insertUser($username, $password, $email, $reg_seq )
	{
		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'INSERT INTO User(username, password, email, daily_limit, weekly_limit, monthly_limit, send_mail, registration_sequence, has_registered) VALUES ' .
												'(:username, :password, :email, 200, 1500, 5000, 1, :reg_seq, 0)' );
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
			$username = $row['user_id'];
			// Default categories
			try
			{
				$db = DB::getConnection();
				$st = $db->prepare( 'INSERT INTO Category(user_id, category_name, category_type) VALUES ' .
													'(:user_id, :category_name, :category_type)' );
				$st->execute( array('user_id' => $username, 'category_name' => "Stanarina", 'category_type' => "Troskovi") );
				$st->execute( array('user_id' => $username, 'category_name' => "Hrana", 'category_type' => "Troskovi") );
				$st->execute( array('user_id' => $username, 'category_name' => "Izlasci", 'category_type' => "Troskovi") );
				$st->execute( array('user_id' => $username, 'category_name' => "Placa", 'category_type' => "Primanja") );
				$st->execute( array('user_id' => $username, 'category_name' => "Stipendija", 'category_type' => "Primanja") );
				$st->execute( array('user_id' => $username, 'category_name' => "Fondovi", 'category_type' => "Primanja") );
			}
			catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
	  }
	}


	/****************************************************************************/
 	//PROVJERA JE LI KORISNIK U BAZI
 /*****************************************************************************/
  function isInDB( $username, $password ) //vraca false ako nije, inace user_id!
  {
    try
    {
      $db = DB::getConnection();
      $st = $db->prepare( 'SELECT password, user_id, has_registered FROM User WHERE username=:username');
      $st->execute( array( 'username' => $username));
    }
    catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
		$row = $st->fetch();
		if( $row === false )
			return false;
		$hash = $row['password'];
		if( password_verify( $password, $hash ) && $row['has_registered'] == 1)
		{

			return $row['user_id'];
		}
    return false;
  }


	/****************************************************************************/
 	//SVI TROŠKOVI KORISNIKA
 /*****************************************************************************/
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


	/****************************************************************************/
	 //SVA PRIMANJA KORISNIKA
 /*****************************************************************************/
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

	/*******************************************************************************/
	//RAČUNANJE PREKORAČENJA LIMITA
	/*******************************************************************************/

	function sendWarning( $type_of_limit ){

		$ls = new BudgetService;
		$user = $ls->getUserbById($_SESSION['user_id']);

		$to       = $user->email;
		$subject  = 'Warning - Budget-app';
		$message  = 'Dear ' . $user->username . ",\n You have exceed your ".$type_of_limit;
		$message .= ". \n";
		$headers  = 'From: rp2@studenti.math.hr' . "\r\n" .
								'Reply-To: rp2@studenti.math.hr' . "\r\n" .
								'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, $headers);
		exit();
	}

	function limits(){
		$expenses  = $this->getExpensesById($_SESSION['user_id']);
		$user_data = $this->getUserbById($_SESSION['user_id']);

		//danasnji datum
		$today = date("Y-m-d");
		$this_month = date("Y-m");
		$this_week = date("Y-W");

		//sume
		$daily_sum = 0;
		$weekly_sum = 0;
		$monthly_sum = 0;

		//daily_sum, weekly_sum, monthly_sum
		foreach ($expenses as $row) {
					if( date("Y-m-d",strtotime( $row->expense_date) ) === $today )
						$daily_sum += $row->expense_value;
					if( date("Y-m", strtotime(  $row->expense_date) ) === $this_month )
						$monthly_sum += $row->expense_value;
					if(date( "Y-W", strtotime( $row->expense_date ) ) === $this_week )
						$weekly_sum += $row->expense_value;
		 }
		//u sesiju stavi prekoracenja
		 $old_daily = $_SESSION['d_limit'];
		 $_SESSION['d_limit'] = $user_data->daily_limit - $daily_sum;
		 if( $old_daily >= 0 && $_SESSION['d_limit'] < 0 )
		 			$this->sendWarning("daily limit");

		 $old_weekly = $_SESSION['w_limit'];
		 $_SESSION['w_limit'] = $user_data->weekly_limit - $weekly_sum;
		 if( $old_weekly >= 0 && $_SESSION['w_limit'] < 0 )
		 			$this->sendWarning("weekly limit");

		 $old_monthly = $_SESSION['m_limit'];
		 $_SESSION['m_limit'] = $user_data->monthly_limit - $monthly_sum;
		 if( $old_monthly >= 0 && $_SESSION['m_limit'] < 0 )
		 			$this->sendWarning("monthly limit");
	}


	/****************************************************************************/
	 //SVE TRANSAKCIJE KORISNIKA
 /*****************************************************************************/
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


	/****************************************************************************/
 	//BRISANJE TROŠKOVA
 /*****************************************************************************/
	function removeExpense($user_id, $expense_id ){
		try
    {
      $expenses = DB::getConnection();
			$expenses_ = $expenses->prepare('DELETE FROM `Expense` WHERE ( user_id LIKE :user_id ) AND ( expense_id LIKE :expense_id )');
			$expenses_->execute(array('user_id' => $user_id, 'expense_id' => $expense_id ));
    }
    catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
	}

	/****************************************************************************/
 	//BRISANJE PRIMANJA
 /*****************************************************************************/
	function removeIncome($user_id, $income_id ){

		try
		{
			$incomes = DB::getConnection();
			$incomes_ = $incomes->prepare('DELETE FROM `Income` WHERE ( user_id LIKE :user_id ) AND ( income_id LIKE :income_id )');
			$incomes_->execute(array('user_id' => $user_id, 'income_id' => $income_id ));
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
	}

	/****************************************************************************/
 	//SVI PODACI IZ TABLICE USER ZA TRAŽENOG KORISNIKA
 /*****************************************************************************/
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
		 													$row['daily_limit'], $row['weekly_limit'], $row['monthly_limit'],$row['send_mail'],
															$row['registration_sequence'], $row['has_registered'] );

    return $user;
	}


	/****************************************************************************/
 	//PROMJENA EMAILA
 /*****************************************************************************/
	function changeEmail($user_id, $new_email ){

		try{
			$user = DB::getConnection();
			$user_ = $user->prepare('UPDATE User SET email=:new_email WHERE user_id LIKE :user_id');
			$user_->execute( array( 'new_email' => $new_email, 'user_id' => $user_id) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		return;
	}

	/****************************************************************************/
 	//PROMJENA KORISNIČKOG IMENA
 /*****************************************************************************/
	function changeUsername($user_id, $new_username ){

		try{
			$user = DB::getConnection();
			$user_ = $user->prepare('UPDATE User SET username=:new_username WHERE user_id LIKE :user_id');
			$user_->execute( array( 'new_username' => $new_username, 'user_id' => $user_id ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		return;
	}

	/****************************************************************************/
 	//PROMJENA LOZINKE
 /*****************************************************************************/
	function changePassword( $user_id, $new_pass ){
		try{
			$user = DB::getConnection();
			$user_ = $user->prepare('UPDATE User SET password=:new_pass WHERE user_id LIKE :user_id');
			$user_->execute( array( 'new_pass' => password_hash( $new_pass, PASSWORD_DEFAULT ), 'user_id' => $user_id) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		return;
	}

	/****************************************************************************/
 	//PROMJENA DNEVNOG LIMITA
 /*****************************************************************************/
	function changeDaily($user_id, $new_limit ){

		try{
			$user = DB::getConnection();
			$user_ = $user->prepare('UPDATE User SET daily_limit=:new_limit WHERE user_id LIKE :user_id');
			$user_->execute( array( 'new_limit' => $new_limit, 'user_id' => $user_id) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		return;
	}

	/****************************************************************************/
 	//PROMJENA TJEDNOG LIMITA
 /*****************************************************************************/
	function changeWeekly($user_id, $new_limit ){

		try{
			$user = DB::getConnection();
			$user_ = $user->prepare('UPDATE User SET weekly_limit=:new_limit WHERE user_id LIKE :user_id');
			$user_->execute( array( 'new_limit' => $new_limit, 'user_id' => $user_id) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		return;
	}

	/****************************************************************************/
 	//PROMJENA MJESEČNOG LIMITA
 /*****************************************************************************/
	function changeMonthly($user_id, $new_limit ){

		try{
			$user = DB::getConnection();
			$user_ = $user->prepare('UPDATE User SET monthly_limit=:new_limit WHERE user_id LIKE :user_id');
			$user_->execute( array( 'new_limit' => $new_limit, 'user_id' => $user_id) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		return;
	}

	/****************************************************************************/
	//PROMJENA CHECKBOXA
	/*****************************************************************************/
	function changeCheckbox( $user_id ){
		//dohvati stari send_mail iz baze

			try{
				$user = DB::getConnection();
				$user_ = $user->prepare('SELECT send_mail FROM User WHERE user_id LIKE :user_id');
				$user_->execute( array( 'user_id' => $user_id) );

			}
			catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
			//promjena
		$old = $user_->fetch();
		$new = 1 - $old[0];
		try{
			$user = DB::getConnection();
			$user_ = $user->prepare('UPDATE User SET send_mail=:new WHERE user_id LIKE :user_id');
			$user_->execute( array( 'new' => $new, 'user_id' => $user_id) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		return;
	}

	/****************************************************************************/
 	//BRISANJE KORISNIČKOG RAČUNA
 /*****************************************************************************/
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

	/****************************************************************************/
 	//ID KORISNIKA PREKO NJEGOVOG KORISNIČKOG IMENA
 /*****************************************************************************/
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

	/****************************************************************************/
 	//EMAIL KORISNIKA PREKO NJEGOVOG KORISNIČKOG IMENA
 /*****************************************************************************/
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

	/****************************************************************************/
	//SVE KATEGORIJE KORISNIKA OVISNO O TIPU
	/*****************************************************************************/
	function getCategoriesById( $user_id, $tip ){
		if( $tip == "Income" || $tip =="Prihod")
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

	/****************************************************************************/
	//DODAVANJE TRANSAKCIJE
	/*****************************************************************************/
	 function addTransaction($user_id, $type, $name, $category, $amount, $date, $description, $repeating){

		 if( $type == "Expense" || $type == "Trošak" ){
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

		 else if( $type == "Income"|| $type == "Prihod"  ){
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

	 /****************************************************************************/
	 //
	 /*****************************************************************************/
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


	 /****************************************************************************/
	 //UREĐIVANJE TRANSAKCIJE
	 /*****************************************************************************/
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


	function getFirst( $user_id ){
	 	 try{
	 	 		$db = DB::getConnection();
	 	 		$st = $db->prepare( 'SELECT expense_date FROM  Expense WHERE user_id=:id ORDER BY expense_date LIMIT 1' );
	 	 		$st->execute( array( 'id' => $user_id ) );
	 	 }
	 	 catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

	   $date1 = $st->fetch();

		 try{
				$db2 = DB::getConnection();
				$st2 = $db2->prepare( 'SELECT income_date FROM  Income WHERE user_id=:id ORDER BY income_date LIMIT 1' );
				$st2->execute( array( 'id' => $user_id ) );
		 }
		 catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		 $date2 = $st2->fetch();

		 $date = $date1['expense_date'] < $date2['income_date'] ? $date1['expense_date'] : $date2['income_date'];

		 $time = array();
		 $time[0] = date("n", strtotime($date));
		 $time[1] = date("Y", strtotime($date));
	 	 return $time;
	}

	function ExpenseInMonth($user_id, $m, $y){
		try{
			 $db = DB::getConnection();
			 $st = $db->prepare( 'SELECT expense_value FROM  Expense WHERE user_id=:id AND YEAR(expense_date)=:year AND MONTH(expense_date)=:month' );
			 $st->execute( array( 'id' => $user_id, 'year'=> $y, 'month'=> $m + 1) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$expenses = array();
		while( $row = $st->fetch() )
		{
			array_push($expenses, $row['expense_value']);
		}

		return $expenses;
	}

	function ExpenseInYear($user_id, $y){
		try{
			 $db = DB::getConnection();
			 $st = $db->prepare( 'SELECT expense_value FROM  Expense WHERE user_id=:id AND YEAR(expense_date)=:year' );
			 $st->execute( array( 'id' => $user_id, 'year'=> $y ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$expenses = array();
		while( $row = $st->fetch() )
		{
			array_push($expenses, $row['expense_value']);
		}

		return $expenses;
	}

	function IncomeInMonth($user_id, $m, $y){
		try{
			 $db = DB::getConnection();
			 $st = $db->prepare( 'SELECT income_value FROM  Income WHERE user_id=:id AND year(income_date)=:year AND month(income_date)=:month' );
			 $st->execute( array( 'id' => $user_id, 'year'=> $y, 'month'=> $m + 1) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$incomes = array();
		while( $row = $st->fetch() )
		{
			array_push($incomes, $row['income_value']);
		}

		return $incomes;
	}


	function IncomeInYear($user_id, $y){
		try{
			 $db = DB::getConnection();
			 $st = $db->prepare( 'SELECT income_value FROM  Income WHERE user_id=:id AND year(income_date)=:year' );
			 $st->execute( array( 'id' => $user_id, 'year'=> $y ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$incomes = array();
		while( $row = $st->fetch() )
		{
			array_push($incomes, $row['income_value']);
		}

		return $incomes;
	}

	function lineYear($user_id, $y){
		try{
			 $db = DB::getConnection();
			 $st = $db->prepare( 'SELECT income_value, month(income_date) FROM  Income WHERE user_id=:id AND year(income_date)=:year' );
			 $st->execute( array( 'id' => $user_id, 'year'=> $y ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$line = array(0,0,0,0,0,0,0,0,0,0,0,0);
		while( $row = $st->fetch() )
		{
			$month = $row['month(income_date)'] - 1;
			$line[$month] += $row['income_value'];
		}

		try{
			 $db2 = DB::getConnection();
			 $st2 = $db2->prepare( 'SELECT expense_value, month(expense_date) FROM  Expense WHERE user_id=:id AND year(expense_date)=:year' );
			 $st2->execute( array( 'id' => $user_id, 'year'=> $y ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		while( $row = $st2->fetch() )
		{
			$month = $row['month(expense_date)'] - 1;
			$line[$month] -= $row['expense_value'];
		}
		return $line;
	}


	function lineMonth($user_id, $m, $y){
		try{
			 $db = DB::getConnection();
			 $st = $db->prepare( 'SELECT income_value, day(income_date) FROM  Income WHERE user_id=:id AND month(income_date)=:month AND year(income_date)=:year' );
			 $st->execute( array( 'id' => $user_id, 'month' => $m + 1, 'year'=> $y ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$n = cal_days_in_month(CAL_GREGORIAN, $m + 1, $y); //niz će biti dug onoliko koliko ima dana u mjesecu
		$line = array_fill(0, $n, 0);
		while( $row = $st->fetch() )
		{
			$day = $row['day(income_date)'] - 1;
			$line[$day] += $row['income_value'];
		}

		try{
			 $db2 = DB::getConnection();
			 $st2 = $db2->prepare( 'SELECT expense_value, day(expense_date) FROM  Expense WHERE user_id=:id AND month(expense_date)=:month AND year(expense_date)=:year' );
			 $st2->execute( array( 'id' => $user_id, 'month' => $m + 1, 'year'=> $y ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		while( $row = $st2->fetch() )
		{
			$day = $row['day(expense_date)'] - 1;
			$line[$day] -= $row['expense_value'];
		}
		return $line;
	}

	function getPieData($user_id, $t, $p, $m, $y){

		if( $t == 0 ) // income
		{
			if ( $p == 0 )	// year
			{
				try{
					 $db = DB::getConnection();
					 $st = $db->prepare( 'SELECT income_value, category_name FROM  Income WHERE user_id=:id AND year(income_date)=:year' );
					 $st->execute( array( 'id' => $user_id, 'year'=> $y ) );
				}
				catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

				$incomes = array();
				$i = 0;
				while( $row = $st->fetch() )
				{
					$incomes[$i]['value'] = $row['income_value'];
					$incomes[$i]['category'] = $row['category_name'];
					$i++;
				}
				return $incomes;
			}
			else if ( $p == 1 )	// mjesec
			{
				try{
					 $db = DB::getConnection();
					 $st = $db->prepare( 'SELECT income_value, category_name FROM  Income WHERE user_id=:id AND year(income_date)=:year AND month(income_date)=:month' );
					 $st->execute( array( 'id' => $user_id, 'year'=> $y, 'month'=> $m + 1) );
				}
				catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

				$incomes = array();
				$i = 0;
				while( $row = $st->fetch() )
				{
					$incomes[$i]['value'] = $row['income_value'];
					$incomes[$i]['category'] = $row['category_name'];
					$i++;
				}
				return $incomes;
			}
		}
		else if( $t == 1 ) //expense
		{
			if ( $p == 0 ) // year
			{
				try{
					 $db = DB::getConnection();
					 $st = $db->prepare( 'SELECT expense_value, category_name FROM  Expense WHERE user_id=:id AND YEAR(expense_date)=:year' );
					 $st->execute( array( 'id' => $user_id, 'year'=> $y ) );
				}
				catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

				$expenses = array();
				$i = 0;
				while( $row = $st->fetch() )
				{
					$expenses[$i]['value'] = $row['expense_value'];
					$expenses[$i]['category'] = $row['category_name'];
					$i++;
				}
				return $expenses;
			}
			else if ( $p == 1 )	// mjesec
			{
				try{
					 $db = DB::getConnection();
					 $st = $db->prepare( 'SELECT expense_value, category_name FROM  Expense WHERE user_id=:id AND YEAR(expense_date)=:year AND MONTH(expense_date)=:month' );
					 $st->execute( array( 'id' => $user_id, 'year'=> $y, 'month'=> $m + 1) );
				}
				catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

				$expenses = array();
				$i = 0;
				while( $row = $st->fetch() )
				{
					$expenses[$i]['value'] = $row['expense_value'];
					$expenses[$i]['category'] = $row['category_name'];
					$i++;
				}
				return $expenses;
			}
		}
		return $a;
	}



 /*DODAVANJE KATEGORIJE*/
function addCategory($user_id, $type, $name){
		if( $type == "Expense" || $type == "Trošak" )  $type= "Troskovi";
		else $type= "Primanja";
		//provjera postoji li vec ta kategorija
		try
	 		{
	 			$db = DB::getConnection();
	 			$category = $db->prepare( 'SELECT category_name FROM Category WHERE ( user_id = :user_id ) AND ( category_name = :name ) AND ( category_type = :type )' );
	 			$category->execute( array( 'user_id' => $user_id, 'name' => $name, 'type' => $type ) );

	 		}
 		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		//vec postoji ta kategorija za taj tip i usera
 		if( $category->rowCount() !== 0 )
 			return false;


		 try{
			 $db = DB::getConnection();

			 $st = $db->prepare( 'INSERT INTO Category(user_id, category_name, category_type )
			 VALUES (:user_id, :category_name, :category_type)' );
			 $st->execute( array( 'user_id' => $user_id, 'category_name' => $name, 'category_type' => $type) );
		 }
		 catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
		 return true;
	 }


	 /****************************************************************************/
		//BRISANJE KATEGORIJE
	/*****************************************************************************/

	 function removeCategory( $user_id, $category_name, $category_type ){

		if( $category_type == "Income" )
 			$type ="Primanja";
 		else {
 			$type = "Troskovi";
 		}

		try
		{
			$db = DB::getConnection();
			$ex = $db->prepare( 'SELECT category_name FROM Expense WHERE ( user_id=:user_id ) AND ( category_name = :name )' );
			$ex->execute( array( 'user_id' => $user_id, 'name' => $category_name ) );
			$in = $db->prepare( 'SELECT category_name FROM Income WHERE ( user_id=:user_id ) AND ( category_name = :name )' );
			$in->execute( array( 'user_id' => $user_id, 'name' => $category_name ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		if( $ex->rowCount() !== 0 || $in->rowCount() !== 0 )
			return false;

		try{
			 $user = DB::getConnection();
			 $category = $user->prepare('DELETE FROM Category WHERE ( user_id = :user_id ) AND ( category_name =:category_name ) AND ( category_type = :category_type )');
			 $category->execute( array( 'user_id' => $user_id, 'category_name' => $category_name, 'category_type' => $type ) );
		 }
		 catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
		 return true;
	 }

	 /****************************************************************************/
		//UREĐIVANJE KATEGORIJE
	/*****************************************************************************/

	 function editCategory( $user_id, $category_name, $category_type, $old_category_name ){

		 if( $category_type == "Income" )
  			$type ="Primanja";
  		else {
  			$type = "Troskovi";
  		}

		 try{
			 $user = DB::getConnection();

			 if($type === "Troskovi" ){
					$expense = $user->prepare('UPDATE Category SET category_name = :new_name WHERE ( user_id LIKE :user_id) AND ( category_name LIKE :category_name)  AND ( category_type LIKE :type)');
					$expense->execute( array( 'new_name' => $category_name, 'user_id' => $user_id, 'category_name' => $old_category_name, 'type' => $type ) );
			 }
			 if( $type === "Primanja" ){
				 $income = $user->prepare('UPDATE Category SET category_name = :new_name WHERE ( user_id LIKE :user_id) AND ( category_name LIKE :category_name) AND ( category_type LIKE :type)');
				 $income->execute( array( 'new_name' => $category_name, 'user_id' => $user_id, 'category_name' => $old_category_name, 'type' => $type ) );
			 }

		 }
		 catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
	 }
};
?>
