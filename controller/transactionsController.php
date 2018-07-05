<?php

function sendJSONandExit( $message )
{
    header( 'Content-type:application/json;charset=utf-8' );
    echo json_encode( $message );
    flush();
    exit( 0 );
}

function sendErrorAndExit( $messageText )
{
  $message = [];
  $message[ 'error' ] = $messageText;

  sendJSONandExit( $message );
}


  class transactionsController extends BaseController{

    function index(){}


    function incomes(){
      $ls = new BudgetService();

      $this->registry->template->transactionsList = $ls->getIncomesById($_SESSION['user_id']);
      $this->registry->template->flag = "income";
      $this->registry->template->show('transactions_index');
    }


    function expenses(){
      $ls = new BudgetService();

      $this->registry->template->transactionsList = $ls->getExpensesById($_SESSION['user_id']);
      $this->registry->template->flag = "expense";
      $this->registry->template->show('transactions_index');
    }

    function removeExpense(){
      $ls = new BudgetService();
      $transaction_id = $_POST['transaction'];

      $this->registry->template->removeExpense = $ls->removeExpense ($_SESSION['user_id'], $transaction_id);

      $this->registry->template->transactionsList = $ls->getExpensesById($_SESSION['user_id']);
      $this->registry->template->flag = "expense";
      $this->registry->template->show('transactions_index');
    }

    function removeIncome(){
      $ls = new BudgetService();
      $transaction_id = $_POST['income'];

      $this->registry->template->removeIncome = $ls->removeIncome($_SESSION['user_id'], $transaction_id);

      $this->registry->template->transactionsList = $ls->getIncomesById($_SESSION['user_id']);
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


      $this->registry->template->transactionsList = $ls->getTransactionsById($_SESSION['user_id']);
      $this->registry->template->flag = "transactions";
      $this->registry->template->show('transactions_index');
    }

    function CategoryForSelect(){

      $ls = new BudgetService();

      if ( isset( $_GET['tip']) ){
        $tip = $_GET['tip'];

      $message = $ls->getCategoriesById($_SESSION['user_id'], $tip);


      sendJSONandExit( $message );
      }
    }

    function addTransaction(){

      $ls = new BudgetService();

      $user_id = $_SESSION['user_id'];


      $name = $_POST['name'];
      $amount = $_POST['amount'];
      $date = $_POST['date'];
      $description = $_POST['description'];
      $repeating = $_POST['repeating'];

      // Validating data : name, description ( other are specified with form )


      if( !preg_match( '/^[A-Za-z0-9_@\\-\\.\\, ]{1,20}$/' , $name )){
        $this->registry->template->message = "Name of transaction must consist of at most 20 letters or numbers." ;
      }
      else if( !preg_match( '/^[A-Za-z0-9_@\\-\\.\\, ]{0,50}$/',$description )){
        $this->registry->template->message = "Description of transaction must consist of at most 50 letters or numbers.";
      }
      else if( empty(strtotime($_POST['date'])) ){
        $this->registry->template->message = "Please, chose date.";
      }
      else{
        if ( strlen($description) == 0 ){
          $description = "-";
        }
        $type = $_POST['type'];
        $category = $_POST['category'];
        $this->registry->template->addingTransaction = $ls->addTransaction($user_id, $type, $name, $category, $amount, $date, $description, $repeating);
      }


      if ( $_POST['SubmitButton'] == "expense" ){
        $this->registry->template->transactionsList = $ls->getExpensesById( $user_id );
        $this->registry->template->flag = "expense";
      }
      else if ( $_POST['SubmitButton'] == "income" ){
        $this->registry->template->transactionsList = $ls->getIncomesById( $user_id );
        $this->registry->template->flag = "income";
      }
      else if ( $_POST['SubmitButton'] == "transactions" ){
        $this->registry->template->transactionsList = $ls->getTransactionsById( $user_id );
        $this->registry->template->flag = "transactions";
      }

      $this->registry->template->show('transactions_index');
    }


    function getTransactionById(){

      $ls = new BudgetService();

      $user_id = $_SESSION['user_id'];

      if (isset( $_GET['id'] ) && isset($_GET['type'])){
        $transaction_id = $_GET['id'];
        $type = $_GET['type'];

        // trazim ili income ili expense, a imam user_id i tran_id
        $transaction = $ls->getTransactionById( $user_id, $transaction_id, $type);

        sendJSONandExit( $transaction );
      }
    }

    function editTransaction(){

      $ls = new BudgetService();

      $transaction_id = $_POST['TranId'];
      $name = $_POST['name'];
      $category = $_POST['category'];
      $type = $_POST['type'];
      $date = $_POST['date'];
      $amount = $_POST['amount'];
      $description = $_POST['description'];
      $user_id = $_SESSION['user_id'];

      if( !preg_match( '/^[A-Za-z0-9_@\\-\\.\\, ]{1,20}$/' , $name )){
        $this->registry->template->message = "Name of transaction cannot be empty and must consist of at most 20 letters or numbers." ;
      }
      else if( !preg_match( '/^[A-Za-z0-9_@\\-\\.\\, ]{0,50}$/',$description )){
        $this->registry->template->message = "Description of transaction must consist of at most 50 letters or numbers.";
      }
      else if( empty(strtotime($_POST['date'])) ){
        $this->registry->template->message = "You must chose date.";
      }
      else{
        if ( strlen($description) == 0 ){
          $description = "-";
        }
        $ls->editingTransaction($user_id, $type, $transaction_id, $name, $category, $amount, $date, $description);
      }

      if ( $_POST['editButton'] == "expense" ){
        $this->registry->template->transactionsList = $ls->getExpensesById( $user_id );
        $this->registry->template->flag = "expense";
      }
      else if ( $_POST['editButton'] == "income" ){
        $this->registry->template->transactionsList = $ls->getIncomesById( $user_id );
        $this->registry->template->flag = "income";
      }
      else if ( $_POST['editButton'] == "transactions" ){
        $this->registry->template->transactionsList = $ls->getTransactionsById( $user_id );
        $this->registry->template->flag = "transactions";
      }

      $this->registry->template->show('transactions_index');

    }

  };
 ?>
