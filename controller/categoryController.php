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

class categoryController extends BaseController
{

  function index(){}

  function CategoryForSelect(){
    $ls = new BudgetService();

    if ( isset( $_GET['tip']) ){
      $tip = $_GET['tip'];

    $message = $ls->getCategoriesById($_SESSION['user_id'], $tip);


    sendJSONandExit( $message );
    }
  }

  function addCategory(){
    $ls = new BudgetService();
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];

    if( !preg_match( '/^[A-Za-z0-9_@\\-\\.\\, ]{1,20}$/' , $name )){
      $this->registry->template->message = "Name of category must consist of at most 20 letters or numbers." ;
      $_SESSION['flag'] = 0;
    }
    else if( !isset($_POST['type']) ){
      $this->registry->template->message = "Please, chose type of transaction." ;
      $_SESSION['flag'] = 0;
    }
    else {
      $type = $_POST['type'];
      $this->registry->template->addingTransaction = $ls->addCategory($user_id, $type, $name);
    }

    if ( $_POST['SubmitButton'] == "expense" ){
      $this->registry->template->transactionsList = $ls->getExpensesById( $user_id );
      $this->registry->template->flag = "expense";
      $this->registry->template->show('transactions_index');
    }
    else if ( $_POST['SubmitButton'] == "income" ){
      $this->registry->template->transactionsList = $ls->getIncomesById( $user_id );
      $this->registry->template->flag = "income";
      $this->registry->template->show('transactions_index');
    }
    else if ( $_POST['SubmitButton'] == "transactions" ){
      $this->registry->template->transactionsList = $ls->getTransactionsById( $user_id );
      $this->registry->template->flag = "transactions";
      $this->registry->template->show('transactions_index');
    }
    else if ( $_POST['SubmitButton'] == "profile"){
      $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);
      $this->registry->template->show('profile_index');
    }
    else if ( $_POST['SubmitButton'] == "statistics"){
/* Ovo nije dobro */
      $list = $ls->getExpensesById($_SESSION['user_id']);
      $total = 0;
      $biggest = 0;
      foreach($list as $t){
        $total += $t->expense_value;
        if($t->expense_value > $biggest)
            $biggest = $t->expense_value;
      }
      $this->registry->template->total = $total;
      $this->registry->template->average = sprintf('%0.2f', $total/sizeof($list));
      $this->registry->template->apd = sprintf('%0.2f', $total/date("d"));
      $this->registry->template->biggest = $biggest;
      $this->registry->template->show('statistics_index');

    }
  }

  function removeCategory( $category_name, $category_type ){
    $ls = new BudgetService();
    $user_id = $_SESSION['user_id'];

    $this->registry->template->removeCategory = $ls->removeCategory( $user_id, $category_name, $category_type );

    $this->registry->template->transactionsList = $ls->getCategoriesById( $user_id, $category_type );
    $this->registry->template->show('category_index');
  }

  function editCategory( $category_name, $category_type ){
    $ls = new BudgetService();
    $user_id = $_SESSION['user_id'];

    if( !preg_match( '/^[A-Za-z0-9_@\\-\\.\\, ]{1,20}$/' , $name )){
      $this->registry->template->message = "Name of category cannot be empty and must consist of at most 20 letters or numbers." ;
      $_SESSION['flag'] = 0;
    }
    else{
      $this->registry->template->removeCategory = $ls->editCategory( $user_id, $category_name, $category_type );
      $this->registry->template->categoryList = $ls->getCategoriesById( $user_id, $category_type );
    }
    $this->registry->template->show('category_index');

  }
};



?>
