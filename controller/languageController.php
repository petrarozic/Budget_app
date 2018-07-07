<?php

class languageController extends BaseController
{

  function index(){}

  function goToLoginCRO(){
    $this->registry->template->l_flag = 1;
    $this->registry->template->show( 'login_indexCRO' );
  }

  function goToLoginENG(){
    $this->registry->template->l_flag = 1;
    $this->registry->template->show( 'login_index' );
  }

  function goToCROPage(){

    $ls = new BudgetService();

    switch ($_SESSION['page']) {
      case 'Profile': {
          $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);
          $this->registry->template->show( 'profile_indexCRO' );
          break;
        }
      case 'expense' : {
          $this->registry->template->flag = 'expense';
          $this->registry->template->transactionsList = $ls->getExpensesById($_SESSION['user_id']);
          $this->registry->template->show( 'transactions_indexCRO' );
          break;
        }
      case 'income' : {
          $this->registry->template->flag = 'income';
          $this->registry->template->transactionsList = $ls->getIncomesById($_SESSION['user_id']);
          $this->registry->template->show( 'transactions_indexCRO' );
          break;
        }
      case 'transactions' : {
          $this->registry->template->flag = 'transactions';
          $this->registry->template->transactionsList = $ls->getTransactionsById($_SESSION['user_id']);
          $this->registry->template->show( 'transactions_indexCRO' );
          break;
        }
      case 'Category' : {
          $this->registry->template->exp_catList = $ls->getCategoriesById( $_SESSION['user_id'], "Expense" );
          $this->registry->template->inc_catList = $ls->getCategoriesById( $_SESSION['user_id'], "Income" );
          $this->registry->template->show( 'category_indexCRO' );
          break;
        }
      case 'statistics' : {
          $this->registry->template->show( 'statistics_indexCRO' );
          break;
        }

    }
  }

  function goToENGPage(){
    $ls = new BudgetService();

    switch ($_SESSION['page']){
      case 'expense' : {
          $this->registry->template->transactionsList = $ls->getExpensesById($_SESSION['user_id']);
          $this->registry->template->flag = 'expense';
          $this->registry->template->show( 'transactions_index' );
          break;
        }
      case 'income' : {
          $this->registry->template->flag = 'income';
          $this->registry->template->transactionsList = $ls->getIncomesById($_SESSION['user_id']);
          $this->registry->template->show( 'transactions_index' );
          break;
        }
      case 'transactions' : {
          $this->registry->template->flag = 'transactions';
          $this->registry->template->transactionsList = $ls->getTransactionsById($_SESSION['user_id']);
          $this->registry->template->show( 'transactions_index' );
          break;
        }
      case 'Profile' : {
          $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);
          $this->registry->template->show( 'profile_index' );
          break;
        }
      case 'Category' : {
          $this->registry->template->exp_catList = $ls->getCategoriesById( $_SESSION['user_id'], "Expense" );
          $this->registry->template->inc_catList = $ls->getCategoriesById( $_SESSION['user_id'], "Income" );
          $this->registry->template->show( 'category_index' );
          break;
        }
      case 'statistics' : {
          $this->registry->template->show( 'statistics_index' );
          break;
        }
    }
  }

};



?>
