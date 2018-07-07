<?php

class LoginController extends BaseController
{
	public function index()
	{

		if(isset( $_POST['logout'] ) )
		{
			if ( (!isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG') ){
				unset( $username );
				unset( $user_id );
				session_unset();
				session_destroy();
				$this->registry->template->l_flag = 0;
				$this->registry->template->show( 'login_index' );
			}
			else {
				$this->registry->template->l_flag = 1;
				$this->registry->template->show( 'login_indexCRO' );
			}
		}
		if ( (!isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG') ){
			$this->registry->template->l_flag = 0;
			$this->registry->template->show( 'login_index' );
		}
		else {
			$this->registry->template->l_flag = 1;
			$this->registry->template->show( 'login_indexCRO' );
		}
	}

	public function processLogin()
	{
		$ls = new BudgetService();

		$this->registry->template->smessage = '';
		if(isset( $_POST['username'] ) && isset( $_POST['password']) && $_POST['username'] !== '' && $_POST['password'] !== '')
		{
			$id = $ls->isInDB( $_POST['username'], $_POST['password']);
			if($id === false){
				$this->registry->template->l_flag = 1;
				if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
					$this->registry->template->lmessage = "Username or password incorrect.";
					$_SESSION['flag'] = 0;
					$this->registry->template->show( 'login_index' );
				}
				else if ( $_SESSION['lang'] == 'CRO' ){
					$this->registry->template->lmessage = "Korisničko ime ili lozinka su pogrešni.";
					$_SESSION['flag'] = 0;
					$this->registry->template->show( 'login_indexCRO' );
				}
				exit();
			}

			$_SESSION['user_id'] = $id;
			$_SESSION['username'] = $_POST['username'];

			$ls->limits();

			header( 'Location: ' . __SITE_URL . '/index.php?rt=home');
		}
		else{
			$this->registry->template->l_flag = 1;

			if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
				$this->registry->template->lmessage = "You have to enter username and password to log in.";
				$_SESSION['flag'] = 0;
				$this->registry->template->show( 'login_index' );
			}
			else if ( $_SESSION['lang'] == 'CRO' ){
				$this->registry->template->lmessage = "Molim Vas, upišite i korisničko ime i lozinku.";
				$_SESSION['flag'] = 0;
				$this->registry->template->show( 'login_indexCRO' );
			}
			exit();
		}
	}

	public function processSignUp()
	{
		$ls = new BudgetService();

		$this->registry->template->lmessage = '';
		if( !isset( $_POST['username'] ) || !isset( $_POST['password'] ) || !isset( $_POST['email'] ) || $_POST['username'] === '' || $_POST['password'] === '' || $_POST['email'] === '')
		{

			if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
				$this->registry->template->smessage = "You have to enter username, password and email to sign up.";
				$_SESSION['flag'] = 0;
				$this->registry->template->show( 'login_index' );
			}
			else if ( $_SESSION['lang'] == 'CRO' ){
				$this->registry->template->smessage = "Molim Vas, upišite, korisničko ime, lozinku i email adresu kako biste se registrirali.";
				$_SESSION['flag'] = 0;
				$this->registry->template->show( 'login_indexCRO' );
			}
			exit();
		}

		else if( !preg_match( '/^[A-Za-z0-9_@ ]{3,20}$/', $_POST['username'] ) )
		{
			if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
				$this->registry->template->smessage = "Username should consist of letters and numbers.";
				$_SESSION['flag'] = 0;
				$this->registry->template->show( 'login_index' );
			}
			else if ( $_SESSION['lang'] == 'CRO' ){
				$this->registry->template->smessage = "Korisničko ime se mora sastojati samo od slova i brojeva.";
				$_SESSION['flag'] = 0;
				$this->registry->template->show( 'login_indexCRO' );
			}
			exit();
		}

		else if( !preg_match( '/^[A-Za-z0-9]{3,20}$/', $_POST['password'] ) )
		{
			if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
				$this->registry->template->smessage = "Password should consist only of letters and numbers and be 3-20 characters long.";
				$_SESSION['flag'] = 0;
				$this->registry->template->show( 'login_index' );
			}
			else if ( $_SESSION['lang'] == 'CRO' ){
				$this->registry->template->smessage = "Lozinka mora biti dugačka 3-20 znakova, te se mora sastojati samo od slova i brojeva.";
				$_SESSION['flag'] = 0;
				$this->registry->template->show( 'login_indexCRO' );
			}
			exit();
		}

		else if( !filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL) )
		{
			if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
				$this->registry->template->smessage = "Email is not valid..";
				$_SESSION['flag'] = 0;
				$this->registry->template->show( 'login_index' );
			}
			else if ( $_SESSION['lang'] == 'CRO' ){
				$this->registry->template->smessage = "Unesena email adresa nije valjana.";
				$_SESSION['flag'] = 0;
				$this->registry->template->show( 'login_indexCRO' );
			}
			exit();
		}
		else
		{
			$is = $ls->isAlreadyInDB( $_POST['username']);
			if($is === true)
			{
				if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
					$this->registry->template->smessage = "There is already a user with username ". $_POST['username'] .". Please, choose another username or check if you have already registrated.";
					$_SESSION['flag'] = 0;
					$this->registry->template->show( 'login_index' );
				}
				else if ( $_SESSION['lang'] == 'CRO' ){
					$this->registry->template->smessage = "Već postoji korisnik s unesenim imenom ". $_POST['username'] .". Molim Vas odaberite drugo ime ili provjerite jeste je li već registrirani.";
					$_SESSION['flag'] = 0;
					$this->registry->template->show( 'login_indexCRO' );
				}
				exit();
			}

			$reg_seq = '';
			for( $i = 0; $i < 20; ++$i )
				$reg_seq .= chr( rand(0, 25) + ord( 'a' ) );

			$ls->insertUser( $_POST['username'], $_POST['password'], $_POST['email'], $reg_seq);

			$to       = $_POST['email'];
			$subject  = 'Registration mail';
			$message  = 'Dear ' . $_POST['username'] . ",\nTo complete the registration click on the following link: ";
			$message .= 'http://' . $_SERVER['SERVER_NAME']. __SITE_URL . '/index.php?rt=login/register&sequence='. $reg_seq . "\n";
			$headers  = 'From: rp2@studenti.math.hr' . "\r\n" .
			            'Reply-To: rp2@studenti.math.hr' . "\r\n" .
			            'X-Mailer: PHP/' . phpversion();

			mail($to, $subject, $message, $headers);
			$this->registry->template->output = "Thank you for choosing Budget-app. To complete the registration, click on the link in the email we sent you.";

			$this->registry->template->show( 'login_index' );
			exit();
		}
	}

 public function register(){
	$ls = new BudgetService();

	if( !isset( $_GET['sequence'] ) || !preg_match( '/^[a-z]{20}$/', $_GET['sequence'] ) )
		exit( 'Something is wrong with the sequence.' );

		$ls->findWithSequence( $_GET['sequence']);
		if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' ){
			$this->registry->template->smessage = "Registration has been successfully completed, you can now log in.";
			$this->registry->template->show( 'login_index' );
		}
		else if ( $_SESSION['lang'] == 'CRO' ){
			$this->registry->template->smessage = "Registracija je bila uspiješna, sada se možete prijaviti.";
			$this->registry->template->show( 'login_indexCRO' );
		}
	}
};

?>
