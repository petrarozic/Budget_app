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

  class profileController extends BaseController{

    function index(){
      $ls = new BudgetService();

      $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);

      if ( !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' )
        $this->registry->template->show('profile_index');
      else if ( $_SESSION['lang'] == 'CRO' )
        $this->registry->template->show('profile_indexCRO');
    }

// anlogno daily, weekly, monthly ....
    function changeUsername(){

      $ls = new BudgetService();

      if( !preg_match( '/^[A-Za-z0-9_@ ]{3,20}$/', $_POST['new_username'] ) )
  		{
        if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
					$this->registry->template->smessage = "Username should consist of 3-20 letters, numbers and special character _ or @.";
					$_SESSION['flag'] = 0;
					}
				else if ( $_SESSION['lang'] == 'CRO' ){
					$this->registry->template->smessage = "Korisničko ime treba sadržavati  3-20 slova, brojeva ili specijanih znakova.";
					$_SESSION['flag'] = 0;
				}
  		}
      else{
        $new_username = $_POST['new_username'];
        $this->registry->template->success = $ls->changeUsername( $_SESSION['user_id'], $new_username );
        $_SESSION['flag'] = 1;
        if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
          $this->registry->template->smessage = "Username has been successfully changed.";
          }
        else if ( $_SESSION['lang'] == 'CRO' ){
          $this->registry->template->smessage = "Korisničko ime je uspiješno promijenjeno.";
        }
      }

      $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);

      if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
        $this->registry->template->show( 'profile_index' );
        }
      else if ( $_SESSION['lang'] == 'CRO' ){
        $this->registry->template->show( 'profile_indexCRO' );

      exit();
    }}


    function changeEmail(){
      $ls = new BudgetService();

      if( !filter_var( $_POST['new_email'], FILTER_VALIDATE_EMAIL) )
      {
        if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
					$this->registry->template->smessage = "Email is not valid.";
					$_SESSION['flag'] = 0;
					}
				else if ( $_SESSION['lang'] == 'CRO' ){
					$this->registry->template->smessage = "Unesena email adrea nije valjana.";
					$_SESSION['flag'] = 0;
				}
      }
      else{
        $this->registry->template->success = $ls->changeEmail( $_SESSION['user_id'], $_POST['new_email']);
        if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
          $this->registry->template->smessage = "Email has been successfully changed.";
          }
        else if ( $_SESSION['lang'] == 'CRO' ){
          $this->registry->template->smessage = "Email adresa je uspiješno promijenjena.";
        }
        $_SESSION['flag'] = 1;
      }

      $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);
      if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
        $this->registry->template->show( 'profile_index' );
        }
      else if ( $_SESSION['lang'] == 'CRO' ){
        $this->registry->template->show( 'profile_indexCRO' );
      }

    }




    //password - forgot
    function forgotPassword(){

      $ls = new BudgetService();

      $this->registry->template->l_flag = 1;

      if( $_POST['username_forgot'] === '' ){
        $_SESSION['flag'] = 0;
        if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
          $this->registry->template->lmessage = "You need to type your username.";
          $this->registry->template->show( 'login_index' );
        }
        else if( $_SESSION['lang'] == 'CRO' ){
          $this->registry->template->lmessage = "Unesite korisničko ime.";
          $this->registry->template->show( 'login_indexCRO' );
        }
  			exit();
      }
      else if( !preg_match( '/^[A-Za-z0-9_@ ]{3,20}$/', $_POST['username_forgot'] ))
      {
        $_SESSION['flag'] = 0;
        if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
          $this->registry->template->lmessage = "Username should consist of letters, numbers and special character _ or @.";
          $this->registry->template->show( 'login_index' );
        }
        else if( $_SESSION['lang'] == 'CRO' ){
          $this->registry->template->lmessage = "Korisničko ime se treba sastojati od slova, brojeva i specijalnih znakova.";
          $this->registry->template->show( 'login_indexCRO' );
        }
  			exit();
      }
      else if( !$ls->isAlreadyInDB( $_POST['username_forgot'] ) ){
        $_SESSION['flag'] = 0;
        if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
          $this->registry->template->lmessage = "Username is not valid.";
          $this->registry->template->show( 'login_index' );
        }
        else if( $_SESSION['lang'] == 'CRO' ){
          $this->registry->template->lmessage = "Korisničko ime nije valjano.";
          $this->registry->template->show( 'login_indexCRO' );
        }
        exit();
      }
      //generirati pass od 8 znakova
      else{
        $id = $ls->getUserId( $_POST['username_forgot'] );
        $email = $ls->getUserEmail( $_POST['username_forgot'] );
        $new_pass = '';
        for( $i = 0; $i < 8; ++$i )
          $new_pass .= chr( rand(0, 25) + ord( 'a' ) );


        //ubaci u bazu

        $this->registry->template->success = $ls->changePassword( $id, $new_pass);
        $_SESSION['flag'] = 1;


        $this->registry->template->user = $ls->getUserbById($id);

        //mail
        $to       = $email; //korisnik je unio svoj mail
        $headers  = 'From: rp2@studenti.math.hr' . "\r\n" .
                    'Reply-To: rp2@studenti.math.hr' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
        if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
          $subject  = 'Password';
    			$message  = 'Dear ' . $_POST['username_forgot'] . ",\nYour new Budget-app password is: ";
        }
        else if( $_SESSION['lang'] == 'CRO' ){
          $subject  = 'Lozinka';
          $message  = 'Poštovani, ' . $_POST['username_forgot'] . ",\nVaša nova Budget-app lozinka je: ";
        }
  			$message .=  $new_pass . "\n";

  			mail($to, $subject, $message, $headers);

        $_SESSION['flag'] = 1;
        $this->registry->template->l_flag = 1;
        if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
          $this->registry->template->lmessage = 'Your new password has been sent on yout email.';
          $this->registry->template->show( 'login_index' );
        }
        else if( $_SESSION['lang'] == 'CRO' ){
          $this->registry->template->lmessage = "Nova lozinka je poslana na Vašu email adresu.";
          $this->registry->template->show( 'login_indexCRO' );
        }
      }
    }

    function changePassword(){

      $new_pass = $_POST['new_pass'];
      $new_pass_repeat = $_POST['new_pass_repeat'];
      $ls = new BudgetService();


      //ubaci u bazu ako su oba unosa jednaka
    if( $new_pass === $new_pass_repeat){

        $this->registry->template->success = $ls->changePassword( $_SESSION['user_id'], $new_pass);
        $_SESSION['flag'] = 1;
        $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);

        if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
          $this->registry->template->smessage = "Password successfully changed.";
          $this->registry->template->show( 'profile_index' );
        }
        else if( $_SESSION['lang'] == 'CRO' ){
          $this->registry->template->smessage = "Lozinka uspiješno promijenjena.";
          $this->registry->template->show( 'profile_indexCRO' );
        }

      }
      else{
        //error
        $_SESSION['flag'] = 0;
        $this->registry->template->l_flag = 1;
        $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);

        if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
          $this->registry->template->smessage = "Password is not valid.";
          $this->registry->template->show( 'profile_index' );
        }
        else if( $_SESSION['lang'] == 'CRO' ){
          $this->registry->template->smessage = "Lozinka nije valjana.";
          $this->registry->template->show( 'profile_indexCRO' );
        }
        exit();
      }
    }



