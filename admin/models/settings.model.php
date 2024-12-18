<?php

class SettingsModel extends Model
	{
	protected $rights_mask = CAN_SUPER;

	function vars()
		{
		$this->all_vars['ips_lock_list'] = $this->db->setGet('ips_lock_list');
		$this->all_vars['pay_life'] = $this->db->setGet('pay_life');
		$this->all_vars['tg_chat'] = $this->db->setGet('tg_chat');
		$this->all_vars['depo_rp_id'] = $this->db->setGet('depo_rp_id');

		$this->all_vars['voucher_len'] = $this->db->setGet('voucher_len');
		$this->all_vars['ub_len'] = $this->db->setGet('ub_len');
		$this->all_vars['ub_pref'] = $this->db->setGet('ub_pref');

		$this->all_vars['api_len'] = $this->db->setGet('api_len');
		$this->all_vars['api_timeout'] = $this->db->setGet('api_timeout');

		$this->all_vars['merchant_len'] = $this->db->setGet('merchant_len');
		$this->all_vars['merchant_prc'] = $this->db->setGet('merchant_prc');
		$this->all_vars['merchant_timeout'] = $this->db->setGet('merchant_timeout');
		$this->all_vars['merchant_try'] = $this->db->setGet('merchant_try');

		// реф планы инвест
		$refplans = $this->db->uni_select('ref_plans',['rp_type'=>RP_DEPOSIT]);
		if (count($refplans)>0) foreach ($refplans as $k=>$v)
			{
			$refplans[$k]['rp_id'] = (int)$v['rp_id'];
			$refplans[$k]['prcs_line'] = '% '.implode('<span style="color:#aaa">-</span>',json_decode($v['rp_prcs'],true));
			}
		$this->all_vars['refplans'] = $refplans;

		}

	function ajax()
		{
		if ($_GET['ajax']=='saveMainSettings')
			{
			foreach ($_POST['sets'] as $k=>$v)
				{
				if ($k=='ips_lock_list') $v = json_encode(explode("\r\n",$v));

				$this->db->setSet($k,$v);
				}
				
			$this->db->logWrite(LOG_MAIN_SETTINGS_SAVE,'','',ACC_ADMIN,$this->adminId());
			}
		}

	}