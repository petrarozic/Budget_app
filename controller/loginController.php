<?php

class LoginController extends BaseController
{
	public function index()
	{

		if(isset( $_POST['logout'] ) )
		{
			unset( $username );
			unset( $user_id );
			session_unset();
			session_destroy();
		}
		//$this->registry->template->lmessage = '';
		//$this->registry->template->smessage = '';
		$this->registry->template->l_flag = 1;

		if(  !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG' )
				$this->registry->template->show( 'login_index' );
		else if ( $_SESSION['lang'] == 'CRO' )
			$this->registry->template->show( 'login_indexCRO' );	}

	public function processLogin()
	{
		$ls = new BudgetService();

		$this->registry->template->smessage = '';
		if(isset( $_POST['username'] ) && isset( $_POST['password']) && $_POST['username'] !== '' && $_POST['password'] !== '')
		{
			$id = $ls->isInDB( $_POST['username'], $_POST['password']);
			if($id === false){
				$this->registry->template->l_flag = 1;
				$this->registry->template->lmessage = "Username or password incorrect.";
				$_SESSION['flag'] = 0;
				$this->registry->template->show( 'login_index' );
				exit();
			}

			$_SESSION['user_id'] = $id;
			$_SESSION['username'] = $_POST['username'];

			$ls->limits();

			header( 'Location: ' . __SITE_URL . '/index.php?rt=home');
		}
		else{
			$this->registry->template->l_flag = 1;
			$this->registry->template->lmessage = "You have to enter username and password to log in.";
			$_SESSION['flag'] = 0;
			$this->registry->template->show( 'login_index' );
			exit();
		}
	}

	public function processSignUp()
	{
		$ls = new BudgetService();

		$this->registry->template->lmessage = '';
		if( !isset( $_POST['username'] ) || !isset( $_POST['password'] ) || !isset( $_POST['email'] ) || $_POST['username'] === '' || $_POST['password'] === '' || $_POST['email'] === '')
		{
			$this->registry->template->smessage = "You have to enter username, password and email to sign up.";
			$_SESSION['flag'] = 0;
			$this->registry->template->show( 'login_index' );
			exit();
		}

		else if( !preg_match( '/^[A-Za-z0-9_@ ]{3,20}$/', $_POST['username'] ) )
		{
			$this->registry->template->smessage = "Username should consist of letters and numbers.";
			$_SESSION['flag'] = 0;
			$this->registry->template->show( 'login_index' );
			exit();
		}

		else if( !preg_match( '/^[A-Za-z0-9]{3,20}$/', $_POST['password'] ) )
		{
			$this->registry->template->smessage = "Password should consist only of letters and numbers and be 3-20 characters long.";
			$_SESSION['flag'] = 0;
			$this->registry->template->show( 'login_index' );
			exit();
		}

		else if( !filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL) )
		{
			$this->registry->template->smessage = "Email is not valid.";
			$_SESSION['flag'] = 0;
			$this->registry->template->show( 'login_index' );
			exit();
		}
		else
		{
			$is = $ls->isAlreadyInDB( $_POST['username']);
			if($is === true)
			{
				$this->registry->template->smessage = "There is already a user with username ". $_POST['username'] .". Please, choose another username or check if you have already registrated.";
				$_SESSION['flag'] = 0;
				$this->registry->template->show( 'login_index' );
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
		$this->registry->template->output = "Registration has been successfully completed, you can now log in.";
		$this->registry->template->show( 'login_index' );
	}
};

?>
