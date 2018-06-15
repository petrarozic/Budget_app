<?php

  class profileController extends BaseController{

    function index(){
      $ls = new BudgetService();

      $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);

      $this->registry->template->show('profile_index');
    }

// anlogno daily, weekly, monthly ....
    function changeUsername(){

      $ls = new BudgetService();

      if( !preg_match( '/^[A-Za-z0-9_@ ]{1,40}$/', $_POST['new_username'] ) )
  		{
  			$this->registry->template->smessage = "Username should consist of letters, numbers and special character _ or @.";
  		}
      else{
      $new_username = $_POST['new_username'];
      $this->registry->template->success = $ls->changeUsername( $_SESSION['user_id'], $new_username );
      $this->registry->template->smessage = "Username has been successfully changed.";
      }

      $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);
      $this->registry->template->show( 'profile_index' );
      exit();
      //$this->registry->template->show('profile_index');
    }


    function changeEmail(){
      $ls = new BudgetService();

      if( !filter_var( $_POST[''], FILTER_VALIDATE_EMAIL) )
      {
        $this->registry->template->smessage = "Email is not valid.";
      }
      else{
        $this->registry->template->success = $ls->changeEmail( $_SESSION['user_id'], $_POST['new_email']);
        $this->registry->template->smessage = "Email has been successfully changed.";
      }

      $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);
      $this->registry->template->show( 'profile_index' );

    
    }


   

    //password - forgot
    function forgotPassword(){
      //generirati pass od 8 znakova

      $new_pass = '';
      for( $i = 0; $i < 8; ++$i )
        $new_pass .= chr( rand(0, 25) + ord( 'a' ) );
     

      //ubaci u bazu
    
      $ls = new BudgetService();

      $this->registry->template->success = $ls->changePassword( $_SESSION['user_id'], $new_pass);
      $this->registry->template->smessage = "New password has been sent to your email.";
    

      $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);
      
   
      //mail
      $to       = 'email'; //e-mail dobiti iz baze 
			$subject  = 'Password';
			$message  = 'Dear ' . $_SESSION['username'] . ",\nYour new Budget-app password is: ";
			$message .=  $new_pass . "\n";
			$headers  = 'From: rp2@studenti.math.hr' . "\r\n" .
			            'Reply-To: rp2@studenti.math.hr' . "\r\n" .
			            'X-Mailer: PHP/' . phpversion();

			mail($to, $subject, $message, $headers);  

      $this->registry->template->show( 'profile_index' );

    }

    function changePassword(){
      //generirati pass od 8 znakova

      $new_pass = $_POST['new_pass'];
      $new_pass_repeat = $_POST['new_pass_repeat'];
     


      //ubaci u bazu ako su oba unosa jednaka
    if( $new_pass === $new_pass_repeat){
        $ls = new BudgetService();
        $this->registry->template->success = $ls->changePassword( $_SESSION['user_id'], $new_pass);
        $this->registry->template->smessage = "Password successfully changed.";
        $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);
        $this->registry->template->show( 'profile_index' );
      }
      else{
        //error
      }
    }


    function changeDaily(){
      $ls = new BudgetService();

      if( !preg_match( '/^[0-9]{1,40}$/', $_POST['new_daily'] ) )
  		{
  			$this->registry->template->smessage = "Limit should consist of numbers, special character .";
  		}
      else{
      $new_daily = $_POST['new_daily'];
      $this->registry->template->success = $ls->changeDaily( $_SESSION['user_id'], $new_daily );
      $this->registry->template->smessage = "Daily limit has been successfully changed.";
      }

      $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);
      $this->registry->template->show( 'profile_index' );
      exit();
    
    }

    function changeWeekly(){
      $ls = new BudgetService();

      if( !preg_match( '/^[0-9]{1,40}$/', $_POST['new_weekly'] ) )
  		{
  			$this->registry->template->smessage = "Limit should consist of numbers, special character .";
  		}
      else{
      $new_weekly = $_POST['new_weekly'];
      $this->registry->template->success = $ls->changWeekly( $_SESSION['user_id'], $new_weekly );
      $this->registry->template->smessage = "Weekly limit has been successfully changed.";
      }

      $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);
      $this->registry->template->show( 'profile_index' );
      exit();
    
    }

    function changeMonthly(){
      $ls = new BudgetService();

      if( !preg_match( '/^[0-9]{1,40}$/', $_POST['new_monthly'] ) )
  		{
  			$this->registry->template->smessage = "Limit should consist of numbers, special character .";
  		}
      else{
      $new_monthly = $_POST['new_monthly'];
      $this->registry->template->success = $ls->changeMonthly( $_SESSION['user_id'], $new_monthly );
      $this->registry->template->smessage = "Monthly limit has been successfully changed.";
      }

      $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);
      $this->registry->template->show( 'profile_index' );
      exit();
    
    }

    function accountDelete(){
      
    }

  };
 ?>
