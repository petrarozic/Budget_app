<?php

  class profileController extends BaseController{

    function index(){
      $ls = new BudgetService();

      $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);

      $this->registry->template->show('profile_index');
    }

// anlogno daily, weekly, monthly ....

    function changeEmail(){
      $ls = new BudgetService();

      $new_email = $_POST['']
    }

    //..
  };
 ?>
