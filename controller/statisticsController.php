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

class StatisticsController extends BaseController
{
	public function index(){$this->registry->template->show('statistics_index');}

	function getFirst(){
		$ls = new BudgetService();
		$user_id = $_SESSION['user_id'];
		$time = $ls->getFirst($user_id);
    $message = [];
		$message[ 'month' ] = $time[0] - 1; // -1 jer u mjeseci pocinju od 0!
    $message[ 'year' ] = $time[1];
		sendJSONandExit( $message );
	}

  function lineChartYear(){
		$ls = new BudgetService();
		$user_id = $_SESSION['user_id'];
		//$time = $ls->lineYear($user_id, $_GET['y']);
    $time = array(1,2,3);
    $message = [];
		$message[ 'line' ] = $time;
		sendJSONandExit( $message );
	}

  function getInform(){
		$ls = new BudgetService();
		$user_id = $_SESSION['user_id'];
    $n_days = cal_days_in_month(CAL_GREGORIAN, $_GET['m'] + 1, $_GET['y']); //treba za racunanje average per day, gledamo koliko mjesec ima dana
    if($_GET['t'] == 1) //gledamo expense
    {
      if($_GET['p'] == 1) //gledamo mjesece
        $transactions = $ls->ExpenseInMonth($user_id, $_GET['m'], $_GET['y']);
      else{ //gledamo godine
        $transactions = $ls->ExpenseInYear($user_id, $_GET['y']);
        $n_days = date("z", mktime(0,0,0,12,31, $_GET['y'])) + 1; //gledamo koliko godina ima dana
      }
    }
    else //gledamo income
    {
      if($_GET['p'] == 1) //gledamo mjesece
        $transactions = $ls->IncomeInMonth($user_id, $_GET['m'], $_GET['y']);
      else{ //gledamo godine
        $transactions = $ls->IncomeInYear($user_id, $_GET['y']);
        $n_days = date("z", mktime(0,0,0,12,31, $_GET['y'])) + 1;
      }
    }
    $message = [];
    $total = 0;
    $biggest = 0;
    foreach($transactions as $i){
      $total += $i;
      if($i > $biggest) $biggest = $i;
    }
    $n = sizeof($transactions);
    if($n){
		$message[ 'total' ] = $total;
    $message[ 'average' ] = sprintf('%0.2f', $total/$n);
    $message[ 'apd' ] = sprintf('%0.2f', $total/$n_days);
    $message[ 'biggest' ] = $biggest;
    }
    else{
    $message[ 'total' ] = 0;
    $message[ 'average' ] = 0;
    $message[ 'apd' ] = 0;
    $message[ 'biggest' ] = 0;
    }
		sendJSONandExit( $message );
	}



};


?>
