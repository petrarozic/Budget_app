<?php


require_once '../../model/db.class.php';

$db = DB::getConnection();

//tablica Korisnik
try
{
	$st = $db->prepare(
		'CREATE TABLE IF NOT EXISTS Korisnik (' .
		'id_korisnika int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
		'username varchar(20) NOT NULL,' .
		'password varchar(20) NOT NULL,' .
		'email varchar(50) ,' .					//korisnik ne moraunijeti e-mail adresu		
		'dnevni_limit int, ' .
		'tjedni_limit int, ' .
		'mjesečni_limit int )'
	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error #1: " . $e->getMessage() ); }

//tablica Troškovi

try
{
	$st = $db->prepare(
		'CREATE TABLE IF NOT EXISTS Troškovi (' .
		'id_troška int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
		'kategorija_naziv varchar(20) NOT NULL,' .
		'korisničko_ime varchar(20) NOT NULL,' .
		'naziv_troška varchar(30) NOT NULL,'.
		'iznos_troška int NOT NULL,' .
		'datum_troška date NOT NULL,' .												
		'ponavljanje_troška int ,' .
		'opis_troška varchar(50) )'

	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error #2: " . $e->getMessage() ); }



//tablica primanja

try
{
	$st = $db->prepare(
		'CREATE TABLE IF NOT EXISTS Primanja (' .
		'id_primanja int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
		'kategorija_naziv varchar(20) NOT NULL,' .
		'korisničko_ime varchar(20) NOT NULL,' .
		'naziv_primanja varchar(30) NOT NULL,'.
		'iznos_primanja int NOT NULL,' .
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
		'CREATE TABLE IF NOT EXISTS Kategorija (' .
		'id_korisnika int NOT NULL' .
		'kategorija_naziv varchar(20) NOT NULL ,' .			
		'vrsta varchar(20) NOT NULL)'. 													
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





?>
