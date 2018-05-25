<?php

  /********************************
   * Primanje korisnika


   PITANJE: korisnicko_ime --> user ?
   datum --> date --> da ga ne primamo nego ga tu u konstruktoru izracunamo?

ATRIBUTI
   'id_primanja int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
   'kategorija_naziv varchar(20) NOT NULL,' .
   'korisničko_ime varchar(20) NOT NULL,' .
   'naziv_primanja varchar(30) NOT NULL,'.
   'iznos_primanja double NOT NULL,' .
   'datum_primanja date NOT NULL,' .
   'ponavljanje_primanja int ,' .
   'opis_primanja  varchar(50) )'
   ********************************/
  class Receiving extends AnotherClass
  {

    protected $id, $category, $user, $name, $amount, $date, $repetition, $description;
    function __construct($id, $category, $user, $name, $amount, $date, $repetition, $description)
    {
      $this->id = $id; 
      $this->category = $category;
      $this->user = $user;
      $this->name = $name;
      $this->amount = $amount;
      $this->date = $date;
      $this->repetition = $repetition;
      $this->description = $description;
    }
  }







 ?>
