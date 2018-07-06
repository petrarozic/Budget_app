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

      if( !preg_match( '/^[A-Za-z0-9_@ ]{3,20}$/', $_POST['new_username'] ) )
  		{
  			$this->registry->template->smessage = "Username should consist of 3-20 letters, numbers and special character _ or @.";
        $_SESSION['flag'] = 0;
  		}
      else{
      $new_username = $_POST['new_username'];
      $this->registry->template->success = $ls->changeUsername( $_SESSION['user_id'], $new_username );
      $_SESSION['flag'] = 1;
      $this->registry->template->smessage = "Username has been successfully changed.";
      }

      $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);
      $this->registry->template->show( 'profile_index' );
      exit();

    }


    function changeEmail(){
      $ls = new BudgetService();

      if( !filter_var( $_POST['new_email'], FILTER_VALIDATE_EMAIL) )
      {
        $this->registry->template->smessage = "Email is not valid.";
        $_SESSION['flag'] = 0;
      }
      else{
        $this->registry->template->success = $ls->changeEmail( $_SESSION['user_id'], $_POST['new_email']);
        $this->registry->template->smessage = "Email has been successfully changed.";
        $_SESSION['flag'] = 1;
      }

      $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);
      $this->registry->template->show( 'profile_index' );


    }




    //password - forgot
    function forgotPassword(){

      $ls = new BudgetService();

      $this->registry->template->l_flag = 1;



      if( $_POST['username_forgot'] === '' ){
        $this->registry->template->lmessage = "You need to type your username.";
        $_SESSION['flag'] = 0;
        $this->registry->template->show( 'login_index' );
  			exit();
      }
      else if( !preg_match( '/^[A-Za-z0-9_@ ]{3,20}$/', $_POST['username_forgot'] ))
      {
        $this->registry->template->lmessage = "Username should consist of letters, numbers and special character _ or @.";
        $_SESSION['flag'] = 0;
        $this->registry->template->show( 'login_index' );
  			exit();
      }
      else if( !$ls->isAlreadyInDB( $_POST['username_forgot'] ) ){
        $this->registry->template->lmessage = "Username is not valid.";
        $_SESSION['flag'] = 0;
        $this->registry->template->show( 'login_index' );
        exit();
      }
      //generirati pass od 8 znakova
      else{
        $id = $ls->getUserId( $_POST['username_forgot'] );
        $email = $ls->getUserEmail( $_POST['username_forgot'] );
        $new_pass = '';
        for( $i = 0; $i < 8; ++$i )
          $new_pass .= chr( rand(0, 25) + ord( 'a' ) );


        //ubaci u bazu

        $this->registry->template->success = $ls->changePassword( $id, $new_pass);
        $this->registry->template->smessage = "New password has been sent to your email.";
        $_SESSION['flag'] = 1;


        $this->registry->template->user = $ls->getUserbById($id);


        //mail
        $to       = $email; //korisnik je unio svoj mail
  			$subject  = 'Password';
  			$message  = 'Dear ' . $_POST['username_forgot'] . ",\nYour new Budget-app password is: ";
  			$message .=  $new_pass . "\n";
  			$headers  = 'From: rp2@studenti.math.hr' . "\r\n" .
  			            'Reply-To: rp2@studenti.math.hr' . "\r\n" .
  			            'X-Mailer: PHP/' . phpversion();

  			mail($to, $subject, $message, $headers);

        $this->registry->template->lmessage = 'Your new password has been sent on yout email.';
        $_SESSION['flag'] = 1;
    		$this->registry->template->l_flag = 1;
        $this->registry->template->show( 'login_index' );
      }

    }

    function changePassword(){

      $new_pass = $_POST['new_pass'];
      $new_pass_repeat = $_POST['new_pass_repeat'];
      $ls = new BudgetService();


      //ubaci u bazu ako su oba unosa jednaka
    if( $new_pass === $new_pass_repeat){

        $this->registry->template->success = $ls->changePassword( $_SESSION['user_id'], $new_pass);
        $this->registry->template->smessage = "Password successfully changed.";
        $_SESSION['flag'] = 1;
        $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);
        $this->registry->template->show( 'profile_index' );
      }
      else{
        //error
        $this->registry->template->lmessage = "Username is not valid.";
        $_SESSION['flag'] = 0;
        $this->registry->template->l_flag = 1;
        $this->registry->template->show( 'login_index' );
        exit();
      }
    }



/*******************************************************************************/
//CHANGE Daily
/*******************************************************************************/

    function changeDaily(){
      $ls = new BudgetService();

      if( !preg_match( '/^[0-9]{1,40}$/', $_POST['new_daily'] ) )
  		{
  			$this->registry->template->smessage = "Limit should consist of numbers, special character .";
        $_SESSION['flag'] = 0;
  		}
      else{
      $new_daily = $_POST['new_daily'];
      $this->registry->template->success = $ls->changeDaily( $_SESSION['user_id'], $new_daily );
      $ls->limits();
      $this->registry->template->smessage = "Daily limit has been successfully changed.";
      $_SESSION['flag'] = 1;
      }

      $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);
      $this->registry->template->show( 'profile_index' );
      exit();

    }

/*******************************************************************************/
//CHANGE Weekly
/*******************************************************************************/

    function changeWeekly(){
      $ls = new BudgetService();

      if( !preg_match( '/^[0-9]{1,40}$/', $_POST['new_weekly'] ) )
  		{
  			$this->registry->template->smessage = "Limit should consist of numbers, special character .";
        $_SESSION['flag'] = 0;
  		}
      else{
      $new_weekly = $_POST['new_weekly'];
      $this->registry->template->success = $ls->changeWeekly( $_SESSION['user_id'], $new_weekly );
      $ls->limits();
      $this->registry->template->smessage = "Weekly limit has been successfully changed.";
      $_SESSION['flag'] = 1;
      }

      $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);
      $this->registry->template->show( 'profile_index' );
      exit();

    }

/*******************************************************************************/
//CHANGE Monthly
/*******************************************************************************/

    function changeMonthly(){
      $ls = new BudgetService();

      if( !preg_match( '/^[0-9]{1,40}$/', $_POST['new_monthly'] ) )
  		{
  			$this->registry->template->smessage = "Limit should consist of numbers, special character .";
        $_SESSION['flag'] = 0;
  		}
      else{
      $new_monthly = $_POST['new_monthly'];
      $this->registry->template->success = $ls->changeMonthly( $_SESSION['user_id'], $new_monthly );
      $ls->limits();
      $this->registry->template->smessage = "Monthly limit has been successfully changed.";
      $_SESSION['flag'] = 1;
      }

      $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);
      $this->registry->template->show( 'profile_index' );
      exit();

    }

    function accountDelete(){
       $ls = new BudgetService();
       $this->registry->template->success = $ls->accountDelete( $_SESSION['user_id'] );
       $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);
       $this->registry->template->smessage = "Account has been successfully deleted.";
       $_SESSION['flag'] = 1;
       $this->registry->template->show( 'login_index' );
       exit();
    }

  };
 ?>
