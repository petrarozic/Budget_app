<?php

  /* ATRIBUTI

  'id_korisnika int NOT NULL PRIMARY KEY AUTO_INCREMENT, ' .
  'username varchar(20) NOT NULL, ' .
  'password varchar(225) NOT NULL, ' .
  'email varchar(50), ' .					//korisnik ne moraunijeti e-mail adresu
  'dnevni_limit double, ' .
  'tjedni_limit double, ' .
  'mjesecni_limit double, '.
  'registration_sequence varchar(20) NOT NULL, ' .
  'has_registered int )'
  */

  class User{
    protected $user_id, $username, $password, $email, $weekly_limit, $daily_limit, $monthly_limit, $send_mail, $registration_sequence, $has_registered;

    function __construct($user_id_, $username_, $password_, $email_, $daily_limit_,
                        $weekly_limit_, $monthly_limit_, $send_mail_, $registration_sequence_, $has_registered_){
      $this->user_id                = $user_id_;
      $this->username               = $username_;
      $this->password               = $password_;
      $this->email                  = $email_;
      $this->daily_limit            = $daily_limit_;
      $this->weekly_limit           = $weekly_limit_;
      $this->monthly_limit          = $monthly_limit_;
      $this->send_mail              = $send_mail_;
      $this->registration_sequence  = $registration_sequence_;
      $this->has_registered         = $has_registered_;
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
