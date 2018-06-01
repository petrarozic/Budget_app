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
		$this->registry->template->show( 'login_sig_log' );
	}

	public function obradaLogina()
	{
		$ls = new BudgetService();

		if(isset( $_POST['username'] ) && isset( $_POST['password'] ))
		{
			$id = $ls->isInDB( $_POST['username'], $_POST['password']);
			if($id === false){
				header( 'Location: ' . __SITE_URL . '/index.php?rt=login');
				exit();
			}

			$_SESSION['user_id'] = $id;
			$_SESSION['username'] = $_POST['username'];
			$this->registry->template->show( '_table' );
		}
		else{
			header( 'Location: ' . __SITE_URL . '/index.php?rt=login');
			exit();
		}
	}
	public function obradaSignUpa()
	{
		$ls = new BudgetService();

		if( !isset( $_POST['username'] ) || !isset( $_POST['password'] ) || !isset( $_POST['email'] ) )
		{
			$this->registry->template->show( '_header' );
			header( 'Location: ' . __SITE_URL . '/index.php?rt=login');
			exit();
		}

		if( !preg_match( '/^[A-Za-z0-9_]{1,20}$/', $_POST['username'] ) )
		{
			$this->registry->template->show( '_header' );
			header( 'Location: ' . __SITE_URL . '/index.php?rt=login');
			exit();
		}
		else if( !filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL) )
		{
			header( 'Location: ' . __SITE_URL . '/index.php?rt=login');
			exit();
		}
		else
		{
			$is = $ls->isAlreadyInDB( $_POST['username']);
			if($is === true)
			{
				header( 'Location: ' . __SITE_URL . '/index.php?rt=login');
				exit();
			}

			$reg_seq = '';
			for( $i = 0; $i < 20; ++$i )
				$reg_seq .= chr( rand(0, 25) + ord( 'a' ) );

			$ls->unesiKorisnika( $_POST['username'], $_POST['password'], $_POST['email'], $reg_seq);

			$to       = $_POST['email'];
			$subject  = 'Registracijski mail';
			$message  = 'Poštovani ' . $_POST['username'] . "!\nZa dovršetak registracije kliknite na sljedeći link: ";
			$message .= 'http://' . $_SERVER['SERVER_NAME']. __SITE_URL . '/index.php?rt=login/register&niz='. $reg_seq . "\n";
			$headers  = 'From: rp2@studenti.math.hr' . "\r\n" .
			            'Reply-To: rp2@studenti.math.hr' . "\r\n" .
			            'X-Mailer: PHP/' . phpversion();

			mail($to, $subject, $message, $headers);
			$this->registry->template->show( 'login_pogledajMail' );
			exit();
		}
	}

 public function register(){
	$ls = new BudgetService();

	if( !isset( $_GET['niz'] ) || !preg_match( '/^[a-z]{20}$/', $_GET['niz'] ) )
		exit( 'Nešto ne valja s nizom.' );

		$ls->findWithNiz( $_GET['niz']);
		$this->registry->template->show( 'login_uspjesanSignUp' );
	}
};

?>
