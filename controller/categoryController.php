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

  function index(){
    $ls = new BudgetService();

    $this->registry->template->exp_catList = $ls->getCategoriesById( $_SESSION['user_id'], "Expense" );
    $this->registry->template->inc_catList = $ls->getCategoriesById( $_SESSION['user_id'], "Income" );

    if ( $_SESSION['lang'] == 'CRO' )
      $this->registry->template->show('category_indexCRO');
		else if (!isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' )
      $this->registry->template->show('category_index');


  }

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

      if ( !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' )
        $this->registry->template->message = "Name of category must consist of at most 20 letters or numbers." ;
      if ( $_SESSION['lang'] == 'CRO' )
        $this->registry->template->message = "Ime kategorije mora se sastojati od najviše 20 slova ili brojeva." ;
      $_SESSION['flag'] = 0;
    }
    else if( !isset($_POST['type']) ){
      if ( !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' )
        $this->registry->template->message = "Please, chose type of transaction." ;
      if ( $_SESSION['lang'] == 'CRO' )
        $this->registry->template->message = "Molim Vas, odaberite tip transakcije." ;
      $_SESSION['flag'] = 0;
    }
    else {
      $type = $_POST['type'];
      $test = $ls->addCategory($user_id, $type, $name);
      if($test === false ){
        $_SESSION['flag'] = 0;
        if ( !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' )
          $this->registry->template->message = 'Category with given name already exists.';
        if ( $_SESSION['lang'] == 'CRO' )
          $this->registry->template->message = "Kategorija s odabrenim imenom već postoji." ;
      }
    }

    $this->registry->template->exp_catList = $ls->getCategoriesById( $_SESSION['user_id'], "Expense" );
    $this->registry->template->inc_catList = $ls->getCategoriesById( $_SESSION['user_id'], "Income" );
    if ( $_SESSION['lang'] == 'CRO' )
      $this->registry->template->show('category_indexCRO');
		else if (!isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' )
      $this->registry->template->show('category_index');
    }

  function removeCategory(){
    $category_name = $_POST['name'];
    $category_type = $_POST['type'];
    $ls = new BudgetService();
    $user_id = $_SESSION['user_id'];

    $test = $ls->removeCategory( $user_id, $category_name, $category_type );
    if($test === false){
      $_SESSION['flag'] = 0;
      if ( !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' )
        $this->registry->template->message = 'First you need to delete all transactions with given category name.';
      else if ( $_SESSION['lang'] == 'CRO' )
        $this->registry->template->message = "Prvo obrišite sve transakcije sa izabranom kategorijom." ;

    }
    $this->registry->template->exp_catList = $ls->getCategoriesById( $_SESSION['user_id'], "Expense" );
    $this->registry->template->inc_catList = $ls->getCategoriesById( $_SESSION['user_id'], "Income" );
    if ( $_SESSION['lang'] == 'CRO' )
      $this->registry->template->show('category_indexCRO');
		else if (!isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' )
      $this->registry->template->show('category_index');
    }

  function editCategory(){
    $old_category_name = $_POST['editButtonCa'];
    $category_name = $_POST['name'];
    $category_type = $_POST['type'];
    $ls = new BudgetService();
    $user_id = $_SESSION['user_id'];

    if( !preg_match( '/^[A-Za-z0-9_@\\-\\.\\, ]{1,20}$/' , $category_name ) ){
      if ( !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' )
        $this->registry->template->message = "Name of category cannot be empty and must consist of at most 20 letters or numbers." ;
      else if ( $_SESSION['lang'] == 'CRO' )
        $this->registry->template->message = "Ime kategorije ne može biti prazno. Mora se sastojati od najviše 20 slova ili brojeva." ;

      $_SESSION['flag'] = 0;
    }
    else{
      $this->registry->template->removeCategory = $ls->editCategory( $user_id, $category_name, $category_type, $old_category_name );
    }
    $this->registry->template->exp_catList = $ls->getCategoriesById( $_SESSION['user_id'], "Expense" );
    $this->registry->template->inc_catList = $ls->getCategoriesById( $_SESSION['user_id'], "Income" );

    if ( $_SESSION['lang'] == 'CRO' )
      $this->registry->template->show('category_indexCRO');
		else if (!isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' )
      $this->registry->template->show('category_index');
  }
};



?>
