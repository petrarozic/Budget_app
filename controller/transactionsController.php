<?php

  class transactionsController extends BaseController{

    function index(){
      $ls = new BudgetService();

      $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);

      $this->registry->template->show('profile_index');
    }


    function incomes(){
      $ls = new BudgetService();

      $this->registry->template->transactionsList = $ls->getIncomeById($_SESSION['user_id']);
      $this->registry->template->flag = "income";
      $this->registry->template->show('transactions_index');
    }


    function expenses(){
      $ls = new BudgetService();

      $this->registry->template->transactionsList = $ls->getExpenseById($_SESSION['user_id']);
      $this->registry->template->flag = "expense";
      $this->registry->template->show('transactions_index');
    }

  
  };
 ?>
