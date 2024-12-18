<?php

class AdmsettingsModel extends Model
	{
	protected $rights_mask = CAN_MODER;

	function vars()
		{
		if ($this->admin_data['admin_tg_code']=='')
			{
			$tg_code = genPass(10,10);
			$this->db->uni_update('admins',['admin_id'=>$this->adminId()],['admin_tg_code'=>$tg_code]);
			}
		else
			{
			$tg_code = $this->admin_data['admin_tg_code'];
			}

		$this->all_vars['admin_tg'] = $this->admin_data['admin_tg'];
		$this->all_vars['admin_tg_code'] = $tg_code;

		$ass = $this->db->getASS(ACC_ADMIN,$this->adminId());
		if (count($ass)>0) foreach ($ass as $k=>$v) $this->all_vars['tga'][$k] = $v;
		}

	function ajax()
		{
		if ($_GET['ajax']=='saveASS')
			{
			$ava = [
					'feedback',
					'payout','payin',
					'changein','changeout',
					'transfer',
					'order'
					];

			$todo = [];
			foreach ($ava as $one)
				{
				if (isset($_POST['tga'][$one])) $todo[$one] = 1;
				}

			$this->db->saveASS(ACC_ADMIN,$this->adminId(),$todo);
			$this->db->logWrite(LOG_ASS_SAVE,'','',ACC_ADMIN,$this->adminId());

			$this->ajax_return['result'] = true;
			}
		}

	}