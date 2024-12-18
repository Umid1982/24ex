<?php

class OfficeModel extends Model
	{
	function vars()
		{
		global $config;

		if (!$this->isAuth())
			{
			$_SESSION['redirTo'] = $_SERVER['REQUEST_URI'];
			goRedir('/auth/');
			}

		// обновляем онлайн статус
		$ip = getClientIp();
		$this->db->uni_update('users',['user_id'=>$this->userId()],['user_last_action'=>time(),'user_last_ip'=>$ip]);

		// если запрошено изменение пароля, пока не поменяет кидать на секурити
		if (isset($_SESSION['coincash_user_need_change_pass']) && $this->page != 'office/cabinet') goRedir('/office/cabinet/#security');

		$this->all_vars['user_id'] = $this->userId();

		$user_data = $this->user_data;

		if ($user_data['user_need_logout']==1 && @$_GET['action']!='logout')
			{
			goRedir('/office/?action=logout');
			}

		foreach ($user_data as $k=>$v) $this->all_vars[$k] = $v;
		$this->all_vars['user_avatar'] = getUserAvatar($user_data['user_avatar']);

		if ($user_data['user_fname']=='' && $user_data['user_lname']=='') $user_name_line = $user_data['user_email'];
		else $user_name_line = trim($user_data['user_fname'] . ' ' . $user_data['user_lname']);
		$this->all_vars['user_name_line'] = $user_name_line;

		$this->all_vars['user_symbol'] = mb_substr($user_name_line, 0, 1, 'UTF-8');

		$this->all_vars['ref_link'] = $config['site']['url'].'/'._LANG_.'/?reflink='.$this->userId();

		$this->all_vars['user_bals_sum_usd'] = number_format($this->db->getUserBalsSumsUsd($this->userId()),2);
		$this->all_vars['user_invest_sum_usd'] = number_format($this->db->getUserInvestSumsUsd($this->userId()),2);
		$this->all_vars['user_payout_sum_usd'] = number_format($this->db->getUserPayoutSumsUsd($this->userId()),2);
		}

	function actions()
		{
		if (@$_GET['action']=='logout')
			{
			$user_id = $_SESSION['coincash_user_id'];
			
			unset($_SESSION['coincash_auth']);
			unset($_SESSION['coincash_user_id']);
			unset($_SESSION['coincash_user_email']);
			unset($_SESSION['coincash_user_need_change_pass']);

			$this->db->logWrite(LOG_LOGOUT,'','',ACC_USER,$user_id);

			goRedir('/auth/');
			}
		}

	}