<?php
  class User{
    protected $user_id, $username, $password, $email, $daily_limit, $monthly_limit, $registration_sequence, $has_registered;

    function __construct($user_id_, $username_, $password_, $email_, $daily_limit_,
                        $monthly_limit_, $registration_sequence_, $has_registered_){
      $this->user_id                = $user_id;
      $this->username               = $username_;
      $this->password               = $password_;
      $this->email                  = $email_;
      $this->daily_limit            = $daily_limit_;
      $this->monthly_limit          = $monthly_limit_;
      $this->registration_sequence  = $registration_sequence_;
      $this->has_registered         = $has_registered_;
    }

/*
    function __construct($user_id_, $username_, $password_, $email_, $registration_sequence_, $has_registered_){
      $this->user_id                = $user_id;
      $this->username               = $username_;
      $this->password               = $password_;
      $this->email                  = $email_;
      $this->registration_sequence  = $registration_sequence_;
      $this->has_registered         = $has_registered_;
    }
*/

    function __get( $variable ) {
      return $this->$variable;
    }

    function __set( $variable, $value ) {
       $this->$variable = $value;
       return $this;
    }

  }
 ?>
