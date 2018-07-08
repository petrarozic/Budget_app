<?php

  /*ATRIBUTI

  'id_korisnika int NOT NULL ,' .
  'kategorija_naziv varchar(20) NOT NULL ,' .
  'vrsta varchar(20) NOT NULL,'.
  'PRIMARY KEY(id_korisnika, kategorija_naziv) )'
  */

  class Category{
    protected $user_id, $category_name, $category_type;

    function __construct($user_id_, $category_name_, $category_type_) {
      $this->user_id        = $user_id_;
      $this->category_name  = $category_name_;
      $this->category_type  = $category_type_;
    }

    function __get( $variable ) {
      return $this->$variable;
    }

    function __set( $variable, $value ) {
       $this->$variable = $value;
       return $this;
    }

  }
 ?>
