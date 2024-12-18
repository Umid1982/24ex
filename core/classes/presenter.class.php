<?php

// ЗАГЛУШКА ЗАЩИТА -------------
if (!defined('COINCASH')) die();

class Presenter
	{
	private $model;
	private $view;

	public function __construct($model, $view)
		{
		$this->model = $model;
		$this->view = $view;

		if ($this->model->isAjax())
			{
			$this->model->doAjax();
			}
		else
			{
			$this->model->doActions();
			}
		}

	public function printPage($page)
		{
		if ($this->model->isAjax())
			{
			$data = $this->model->getAjaxVars();

			header('Content-type: application/json; charset=utf-8');
			$text = json_encode($data);		

			$text = $this->view->translate($text);	
			echo $text;
			}
		else
			{
			$data = $this->model->getVars();
			$html = $this->view->render($page,$data);

			header('Content-type: text/html; charset=utf-8');
			echo $html;
			}
		}

	}