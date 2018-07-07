<?php


require_once '../../model/db.class.php';
//require_once 'db.class.php';

$db = DB::getConnection();

//tablica Korisnik
try
{
	$st = $db->prepare(
		'CREATE TABLE IF NOT EXISTS User (' . //User
		'user_id int NOT NULL PRIMARY KEY AUTO_INCREMENT, ' . //user_id
		'username varchar(20) NOT NULL, ' . // username
		'password varchar(225) NOT NULL, ' . //OK
		'email varchar(50), ' .					//korisnik ne moraunijeti e-mail adresu
		'daily_limit double, ' . //daily_limit
		'weekly_limit double, ' . //weekly_limit
		'monthly_limit double, '. //monthly_limit
		'send_mail int, '.
		'registration_sequence varchar(20) NOT NULL, ' .
		'has_registered int )'
	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error #1: " . $e->getMessage() ); }

//tablica Troskovi

try
{
	$st = $db->prepare(
		'CREATE TABLE IF NOT EXISTS Expense (' . 								// Expense
		'expense_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' . //expense_id
		'category_name varchar(20) NOT NULL,' .									// category_name
//		'username varchar(20) NOT NULL,' . mjenjamo username sa user_id
		'user_id int NOT NULL,'.
		'expense_name varchar(30) NOT NULL,'. 									// expense_name
		'expense_value double NOT NULL,' .											// expense_value
		'expense_date date NOT NULL,' . 												// date
//		'ponavljanje_troska int ,' .	//izbacujemo broj_ponavljanja iz baze i ostavljamo to da se odradi uz formu i for petlju
		'expense_description varchar(50) NOT NULL)' 										// expense_description

	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error #2: " . $e->getMessage() ); }



//tablica primanja

try
{
	$st = $db->prepare(
		'CREATE TABLE IF NOT EXISTS Income (' . 	// Income - analogno Expensema
		'income_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
		'category_name varchar(20) NOT NULL,' .
//		'username varchar(20) NOT NULL,' .
		'user_id int NOT NULL,'.
		'income_name varchar(30) NOT NULL,'.
		'income_value double NOT NULL,' .
		'income_date date NOT NULL,' . // na datumu nisam izmjenila jos
//		'ponavljanje_primanja int ,' .
		'income_description  varchar(50) NOT NULL)'
	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error #3: " . $e->getMessage() ); }

//kategorije

try
{
	$st = $db->prepare(
		'CREATE TABLE IF NOT EXISTS Category (' .	// Category
		'user_id int NOT NULL ,' .			// user_id
		'category_name varchar(20) NOT NULL ,' .	// category_name
		'category_type varchar(20) NOT NULL,'.	// category_type
		'PRIMARY KEY(user_id, category_name, category_type) )'
	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error #4: " . $e->getMessage() ); }


//podkategorije
/*

try
{
	$st = $db->prepare(
		'CREATE TABLE IF NOT EXISTS Podkategorije(' .
		'user_id int NOT NULL ,' .
		'podcategory_name varchar(30) NOT NULL ,' .
		'category_name varchar(20) NOT NULL ,' .
		'PRIMARY KEY(user_id, category_name, podkategorija_naziv) )'


	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error #5: " . $e->getMessage() ); }
*/



//Ubaci neke korisnike unutra

try
{
	$st = $db->prepare( 'INSERT INTO User ( username, password, email, daily_limit, weekly_limit, monthly_limit, send_mail, registration_sequence, has_registered) VALUES( :username, :password, :email, :daily_limit, :weekly_limit, :mjesecni_limit, :send_mail, :r_s, :h_r)' );

 	$st->execute( array( 'username' => 'sara',   'password' => password_hash( 'sara', PASSWORD_DEFAULT ) ,  'email' => 's@ara.com'  ,  'daily_limit' => '400.00',  'weekly_limit' => '800.00' ,  'mjesecni_limit' => '5100.00', 'send_mail' => '0', 'r_s' => 'abc',  'h_r' => '1' ) );
 	$st->execute( array( 'username' => 'petra',  'password' => password_hash( 'petra', PASSWORD_DEFAULT ),  'email' => 'p@etra.com' ,  'daily_limit' => '370.00',  'weekly_limit' => '980.00' ,  'mjesecni_limit' => '6000.00',  'send_mail' => '0', 'r_s' => 'abc',  'h_r' => '1' ) );
 	$st->execute( array( 'username' => 'paula',  'password' => password_hash( 'paula', PASSWORD_DEFAULT ),  'email' => 'p@aula.com' ,  'daily_limit' => '500.00',  'weekly_limit' => '800.00' ,  'mjesecni_limit' => '5100.00',  'send_mail' => '0', 'r_s' => 'abc',  'h_r' => '1' ) );
 	$st->execute( array( 'username' => 'ana',    'password' => password_hash( 'ana', PASSWORD_DEFAULT )  ,  'email' => 'a@na.com'  ,  'daily_limit' => '400.00',  'weekly_limit' => '900.00' ,  'mjesecni_limit' => '5000.00',  'send_mail' => '0', 'r_s' => 'abc',  'h_r' => '1' ) );
 }
catch( PDOException $e ) { exit( "PDO error [User]: " . $e->getMessage() ); }

echo "Ubacio u tablicu User.<br />";


// Ubaci neke followere unutra (ovo nije bas pametno ovako raditi, preko hardcodiranih id-eva usera)
try
{
	$st = $db->prepare( 'INSERT INTO Category(user_id, category_name, category_type) VALUES (:id, :category, :category_type)' );

	$st->execute( array( 'id' => '1', 'category' => 'Stipendija', 'category_type' => 'Primanja' ) );
	$st->execute( array( 'id' => '2', 'category' => 'Stipendija', 'category_type' => 'Primanja' ) );
	$st->execute( array( 'id' => '3', 'category' => 'Stipendija', 'category_type' => 'Primanja' ) );
 	$st->execute( array( 'id' => '4', 'category' => 'Stipendija', 'category_type' => 'Primanja' ) );
	$st->execute( array( 'id' => '1', 'category' => 'Placa', 'category_type' => 'Primanja' ) );
	$st->execute( array( 'id' => '2', 'category' => 'Placa', 'category_type' => 'Primanja' ) );
	$st->execute( array( 'id' => '3', 'category' => 'Placa', 'category_type' => 'Primanja' ) );
	$st->execute( array( 'id' => '4', 'category' => 'Placa', 'category_type' => 'Primanja' ) );
	$st->execute( array( 'id' => '1', 'category' => 'Racuni', 'category_type' => 'Troskovi' ) );
	$st->execute( array( 'id' => '2', 'category' => 'Racuni', 'category_type' => 'Troskovi' ) );
	$st->execute( array( 'id' => '3', 'category' => 'Racuni', 'category_type' => 'Troskovi' ) );
	$st->execute( array( 'id' => '4', 'category' => 'Racuni', 'category_type' => 'Troskovi' ) );
	$st->execute( array( 'id' => '1', 'category' => 'Hrana', 'category_type' => 'Troskovi' ) );
	$st->execute( array( 'id' => '2', 'category' => 'Hrana', 'category_type' => 'Troskovi' ) );
	$st->execute( array( 'id' => '3', 'category' => 'Hrana', 'category_type' => 'Troskovi' ) );
	$st->execute( array( 'id' => '4', 'category' => 'Hrana', 'category_type' => 'Troskovi' ) );
}
catch( PDOException $e ) { exit( "PDO error [Category]: " . $e->getMessage() ); }

echo "Ubacio u tablicu Category.<br />";


// // Ubaci neke troskove
try
{
	$st = $db->prepare( 'INSERT INTO Expense(category_name, user_id, expense_name, expense_value, expense_date, expense_description )
	VALUES (:category_name, :user_id, :expense_name, :expense_value, :expense_date, :expense_description)' );

	$st->execute( array( 'category_name' => 'Hrana',  'user_id' => '2', 'expense_name' => 'Rucak',     'expense_value' => 60.0,    'expense_date' => '2018-01-12',  /*'ponavljanje_troska' => 0,*/ 'expense_description' => '-' ) );
	$st->execute( array( 'category_name' => 'Racuni', 'user_id' => '1',  'expense_name' => 'Struja',    'expense_value' => 202.0,   'expense_date' => '2018-01-13',  /*'ponavljanje_troska' => 0,*/ 'expense_description' => '-' ) );
	$st->execute( array( 'category_name' => 'Hrana',  'user_id' => '3', 'expense_name' => 'Rucak',     'expense_value' => 160.0,   'expense_date' => '2018-01-14',  /*'ponavljanje_troska' => 0,*/ 'expense_description' => '-' ) );
	$st->execute( array( 'category_name' => 'Hrana',  'user_id' => '4',   'expense_name' => 'Rucak',     'expense_value' => 58.0,    'expense_date' => '2018-01-15',  /*'ponavljanje_troska' => 0,*/ 'expense_description' => '-' ) );
	$st->execute( array( 'category_name' => 'Racuni', 'user_id' => '2', 'expense_name' => 'Internet',  'expense_value' => 243,     'expense_date' => '2018-01-16',  /*'ponavljanje_troska' => 0,*/ 'expense_description' => '-' ) );
	$st->execute( array( 'category_name' => 'Racuni', 'user_id' => '3', 'expense_name' => 'Voda',      'expense_value' => 360.0,   'expense_date' => '2018-02-18',  /*'ponavljanje_troska' => 0,*/ 'expense_description' => '-' ) );
	$st->execute( array( 'category_name' => 'Racuni', 'user_id' => '1',  'expense_name' => 'Voda',      'expense_value' => 256.0,   'expense_date' => '2018-02-13',  /*'ponavljanje_troska' => 0,*/ 'expense_description' => '-' ) );
	$st->execute( array( 'category_name' => 'Hrana',  'user_id' => '1',  'expense_name' => 'Kolaci',    'expense_value' => 70.0,    'expense_date' => '2018-02-14',  /*'ponavljanje_troska' => 0,*/ 'expense_description' => '-' ) );
	$st->execute( array( 'category_name' => 'Hrana',  'user_id' => '4',   'expense_name' => 'Torta',     'expense_value' => 160.0,   'expense_date' => '2018-03-13',  /*'ponavljanje_troska' => 0,*/ 'expense_description' => '-' ) );
	$st->execute( array( 'category_name' => 'Racuni', 'user_id' => '4',   'expense_name' => 'Internet',  'expense_value' => 134.7,   'expense_date' => '2018-04-22',  /*'ponavljanje_troska' => 0,*/ 'expense_description' => '-' ) );
	$st->execute( array( 'category_name' => 'Racuni', 'user_id' => '2', 'expense_name' => 'Internet',  'expense_value' => 167.9,   'expense_date' => '2018-04-12',  /*'ponavljanje_troska' => 0,*/ 'expense_description' => '-' ) );
	$st->execute( array( 'category_name' => 'Hrana',  'user_id' => '1',  'expense_name' => 'Palacinke', 'expense_value' => 34.7,    'expense_date' => '2018-05-10',  /*'ponavljanje_troska' => 0,*/ 'expense_description' => '-' ) );
	$st->execute( array( 'category_name' => 'Hrana',  'user_id' => '3', 'expense_name' => 'Vino',      'expense_value' => 59.9,    'expense_date' => '2018-05-01',  /*'ponavljanje_troska' => 0,*/ 'expense_description' => '-' ) );
	$st->execute( array( 'category_name' => 'Racuni', 'user_id' => '3', 'expense_name' => 'Struja',    'expense_value' => 206.5,   'expense_date' => '2018-03-02',  /*'ponavljanje_troska' => 0,*/ 'expense_description' => '-' ) );
	$st->execute( array( 'category_name' => 'Hrana',  'user_id' => '1',  'expense_name' => 'Krafna',    'expense_value' => 4.99,    'expense_date' => '2018-02-09',  /*'ponavljanje_troska' => 0,*/ 'expense_description' => '-' ) );
	$st->execute( array( 'category_name' => 'Racuni', 'user_id' => '2', 'expense_name' => 'Struja',    'expense_value' => 220.9,   'expense_date' => '2018-05-24',  /*'ponavljanje_troska' => 0,*/ 'expense_description' => '-' ) );
	$st->execute( array( 'category_name' => 'Hrana',  'user_id' => '4',   'expense_name' => 'Kava',      'expense_value' => 19.99,   'expense_date' => '2018-04-27',  /*'ponavljanje_troska' => 0,*/ 'expense_description' => '-' ) );

}
catch( PDOException $e ) { exit( "PDO error [Expense]: " . $e->getMessage() ); }

echo "Ubacio u tablicu Expense.<br />";

// // Ubaci neka primanja
try
{
	$st = $db->prepare( 'INSERT INTO Income(category_name, user_id, income_name, income_value, income_date,  income_description )
	VALUES (:category_name, :user_id, :income_name, :income_value, :income_date, :income_description)' );

	$st->execute( array( 'category_name' => 'Placa', 		 'user_id' => '2',  'income_name' => 'Posao: Konobarenje', 'income_value' => 2260, 'income_date' => '2018-04-17', /*'ponavljanje_primanja' => 0,*/ 'income_description' => 'studentski posao' ) );
	$st->execute( array( 'category_name' => 'Placa', 	   'user_id' => '3',  'income_name' => '-', 'income_value' => 4460, 'income_date' => '2018-04-16', /*'ponavljanje_primanja' => 0,*/ 'income_description' => 'studentski posao' ) );
	$st->execute( array( 'category_name' => 'Stipendija', 'user_id' => '1',   'income_name' => '-', 'income_value' => 1000, 'income_date' => '2018-04-15', /*'ponavljanje_primanja' => 6,*/ 'income_description' => 'stipendija - 4.mj' ) );
	$st->execute( array( 'category_name' => 'Stipendija', 'user_id' => '4',    'income_name' => '-', 'income_value' => 1000, 'income_date' => '2018-04-14', /*'ponavljanje_primanja' => 6,*/ 'income_description' => 'stipendija - 4.mj' ) );
	$st->execute( array( 'category_name' => 'Stipendija', 'user_id' => '2',  'income_name' => '-', 'income_value' => 1000, 'income_date' => '2018-03-15', /*'ponavljanje_primanja' => 6,*/ 'income_description' => 'stipendija - 4.mj' ) );
	$st->execute( array( 'category_name' => 'Placa',      'user_id' => '3',  'income_name' => '-', 'income_value' => 3310, 'income_date' => '2018-03-17', /*'ponavljanje_primanja' => 0,*/ 'income_description' => '-' ) );
	$st->execute( array( 'category_name' => 'Placa',      'user_id' => '1',   'income_name' => '-', 'income_value' => 3360, 'income_date' => '2018-03-14', /*'ponavljanje_primanja' => 0,*/ 'income_description' => '-' ) );
	$st->execute( array( 'category_name' => 'Stipendija', 'user_id' => '4',    'income_name' => '-', 'income_value' => 1000, 'income_date' => '2018-03-15', /*'ponavljanje_primanja' => 0,*/ 'income_description' => 'stipendija -> 3.mj' ) );
	$st->execute( array( 'category_name' => 'Placa',      'user_id' => '2',  'income_name' => '-', 'income_value' => 2260, 'income_date' => '2018-02-14', /*'ponavljanje_primanja' => 0,*/ 'income_description' => '-' ) );
	$st->execute( array( 'category_name' => 'Stipendija', 'user_id' => '3',  'income_name' => '-', 'income_value' => 1000, 'income_date' => '2018-02-11', /*'ponavljanje_primanja' => 0,*/ 'income_description' => 'stipendija - 2.mj' ) );
	$st->execute( array( 'category_name' => 'Placa',      'user_id' => '1',   'income_name' => '-', 'income_value' => 5360, 'income_date' => '2017-09-10', /*'ponavljanje_primanja' => 0,*/ 'income_description' => 'ljetni posao' ) );
	$st->execute( array( 'category_name' => 'Placa',      'user_id' => '4',    'income_name' => '-', 'income_value' => 1600, 'income_date' => '2018-02-17', /*'ponavljanje_primanja' => 0,*/ 'income_description' => '-' ) );
	$st->execute( array( 'category_name' => 'Placa',      'user_id' => '2',  'income_name' => '-', 'income_value' => 2260, 'income_date' => '2018-01-16', /*'ponavljanje_primanja' => 0,*/ 'income_description' => '-' ) );
	$st->execute( array( 'category_name' => 'Placa',      'user_id' => '3',  'income_name' => '-', 'income_value' => 2620, 'income_date' => '2018-01-14', /*'ponavljanje_primanja' => 0,*/ 'income_description' => '-' ) );
	$st->execute( array( 'category_name' => 'Placa',      'user_id' => '1',   'income_name' => '-', 'income_value' => 3360, 'income_date' => '2018-01-13', /*'ponavljanje_primanja' => 0,*/ 'income_description' => '-' ) );
	$st->execute( array( 'category_name' => 'Stipendija', 'user_id' => '4',    'income_name' => '-', 'income_value' => 1000, 'income_date' => '2018-01-12', /*'ponavljanje_primanja' => 0,*/ 'income_description' => 'stipendija - 1.mj' ) );
	$st->execute( array( 'category_name' => 'Stipendija', 'user_id' => '2',  'income_name' => '-', 'income_value' => 1000, 'income_date' => '2018-05-17', /*'ponavljanje_primanja' => 0,*/ 'income_description' => 'stipendija - 5.mj' ) );

}
catch( PDOException $e ) { exit( "PDO error [Income]: " . $e->getMessage() ); }

echo "Ubacio u tablicu Income.<br />";


?>
