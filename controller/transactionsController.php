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
      if ( !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' )
        $this->registry->template->show('transactions_index');
      else if ( $_SESSION['lang'] == 'CRO' )
        $this->registry->template->show('transactions_indexCRO');

      }


    function expenses(){
      $ls = new BudgetService();

      $this->registry->template->transactionsList = $ls->getExpensesById($_SESSION['user_id']);
      $this->registry->template->flag = "expense";
      if ( $_SESSION['lang'] == 'CRO' )
        $this->registry->template->show('transactions_indexCRO');
      else if ($_SESSION['lang'] == 'ENG' )
        $this->registry->template->show('transactions_index');
    }


    function removeExpense(){
      $ls = new BudgetService();
      $transaction_id = $_POST['transaction'];

      $this->registry->template->removeExpense = $ls->removeExpense ($_SESSION['user_id'], $transaction_id);

      $this->registry->template->transactionsList = $ls->getExpensesById($_SESSION['user_id']);
      $ls->limits();
      $this->registry->template->flag = "expense";
      if ( $_SESSION['lang'] == 'CRO' )
        $this->registry->template->show('transactions_indexCRO');
      else if ($_SESSION['lang'] == 'ENG' )
        $this->registry->template->show('transactions_index');
    }

    function removeIncome(){
      $ls = new BudgetService();
      $transaction_id = $_POST['income'];

      $this->registry->template->removeIncome = $ls->removeIncome($_SESSION['user_id'], $transaction_id);

      $this->registry->template->transactionsList = $ls->getIncomesById($_SESSION['user_id']);
      $this->registry->template->flag = "income";
      if ( $_SESSION['lang'] == 'CRO' )
        $this->registry->template->show('transactions_indexCRO');
      else if ($_SESSION['lang'] == 'ENG' )
        $this->registry->template->show('transactions_index');    }

    function removeTransaction(){
      $ls = new BudgetService();
      $transaction_id = $_POST['transaction'];
      $type = $_POST['type'];


      if ( $type === "e" )
        $this->registry->template->removeExpense = $ls->removeExpense ($_SESSION['user_id'], $transaction_id);
      else
        $this->registry->template->removeIncome = $ls->removeIncome($_SESSION['user_id'], $transaction_id);


      $this->registry->template->transactionsList = $ls->getTransactionsById($_SESSION['user_id']);
      $ls->limits();
      $this->registry->template->flag = "transactions";
      if ( $_SESSION['lang'] == 'CRO' )
              $this->registry->template->show('transactions_indexCRO');
            else if ($_SESSION['lang'] == 'ENG' )
              $this->registry->template->show('transactions_index');    }


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
        if ( !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' )
          $this->registry->template->message = "Name of transaction must consist of at most 20 letters or numbers." ;
        else if ( $_SESSION['lang'] ==  'CRO')
          $this->registry->template->message = "Ime transakcije mora se sadržavati od najviše 20 slova i ne smije biti prazno." ;



        $_SESSION['flag'] = 0;
      }
      else if( !preg_match( '/^[A-Za-z0-9_@\\-\\.\\, ]{0,50}$/',$description )){
        if ( !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' )
          $this->registry->template->message = "Description of transaction must consist of at most 50 letters or numbers.";
        else if ( $_SESSION['lang'] ==  'CRO')
          $this->registry->template->message = "Opis se treba sadržavati od najvie 50 slova, brojeva ili specijalnih znakova . i -." ;
        $_SESSION['flag'] = 0;
      }
      else if( empty(strtotime($_POST['date'])) ){
        if ( !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' )
          $this->registry->template->message = "Please, chose date.";
        else if ( $_SESSION['lang'] ==  'CRO')
          $this->registry->template->message = "Molim Vas, izaberite datum.";
        $_SESSION['flag'] = 0;
      }
      else if( !isset($_POST['type']) ){
        if ( !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' )
          $this->registry->template->message = "Please, chose type and category.";
        else if ( $_SESSION['lang'] ==  'CRO')
          $this->registry->template->message = "Molim Vas, odaberite tip transakcije i kategoriju.";
        $_SESSION['flag'] = 0;
      }
      else{
        if ( strlen($description) == 0 ){
          $description = "-";
        }
        $type = $_POST['type'];
        $category = $_POST['category'];
        $this->registry->template->addingTransaction = $ls->addTransaction($user_id, $type, $name, $category, $amount, $date, $description, $repeating);
        $ls->limits();
      }


      if ( $_POST['SubmitButton'] == "expense" ){
        $this->registry->template->transactionsList = $ls->getExpensesById( $user_id );
        $this->registry->template->flag = "expense";
        if ( $_SESSION['lang'] == 'CRO' )
          $this->registry->template->show('transactions_indexCRO');
        else if ($_SESSION['lang'] == 'ENG' )
          $this->registry->template->show('transactions_index');      }
      else if ( $_POST['SubmitButton'] == "income" ){
        $this->registry->template->transactionsList = $ls->getIncomesById( $user_id );
        $this->registry->template->flag = "income";
        if ( $_SESSION['lang'] == 'CRO' )
          $this->registry->template->show('transactions_indexCRO');
        else if ($_SESSION['lang'] == 'ENG' )
          $this->registry->template->show('transactions_index');      }
      else if ( $_POST['SubmitButton'] == "transactions" ){
        $this->registry->template->transactionsList = $ls->getTransactionsById( $user_id );
        $this->registry->template->flag = "transactions";
        if ( $_SESSION['lang'] == 'CRO' )
          $this->registry->template->show('transactions_indexCRO');
        else if ($_SESSION['lang'] == 'ENG' )
          $this->registry->template->show('transactions_index');
        }
        else if ( $_POST['SubmitButton'] == "profile" ){
          $this->registry->template->transactionsList = $ls->getUserbById( $user_id );
          if ( $_SESSION['lang'] == 'CRO' )
            $this->registry->template->show('profile_indexCRO');
          else if ($_SESSION['lang'] == 'ENG' )
            $this->registry->template->show('profile_index');
          }
        else if ( $_POST['SubmitButton'] == "category" ){
          $this->registry->template->exp_catList = $ls->getCategoriesById( $_SESSION['user_id'], "Expense" );
          $this->registry->template->inc_catList = $ls->getCategoriesById( $_SESSION['user_id'], "Income" );
          if ( $_SESSION['lang'] == 'CRO' )
            $this->registry->template->show('category_indexCRO');
          else if (!isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' )
            $this->registry->template->show('category_index');
          }
        else if ( $_POST['SubmitButton'] == "statistic" ){
          if ( !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG'  )
            $this->registry->template->show('statistics_index');
          else if ( $_SESSION['lang'] == 'CRO'  )
            $this->registry->template->show('statistics_indexCRO');
          }
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
        if ( !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' )
          $this->registry->template->message = "Name of transaction must consist of at most 20 letters or numbers." ;
        else if ( $_SESSION['lang'] ==  'CRO')
          $this->registry->template->message = "Ime transakcije mora se sadržavati od najviše 20 slova i ne smije biti prazno." ;
        $_SESSION['flag'] = 0;
        }
      else if( !preg_match( '/^[A-Za-z0-9_@\\-\\.\\, ]{0,50}$/',$description )){
        if ( !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' )
          $this->registry->template->message = "Description of transaction must consist of at most 50 letters or numbers.";
        else if ( $_SESSION['lang'] ==  'CRO')
          $this->registry->template->message = "Opis se treba sadržavati od najvie 50 slova, brojeva ili specijalnih znakova . i -." ;
        $_SESSION['flag'] = 0;
      }
      else if( empty(strtotime($_POST['date'])) ){
        $this->registry->template->message = "You must chose date.";
        $_SESSION['flag'] = 0;
      }
      else{
        if ( strlen($description) == 0 ){
          $description = "-";
        }
        $ls->editingTransaction($user_id, $type, $transaction_id, $name, $category, $amount, $date, $description);
        $ls->limits();
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

      if ( $_SESSION['lang'] == 'CRO' )
        $this->registry->template->show('transactions_indexCRO');
      else if ($_SESSION['lang'] == 'ENG' )
        $this->registry->template->show('transactions_index');
    }

  };
 ?>
