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
	public function index(){
    if ( !isset($_SESSION['lang']) || $_SESSION['lang'] == 'ENG'  )
      $this->registry->template->show('statistics_index');
    else if ( $_SESSION['lang'] == 'CRO'  )
      $this->registry->template->show('statistics_indexCRO');

  }

	function getFirst(){
		$ls = new BudgetService();
		$user_id = $_SESSION['user_id'];
		$time = $ls->getFirst($user_id);
    $message = [];
		$message[ 'month' ] = $time[0] - 1; // -1 jer u mjeseci pocinju od 0!
    $message[ 'year' ] = $time[1];
		sendJSONandExit( $message );
	}

  function lineChart(){
		$ls = new BudgetService();
		$user_id = $_SESSION['user_id'];
    if($_GET['flag'] == 'month')
      $time = $ls->lineMonth($user_id, $_GET['m'], $_GET['y']);
    else
		  $time = $ls->lineYear($user_id, $_GET['y']);
    $message = [];
    $time1 = array();
    $ind = 0;
    foreach($time as $i){
      if($ind)
        $time1[$ind] = $time1[$ind - 1] + $i;
      else
       $time1[$ind] = $i;
      ++$ind;
    }
		$message[ 'line' ] = $time1;
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

  function getPieData(){
    $ls = new BudgetService();

    $message = "proba";
    $t = $_GET['type'];
    $p = $_GET['period'];
    $m = $_GET['month'];
    $y = $_GET['year'];
    $user_id = $_SESSION['user_id'];

    $data = $ls->getPieData($user_id, $t, $p, $m, $y);

    $final[0] = [];
    $i = 0;
    $final[1] = [];
    foreach ($data as $trans) {
      if ( array_search ( $trans['category'] , $final[0]) === FALSE ){
        $final[0][$i] = $trans['category'];
        $final[1][$i] = $trans['value'];
        $i++;
      }
      else{
        $j = array_search ( $trans['category'] , $final[0]);
        $final[1][$j] += $trans['value'];
      }
    }

    sendJSONandExit($final);
  }


};


?>