/*******************************************************************************/
//CHANGE Daily
/*******************************************************************************/

    function changeDaily(){
      $ls = new BudgetService();

      if( !preg_match( '/^[0-9]{1,40}$/', $_POST['new_daily'] ) )
  		{
        $_SESSION['flag'] = 0;
        if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
          $this->registry->template->smessage = "Limit should consist of numbers, special character .";
        }
        else if( $_SESSION['lang'] == 'CRO' ){
          $this->registry->template->smessage = "Limit se treba sastojati od brojeva i specijalnog znaka . .";
        }
  	  }
      else{
        $new_daily = $_POST['new_daily'];
        $this->registry->template->success = $ls->changeDaily( $_SESSION['user_id'], $new_daily );
        $ls->limits();

        if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
          $this->registry->template->smessage = "Daily limit has been successfully changed.";
        }
        else if( $_SESSION['lang'] == 'CRO' ){
          $this->registry->template->smessage = "Dnevni limit je uspiješno promijenjen.";
        }
        $_SESSION['flag'] = 1;
      }

      $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);

      if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
        $this->registry->template->show( 'profile_index' );
      }
      else if( $_SESSION['lang'] == 'CRO' ){
        $this->registry->template->show( 'profile_indexCRO' );
      }
      exit();

    }

/*******************************************************************************/
//CHANGE Weekly
/*******************************************************************************/

    function changeWeekly(){
      $ls = new BudgetService();

      if( !preg_match( '/^[0-9]{1,40}$/', $_POST['new_weekly'] ) )
  		{
        if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
          $this->registry->template->smessage = "Limit should consist of numbers, special character .";
        }
        else if( $_SESSION['lang'] == 'CRO' ){
          $this->registry->template->smessage = "Limit se treba sastojati od brojeva i specijalnog znaka . .";
        }
        $_SESSION['flag'] = 0;
  		}
      else{
      $new_weekly = $_POST['new_weekly'];
      $this->registry->template->success = $ls->changeWeekly( $_SESSION['user_id'], $new_weekly );
      $ls->limits();


      if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
        $this->registry->template->smessage = "Weekly limit has been successfully changed.";
      }
      else if( $_SESSION['lang'] == 'CRO' ){
        $this->registry->template->smessage = "Tjedni limit je uspiješno promijenjenn.";
      }
      $_SESSION['flag'] = 1;
      }

      $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);

      if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
        $this->registry->template->show( 'profile_index' );
      }
      else if( $_SESSION['lang'] == 'CRO' ){
        $this->registry->template->show( 'profile_indexCRO' );
      }
      exit();

    }

