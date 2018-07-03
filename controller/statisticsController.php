<?php

class StatisticsController extends BaseController
{
	public function index()
	{
    $ls = new BudgetService();

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

	public function incomeStatistics()
	{
		$ls = new BudgetService();

		$list = $ls->getIncomesById($_SESSION['user_id']);
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

};


?>
