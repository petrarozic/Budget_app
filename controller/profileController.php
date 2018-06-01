<?php

  class profileController extends BaseController{

    function index(){
      $ls = new BudgetService();

      $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);

      $this->registry->template->show('profile_index');
    }

// anlogno daily, weekly, monthly ....

    function changEmail(){
      $ls = new BudgetService();

      $new_email = $_POST[''];
      // Napraviti i ipozvati funkciju u modelu koja verificira i postavlja novi email u bazu
      //$this->registry->template->success = $ls->changeEmail( $_SESSION['user_id'], $_POST['new_email']);

      //$this->registry->template->show('profile_index');
    }
    //..
  };
 ?>
