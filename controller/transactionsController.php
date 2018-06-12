<?php

  class transactionsController extends BaseController{

    function index(){}


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

    function removeTransaction(){
      $ls = new BudgetService();
      // $transaction_id;
      $this->registry->template->removeTransaction = $ls->removeTransaction($_SESSION['user_id']);
      $this->registry->template->show('transactions_index');
    }


  };
 ?>
