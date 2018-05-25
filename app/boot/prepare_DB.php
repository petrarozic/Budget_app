<?php


require_once '../../Model/db.class.php';

$db = DB::getConnection();

//tablica Korisnik
try
{
	$st = $db->prepare(
		'CREATE TABLE IF NOT EXISTS Korisnik (' .
		'id_korisnika int NOT NULL PRIMARY KEY AUTO_INCREMENT, ' .
		'username varchar(20) NOT NULL, ' .
		'password varchar(225) NOT NULL, ' .
		'email varchar(50), ' .					//korisnik ne moraunijeti e-mail adresu
		'dnevni_limit double, ' .
		'tjedni_limit double, ' .
		'mjesečni_limit double, '.
		'registration_sequence varchar(20) NOT NULL, ' .
		'has_registered int )'
	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error #1: " . $e->getMessage() ); }

// u Troškovima i Primanjima umjesto username, staviti user_id

//tablica Troškovi

try
{
	$st = $db->prepare(
		'CREATE TABLE IF NOT EXISTS Troškovi (' . // Expense
		'id_troška int NOT NULL PRIMARY KEY AUTO_INCREMENT,' . //expense_id
		'kategorija_naziv varchar(20) NOT NULL,' .	// category_name
		'korisničko_ime varchar(20) NOT NULL,' . // username
		'naziv_troška varchar(30) NOT NULL,'. // expense_name
		'iznos_troška double NOT NULL,' .	// expense_value
		'datum_troška date NOT NULL,' . // date
	//	'ponavljanje_troška int ,' .	izbacujemo broj_ponavljanja iz baze i ostavljamo to da se odradi uz formu i for petlju
		'opis_troška varchar(50) )' 	// expense_description

	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error #2: " . $e->getMessage() ); }



//tablica primanja

try
{
	$st = $db->prepare(
		'CREATE TABLE IF NOT EXISTS Primanja (' . 	// Income - analogno troškovima
		'id_primanja int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
		'kategorija_naziv varchar(20) NOT NULL,' .
		'korisničko_ime varchar(20) NOT NULL,' .
		'naziv_primanja varchar(30) NOT NULL,'.
		'iznos_primanja double NOT NULL,' .
		'datum_primanja date NOT NULL,' .
		'ponavljanje_primanja int ,' .
		'opis_primanja  varchar(50) )'
	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error #3: " . $e->getMessage() ); }

//kategorije

try
{
	$st = $db->prepare(
		'CREATE TABLE IF NOT EXISTS Kategorija (' .	// Category
		'id_korisnika int NOT NULL ,' .			// user_id
		'kategorija_naziv varchar(20) NOT NULL ,' .	// category_name
		'vrsta varchar(20) NOT NULL,'.	// category_type
		'PRIMARY KEY(id_korisnika, kategorija_naziv) )'
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
		'id_korisnika int NOT NULL ,' .
		'podkategorija_naziv varchar(30) NOT NULL ,' .
		'kategorija_naziv varchar(20) NOT NULL ,' .
		'PRIMARY KEY(id_korisnika, kategorija_naziv, podkategorija_naziv) )'


	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error #5: " . $e->getMessage() ); }
*/



//Ubaci neke korisnike unutra

try
{
	$st = $db->prepare( 'INSERT INTO Korisnik ( username, password, email, dnevni_limit, tjedni_limit, mjesečni_limit, registration_sequence, has_registered) VALUES( :username, :password, :email, :dnevni_limit, :tjedni_limit, :mjesecni_limit, :r_s, :h_r)' );

 	$st->execute( array( 'username' => 'sara',   'password' => password_hash( 'sara', PASSWORD_DEFAULT ) ,  'email' => 's@ara.com'  ,  'dnevni_limit' => '400.00',  'tjedni_limit' => '800.00' ,  'mjesecni_limit' => '5100.00',  'r_s' => 'abc',  'h_r' => '1' ) );
 	$st->execute( array( 'username' => 'petra',  'password' => password_hash( 'petra', PASSWORD_DEFAULT ),  'email' => 'p@etra.com' ,  'dnevni_limit' => '370.00',  'tjedni_limit' => '980.00' ,  'mjesecni_limit' => '6000.00',  'r_s' => 'abc',  'h_r' => '1' ) );
 	$st->execute( array( 'username' => 'paula',  'password' => password_hash( 'paula', PASSWORD_DEFAULT ),  'email' => 'p@aula.com' ,  'dnevni_limit' => '500.00',  'tjedni_limit' => '800.00' ,  'mjesecni_limit' => '5100.00',  'r_s' => 'abc',  'h_r' => '1' ) );
 	$st->execute( array( 'username' => 'ana',    'password' => password_hash( 'ana', PASSWORD_DEFAULT )  ,  'email' => 'a@na.com'  ,  'dnevni_limit' => '400.00',  'tjedni_limit' => '900.00' ,  'mjesecni_limit' => '5000.00',  'r_s' => 'abc',  'h_r' => '1' ) );
 }
catch( PDOException $e ) { exit( "PDO error [Korisnik]: " . $e->getMessage() ); }

echo "Ubacio u tablicu Korisnik.<br />";


// Ubaci neke followere unutra (ovo nije bas pametno ovako raditi, preko hardcodiranih id-eva usera)
try
{
	$st = $db->prepare( 'INSERT INTO Kategorija(id_korisnika, kategorija_naziv, vrsta) VALUES (:id, :kategorija, :vrsta)' );

	$st->execute( array( 'id' => '1', 'kategorija' => 'Stipendija', 'vrsta' => 'Primanja' ) );
	$st->execute( array( 'id' => '2', 'kategorija' => 'Stipendija', 'vrsta' => 'Primanja' ) );
	$st->execute( array( 'id' => '3', 'kategorija' => 'Stipendija', 'vrsta' => 'Primanja' ) );
 	$st->execute( array( 'id' => '4', 'kategorija' => 'Stipendija', 'vrsta' => 'Primanja' ) );
	$st->execute( array( 'id' => '1', 'kategorija' => 'Placa', 'vrsta' => 'Primanja' ) );
	$st->execute( array( 'id' => '2', 'kategorija' => 'Placa', 'vrsta' => 'Primanja' ) );
	$st->execute( array( 'id' => '3', 'kategorija' => 'Placa', 'vrsta' => 'Primanja' ) );
	$st->execute( array( 'id' => '4', 'kategorija' => 'Placa', 'vrsta' => 'Primanja' ) );
	$st->execute( array( 'id' => '1', 'kategorija' => 'Racuni', 'vrsta' => 'Troskovi' ) );
	$st->execute( array( 'id' => '2', 'kategorija' => 'Racuni', 'vrsta' => 'Troskovi' ) );
	$st->execute( array( 'id' => '3', 'kategorija' => 'Racuni', 'vrsta' => 'Troskovi' ) );
	$st->execute( array( 'id' => '4', 'kategorija' => 'Racuni', 'vrsta' => 'Troskovi' ) );
	$st->execute( array( 'id' => '1', 'kategorija' => 'Hrana', 'vrsta' => 'Troskovi' ) );
	$st->execute( array( 'id' => '2', 'kategorija' => 'Hrana', 'vrsta' => 'Troskovi' ) );
	$st->execute( array( 'id' => '3', 'kategorija' => 'Hrana', 'vrsta' => 'Troskovi' ) );
	$st->execute( array( 'id' => '4', 'kategorija' => 'Hrana', 'vrsta' => 'Troskovi' ) );
}
catch( PDOException $e ) { exit( "PDO error [kategorije]: " . $e->getMessage() ); }

echo "Ubacio u tablicu Kategorija.<br />";


// // Ubaci neke troškove
try
{
	$st = $db->prepare( 'INSERT INTO Troškovi(kategorija_naziv, korisničko_ime, naziv_troška, iznos_troška, datum_troška, ponavljanje_troška, opis_troška )
	VALUES (:kategorija_naziv, :korisnicko_ime, :naziv_troska, :iznos_troska, :datum_troska, :ponavljanje_troska, :opis_troska)' );

	$st->execute( array( 'kategorija_naziv' => 'Hrana',  'korisnicko_ime' => 'petra', 'naziv_troska' => 'Ručak',     'iznos_troska' => 60.0,    'datum_troska' => '2018-01-12',  'ponavljanje_troska' => 0, 'opis_troska' => '-' ) );
	$st->execute( array( 'kategorija_naziv' => 'Računi', 'korisnicko_ime' => 'sara',  'naziv_troska' => 'Struja',    'iznos_troska' => 202.0,   'datum_troska' => '2018-01-13',  'ponavljanje_troska' => 0, 'opis_troska' => '-' ) );
	$st->execute( array( 'kategorija_naziv' => 'Hrana',  'korisnicko_ime' => 'paula', 'naziv_troska' => 'Ručak',     'iznos_troska' => 160.0,   'datum_troska' => '2018-01-14',  'ponavljanje_troska' => 0, 'opis_troska' => '-' ) );
	$st->execute( array( 'kategorija_naziv' => 'Hrana',  'korisnicko_ime' => 'ana',   'naziv_troska' => 'Ručak',     'iznos_troska' => 58.0,    'datum_troska' => '2018-01-15',  'ponavljanje_troska' => 0, 'opis_troska' => '-' ) );
	$st->execute( array( 'kategorija_naziv' => 'Računi', 'korisnicko_ime' => 'petra', 'naziv_troska' => 'Internet',  'iznos_troska' => 243,     'datum_troska' => '2018-01-16',  'ponavljanje_troska' => 0, 'opis_troska' => '-' ) );
	$st->execute( array( 'kategorija_naziv' => 'Računi', 'korisnicko_ime' => 'paula', 'naziv_troska' => 'Voda',      'iznos_troska' => 360.0,   'datum_troska' => '2018-02-18',  'ponavljanje_troska' => 0, 'opis_troska' => '-' ) );
	$st->execute( array( 'kategorija_naziv' => 'Računi', 'korisnicko_ime' => 'sara',  'naziv_troska' => 'Voda',      'iznos_troska' => 256.0,   'datum_troska' => '2018-02-13',  'ponavljanje_troska' => 0, 'opis_troska' => '-' ) );
	$st->execute( array( 'kategorija_naziv' => 'Hrana',  'korisnicko_ime' => 'sara',  'naziv_troska' => 'Kolači',    'iznos_troska' => 70.0,    'datum_troska' => '2018-02-14',  'ponavljanje_troska' => 0, 'opis_troska' => '-' ) );
	$st->execute( array( 'kategorija_naziv' => 'Hrana',  'korisnicko_ime' => 'ana',   'naziv_troska' => 'Torta',     'iznos_troska' => 160.0,   'datum_troska' => '2018-03-13',  'ponavljanje_troska' => 0, 'opis_troska' => '-' ) );
	$st->execute( array( 'kategorija_naziv' => 'Računi', 'korisnicko_ime' => 'ana',   'naziv_troska' => 'Internet',  'iznos_troska' => 134.7,   'datum_troska' => '2018-04-22',  'ponavljanje_troska' => 0, 'opis_troska' => '-' ) );
	$st->execute( array( 'kategorija_naziv' => 'Računi', 'korisnicko_ime' => 'petra', 'naziv_troska' => 'Internet',  'iznos_troska' => 167.9,   'datum_troska' => '2018-04-12',  'ponavljanje_troska' => 0, 'opis_troska' => '-' ) );
	$st->execute( array( 'kategorija_naziv' => 'Hrana',  'korisnicko_ime' => 'sara',  'naziv_troska' => 'Palačinke', 'iznos_troska' => 34.7,    'datum_troska' => '2018-05-10',  'ponavljanje_troska' => 0, 'opis_troska' => '-' ) );
	$st->execute( array( 'kategorija_naziv' => 'Hrana',  'korisnicko_ime' => 'paula', 'naziv_troska' => 'Vino',      'iznos_troska' => 59.9,    'datum_troska' => '2018-05-01',  'ponavljanje_troska' => 0, 'opis_troska' => '-' ) );
	$st->execute( array( 'kategorija_naziv' => 'Računi', 'korisnicko_ime' => 'paula', 'naziv_troska' => 'Struja',    'iznos_troska' => 206.5,   'datum_troska' => '2018-03-02',  'ponavljanje_troska' => 0, 'opis_troska' => '-' ) );
	$st->execute( array( 'kategorija_naziv' => 'Hrana',  'korisnicko_ime' => 'sara',  'naziv_troska' => 'Krafna',    'iznos_troska' => 4.99,    'datum_troska' => '2018-02-09',  'ponavljanje_troska' => 0, 'opis_troska' => '-' ) );
	$st->execute( array( 'kategorija_naziv' => 'Računi', 'korisnicko_ime' => 'petra', 'naziv_troska' => 'Struja',    'iznos_troska' => 220.9,   'datum_troska' => '2018-05-24',  'ponavljanje_troska' => 0, 'opis_troska' => '-' ) );
	$st->execute( array( 'kategorija_naziv' => 'Hrana',  'korisnicko_ime' => 'ana',   'naziv_troska' => 'Kava',      'iznos_troska' => 19.99,   'datum_troska' => '2018-04-27',  'ponavljanje_troska' => 0, 'opis_troska' => '-' ) );

}
catch( PDOException $e ) { exit( "PDO error [troškovi]: " . $e->getMessage() ); }

echo "Ubacio u tablicu Troškovi.<br />";

// // Ubaci neka primanja
try
{
	$st = $db->prepare( 'INSERT INTO Primanja(kategorija_naziv, korisničko_ime, naziv_primanja, iznos_primanja, datum_primanja, ponavljanje_primanja, opis_primanja )
	VALUES (:kategorija_naziv, :korisnicko_ime, :naziv_primanja, :iznos_primanja, :datum_primanja, :ponavljanje_primanja, :opis_primanja)' );

	$st->execute( array( 'kategorija_naziv' => 'Plaća', 		 'korisnicko_ime' => 'petra',  'naziv_primanja' => '', 'iznos_primanja' => 2260, 'datum_primanja' => '2018-04-17', 'ponavljanje_primanja' => 0, 'opis_primanja' => 'studentski posao' ) );
	$st->execute( array( 'kategorija_naziv' => 'Plaća', 	   'korisnicko_ime' => 'paula',  'naziv_primanja' => '', 'iznos_primanja' => 4460, 'datum_primanja' => '2018-04-16', 'ponavljanje_primanja' => 0, 'opis_primanja' => 'studentski posao' ) );
	$st->execute( array( 'kategorija_naziv' => 'Stipendija', 'korisnicko_ime' => 'sara',   'naziv_primanja' => '', 'iznos_primanja' => 1000, 'datum_primanja' => '2018-04-15', 'ponavljanje_primanja' => 6, 'opis_primanja' => 'stipendija - 4.mj' ) );
	$st->execute( array( 'kategorija_naziv' => 'Stipendija', 'korisnicko_ime' => 'ana',    'naziv_primanja' => '', 'iznos_primanja' => 1000, 'datum_primanja' => '2018-04-14', 'ponavljanje_primanja' => 6, 'opis_primanja' => 'stipendija - 4.mj' ) );
	$st->execute( array( 'kategorija_naziv' => 'Stipendija', 'korisnicko_ime' => 'petra',  'naziv_primanja' => '', 'iznos_primanja' => 1000, 'datum_primanja' => '2018-03-15', 'ponavljanje_primanja' => 6, 'opis_primanja' => 'stipendija - 4.mj' ) );
	$st->execute( array( 'kategorija_naziv' => 'Plaća',      'korisnicko_ime' => 'paula',  'naziv_primanja' => '', 'iznos_primanja' => 3310, 'datum_primanja' => '2018-03-17', 'ponavljanje_primanja' => 0, 'opis_primanja' => '' ) );
	$st->execute( array( 'kategorija_naziv' => 'Plaća',      'korisnicko_ime' => 'sara',   'naziv_primanja' => '', 'iznos_primanja' => 3360, 'datum_primanja' => '2018-03-14', 'ponavljanje_primanja' => 0, 'opis_primanja' => '' ) );
	$st->execute( array( 'kategorija_naziv' => 'Stipendija', 'korisnicko_ime' => 'ana',    'naziv_primanja' => '', 'iznos_primanja' => 1000, 'datum_primanja' => '2018-03-15', 'ponavljanje_primanja' => 0, 'opis_primanja' => 'stipendija -> 3.mj' ) );
	$st->execute( array( 'kategorija_naziv' => 'Plaća',      'korisnicko_ime' => 'petra',  'naziv_primanja' => '', 'iznos_primanja' => 2260, 'datum_primanja' => '2018-02-14', 'ponavljanje_primanja' => 0, 'opis_primanja' => '' ) );
	$st->execute( array( 'kategorija_naziv' => 'Stipendija', 'korisnicko_ime' => 'paula',  'naziv_primanja' => '', 'iznos_primanja' => 1000, 'datum_primanja' => '2018-02-11', 'ponavljanje_primanja' => 0, 'opis_primanja' => 'stipendija - 2.mj' ) );
	$st->execute( array( 'kategorija_naziv' => 'Plaća',      'korisnicko_ime' => 'sara',   'naziv_primanja' => '', 'iznos_primanja' => 5360, 'datum_primanja' => '2017-09-10', 'ponavljanje_primanja' => 0, 'opis_primanja' => 'ljetni posao' ) );
	$st->execute( array( 'kategorija_naziv' => 'Plaća',      'korisnicko_ime' => 'ana',    'naziv_primanja' => '', 'iznos_primanja' => 1600, 'datum_primanja' => '2018-02-17', 'ponavljanje_primanja' => 0, 'opis_primanja' => '' ) );
	$st->execute( array( 'kategorija_naziv' => 'Plaća',      'korisnicko_ime' => 'petra',  'naziv_primanja' => '', 'iznos_primanja' => 2260, 'datum_primanja' => '2018-01-16', 'ponavljanje_primanja' => 0, 'opis_primanja' => '' ) );
	$st->execute( array( 'kategorija_naziv' => 'Plaća',      'korisnicko_ime' => 'paula',  'naziv_primanja' => '', 'iznos_primanja' => 2620, 'datum_primanja' => '2018-01-14', 'ponavljanje_primanja' => 0, 'opis_primanja' => '' ) );
	$st->execute( array( 'kategorija_naziv' => 'Plaća',      'korisnicko_ime' => 'sara',   'naziv_primanja' => '', 'iznos_primanja' => 3360, 'datum_primanja' => '2018-01-13', 'ponavljanje_primanja' => 0, 'opis_primanja' => '' ) );
	$st->execute( array( 'kategorija_naziv' => 'Stipendija', 'korisnicko_ime' => 'ana',    'naziv_primanja' => '', 'iznos_primanja' => 1000, 'datum_primanja' => '2018-01-12', 'ponavljanje_primanja' => 0, 'opis_primanja' => 'stipendija - 1.mj' ) );
	$st->execute( array( 'kategorija_naziv' => 'Stipendija', 'korisnicko_ime' => 'petra',  'naziv_primanja' => '', 'iznos_primanja' => 1000, 'datum_primanja' => '2018-05-17', 'ponavljanje_primanja' => 0, 'opis_primanja' => 'stipendija - 5.mj' ) );

}
catch( PDOException $e ) { exit( "PDO error [troškovi]: " . $e->getMessage() ); }

echo "Ubacio u tablicu Primanja.<br />";


?>
