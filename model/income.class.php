<?php

  /********************************
   * Primanja korisnika

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
  class Income {
    protected $income_id, $category_name, $user_id,
        $income_name, $income_value, $income_date,  $income_description;

    function __construct($income_id, $category_name, $user_id,
        $income_name, $income_value, $income_date,  $income_description) {

      $this->income_id = $income_id;
      $this->category_name = $category_name;
      $this->user_id = $user_id;
      $this->income_name = $income_name;
      $this->income_value = $income_value;
      $this->income_date = $income_date;
      $this->income_description = $income_description;
    }

    function __get( $variable ){
      return $this->$variable;
    }

    function __set( $variable, $value ){
      $this->$variable = $value;
      return $this;
    }
  };

 ?>
