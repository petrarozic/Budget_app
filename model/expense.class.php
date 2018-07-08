<?php

  /********************************
   * Troškovi korisnika

   ATRIBUTI
      'expense_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
      'category_name varchar(20) NOT NULL,' .
      'user_id int NOT NULL,'.
      'expense_name varchar(30) NOT NULL,'.
      'expense_value double NOT NULL,' .
      'expense_date date NOT NULL,' .
      'expense_description varchar(50)
   ********************************/
   
  class Expense {
    protected $expense_id, $category_name, $user_id,
        $expense_name, $expense_value, $expense_date,  $expense_description;

    function __construct($expense_id, $category_name, $user_id,
        $expense_name, $expense_value, $expense_date,  $expense_description) {

      $this->expense_id = $expense_id;
      $this->category_name = $category_name;
      $this->user_id = $user_id;
      $this->expense_name = $expense_name;
      $this->expense_value = $expense_value;
      $this->expense_date = $expense_date;
      $this->expense_description = $expense_description;
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
