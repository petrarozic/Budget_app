<?php

  /********************************
   * Sve transakcije korisnika
   ********************************/
   
  class Transaction{
    protected $tr_id, $category_name, $user_id,
        $tr_name, $tr_value, $tr_date,  $tr_description, $tr_type;

    function __construct($tr_id, $category_name, $user_id,
        $tr_name, $tr_value, $tr_date,  $tr_description, $tr_type) {

      $this->tr_id = $tr_id;
      $this->category_name = $category_name;
      $this->user_id = $user_id;
      $this->tr_name = $tr_name;
      $this->tr_value = $tr_value;
      $this->tr_date = $tr_date;
      $this->tr_description = $tr_description;
      $this->tr_type = $tr_type;
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
