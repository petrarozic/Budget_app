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

    function removeExpense(){
      $ls = new BudgetService();
      $transaction_id = $_POST['transaction'];

      $this->registry->template->removeExpense = $ls->removeExpense ($_SESSION['user_id'], $transaction_id);

      $this->registry->template->transactionsList = $ls->getExpenseById($_SESSION['user_id']);
      $this->registry->template->flag = "expense";
      $this->registry->template->show('transactions_index');
    }

    function removeIncome(){
      $ls = new BudgetService();
      $transaction_id = $_POST['income'];

      $this->registry->template->removeIncome = $ls->removeIncome($_SESSION['user_id'], $transaction_id);

      $this->registry->template->transactionsList = $ls->getIncomeById($_SESSION['user_id']);
      $this->registry->template->flag = "income";
      $this->registry->template->show('transactions_index');
    }

    function removeTransaction(){
      $ls = new BudgetService();
      $transaction_id = $_POST['transaction'];
      $type = $_POST['type'];


      if ( $type === "e" )
        $this->registry->template->removeExpense = $ls->removeExpense ($_SESSION['user_id'], $transaction_id);
      else
        $this->registry->template->removeIncome = $ls->removeIncome($_SESSION['user_id'], $transaction_id);


      $this->registry->template->transactionsList = $ls->getTransactionById($_SESSION['user_id']);
      $this->registry->template->flag = "transactions";
      $this->registry->template->show('transactions_index');
    }



  };
 ?>