/*******************************************************************************/
//CHANGE Monthly
/*******************************************************************************/

    function changeMonthly(){
      $ls = new BudgetService();

      if( !preg_match( '/^[0-9]{1,40}$/', $_POST['new_monthly'] ) )
  		{
        if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
          $this->registry->template->smessage = "Limit should consist of numbers, special character .";
        }
        else if( $_SESSION['lang'] == 'CRO' ){
          $this->registry->template->smessage = "Limit se treba sastojati od brojeva i specijalnog znaka . .";
        }
        $_SESSION['flag'] = 0;
  		}
      else{
        $new_monthly = $_POST['new_monthly'];
        $this->registry->template->success = $ls->changeMonthly( $_SESSION['user_id'], $new_monthly );
        $ls->limits();

        if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
          $this->registry->template->smessage = "Monthly limit has been successfully changed.";
        }
        else if( $_SESSION['lang'] == 'CRO' ){
          $this->registry->template->smessage = "Mjesečni limit je uspiješno promijenjen.";
        }
        $_SESSION['flag'] = 1;
      }

      $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);
      if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
        $this->registry->template->show( 'profile_index' );
      }
      else if( $_SESSION['lang'] == 'CRO' ){
        $this->registry->template->show( 'profile_indexCRO' );
      }
      exit();

    }
/*******************************************************************************/
//PROMJENA send_mail U BAZI
/*******************************************************************************/
    function changeCheckbox(){
      $ls = new BudgetService();

      $ls->changeCheckbox( $_SESSION['user_id'] );

      if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
        $this->registry->template->show( 'profile_index' );
      }
      else if( $_SESSION['lang'] == 'CRO' ){
        $this->registry->template->show( 'profile_indexCRO' );
      }



    }
    //funkcija za slanje maila kada je prekoracen limit


    function accountDelete(){
       $ls = new BudgetService();
       $this->registry->template->success = $ls->accountDelete( $_SESSION['user_id'] );
       $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);
       $_SESSION['flag'] = 1;


       if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
         $this->registry->template->smessage = "Account has been successfully deleted.";
         $this->registry->template->show( 'login_index' );
       }
       else if( $_SESSION['lang'] == 'CRO' ){
         $this->registry->template->smessage = "Račun je uspiješno izbrisan.";
         $this->registry->template->show( 'login_indexCRO' );
       }
       exit();
    }

  };
 ?>
