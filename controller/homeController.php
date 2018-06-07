<?php

class HomeController extends BaseController
{
	public function index()
	{
    $ls = new BudgetService();

    $this->registry->template->user = $ls->getUserbById($_SESSION['user_id']);

		$this->registry->template->show('_table');
	}
};

?>
