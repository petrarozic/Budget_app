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

      if( !filter_var( $_POST['new_email'], FILTER_VALIDATE_EMAIL) )
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
    //..
  };
 ?>
