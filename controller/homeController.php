<?php



class HomeController extends BaseController
{
	public function index()
	{
    $ls = new BudgetService();

    $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);

		$this->registry->template->transactionsList = $ls->getTransactionsById($_SESSION['user_id']);
		$this->registry->template->flag = "transactions";
		if ( $_SESSION['lang'] == 'CRO' )
			$this->registry->template->show('transactions_indexCRO');
		else if ($_SESSION['lang'] == 'ENG' )
			$this->registry->template->show('transactions_index');

	}


};



?>
