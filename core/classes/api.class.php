<?

class API
	{

	private $db;
	private $rr = [];					// return result
	private $ei = '';					// error info
	private $re = API_ERR_WRONG_SIGN;	// result error
	private $tm_lock = false;			// timeout lock
	private $udata = false;

	public function __construct($db)
		{
		$this->db = $db;
		}

	public function remoteAuth($user_id,$act,$r_sign,$rq)
		{
		$r_sign = trim($r_sign);
		if ($r_sign=='')
			{
			$this->re = API_ERR_WRONG_SIGN;
			return false;
			}

		$udata = $this->db->uni_select_one('users',['user_id'=>$user_id]);
		if ($udata!==false)
			{
			$timeout = $this->db->setGet('api_timeout');
			if ( time() < ($udata['user_api_dt_last']+$timeout) )
				{
				$this->re = API_ERR_TIMEOUT;
				$this->tm_lock = true;
				return false;
				}
			else
				{
				// проверяем подпись
				$arHash = [$user_id,$act,$udata['user_api_key']];
				switch ($act)
					{
					case 'myBals':
					break;
					case 'balsCanAdd':
					break;
					case 'balAdd':
						$arHash[] = $rq['bal_id'];
					break;
					case 'transfer':
						$arHash[] = $rq['my_id'];
						$arHash[] = $rq['value'];
						$arHash[] = $rq['wallet'];
						$arHash[] = $rq['paycode'];
					break;
					case 'payIn':
						$arHash[] = $rq['my_id'];
						$arHash[] = $rq['value'];
					break;
					case 'payOut':
						$arHash[] = $rq['my_id'];
						$arHash[] = $rq['value'];
					break;
					case 'newVoucher':
						$arHash[] = $rq['my_id'];
						$arHash[] = $rq['value'];
					break;
					case 'change':
						$arHash[] = $rq['my_id'];
						$arHash[] = $rq['value'];
						$arHash[] = $rq['to_id'];
						$arHash[] = $rq['paycode'];
					break;
					case 'payInfo':
						$arHash[] = $rq['pay_id'];
					break;
					}
				$m_sign = strtoupper(sha1(implode(':',$arHash)));

				if ($m_sign===$r_sign)
					{
					$this->udata = $udata;
					$this->db->uni_update('users',['user_id'=>$udata['user_id']],['user_api_dt_last'=>time()]);
					return true;
					}
				else
					{
					$this->re = API_ERR_WRONG_SIGN;
					return false;
					}
				}
			}
		else
			{
			$this->re = API_ERR_USER_ID;
			return false;
			}
		}

	public function remoteWork($act)
		{
		global $config;

		if ($this->udata===false) return false;
		if ($this->tm_lock) return false;

		$api_rules = $this->udata['user_api_rules']=='' ? [] : json_decode($this->udata['user_api_rules'],true);
		if (isset($api_rules[$act])) return false;

		switch ($act)
			{
			case 'myBals':

				$ubs = $this->db->getUBs($this->udata['user_id']);
				if (count($ubs)>0) foreach ($ubs as $one)
					{
					$this->rr[] = [
								'bal_id'=> $one['bal_id'],
								'my_id' => $one['ub_id'],
								'title' => $one['bal_title'],
								'name' 	=> $one['bal_name'],
								'value' => $one['ub_value'],
								];
					}

				$this->re = API_ERR_OK;

			break;
			case 'balsCanAdd':

				$bals = $this->db->getBalsForAdd($this->udata['user_id']);
				if (count($bals)>0) foreach ($bals as $one)
					{
					$this->rr[] = [
								'bal_id'=> $one['bal_id'],
								'title' => $one['bal_title'],
								'name' 	=> $one['bal_name'],
								];
					}			

				$this->re = API_ERR_OK;	

			break;
			case 'balAdd':

				$bal_id = (int)@$_REQUEST['bal_id'];

				$result = $this->db->addBalToUser($this->udata['user_id'],$bal_id);
				if ($result===false) $this->re = API_ERR_WRONG_REQUEST;
				else $this->re = API_ERR_OK;

			break;
			case 'transfer':

				$ub_id = (int)@$_REQUEST['my_id'];
				$value = floatval(htmlspecialchars(@$_REQUEST['value']));
				$user_bal_num = trim(htmlspecialchars(@$_REQUEST['wallet']));
				$pay_pass = trim(htmlspecialchars(@$_REQUEST['paycode']));

				if ($ub_id==0 || $value<=0 || $user_bal_num=='')
					{
					$this->re = API_ERR_WRONG_REQUEST;
					}
				else
					{
					// step 1, create
					$result1 = $this->db->transferNew($this->udata['user_id'],$ub_id,$value);
					if ($result1['result']!=PAY_ERR_OK)
						{
						$this->re = API_ERR_WRONG_REQUEST;
						$this->ei = getPayErrText(@$result1['result'],@$result1['min'],@$result1['max']);
						}
					else
						{
						// step 2, setdata
						$pay_id = $result1['pay_id'];
						$result2 = $this->db->transferSetPayData($this->udata['user_id'],$pay_id,$user_bal_num,$pay_pass);
						if ($result2['result']!=PAY_ERR_OK)
							{
							$this->re = API_ERR_WRONG_REQUEST;
							$this->ei = getPayErrText(@$result2['result'],@$result2['min'],@$result2['max']);
							}
						else
							{
							// step 3, confirm
							$result3 = $this->db->transferConfirm($this->udata['user_id'],$pay_id);

							$this->rr['pay_id'] = $pay_id;
							$this->re = API_ERR_OK;
							}				
						}
					}
				

			break;	
			case 'payIn':

				$ub_id = (int)@$_REQUEST['my_id'];
				$value = floatval(htmlspecialchars(@$_REQUEST['value']));

				if ($ub_id==0 || $value<=0)
					{
					$this->re = API_ERR_WRONG_REQUEST;
					}
				else
					{
					// step 1, create
					$result1 = $this->db->payInNew($this->udata['user_id'],$ub_id,$value);
					if ($result1['result']!=PAY_ERR_OK)
						{
						$this->re = API_ERR_WRONG_REQUEST;
						$this->ei = getPayErrText(@$result1['result'],@$result1['min'],@$result1['max']);
						}
					else
						{
						// end, return link
						$this->re = API_ERR_OK;
						$this->rr['link'] = $config['site']['url'] . $result1['link'];
						$this->rr['pay_id'] = $result1['pay_id'];
						}
					}
				

			break;	

			case 'payOut':

				$ub_id = (int)@$_REQUEST['my_id'];
				$value = floatval(htmlspecialchars(@$_REQUEST['value']));

				if ($ub_id==0 || $value<=0)
					{
					$this->re = API_ERR_WRONG_REQUEST;
					}
				else
					{
					// step 1, create
					$result1 = $this->db->payOutNew($this->udata['user_id'],$ub_id,$value);
					if ($result1['result']!=PAY_ERR_OK)
						{
						$this->re = API_ERR_WRONG_REQUEST;
						$this->ei = getPayErrText(@$result1['result'],@$result1['min'],@$result1['max']);
						}
					else
						{
						// end, return link
						$this->re = API_ERR_OK;
						$this->rr['link'] = $config['site']['url'] . $result1['link'];
						$this->rr['pay_id'] = $result1['pay_id'];
						}
					}
				

			break;	

			case 'newVoucher':

				$ub_id = (int)@$_REQUEST['my_id'];
				$value = floatval(htmlspecialchars(@$_REQUEST['value']));

				if ($ub_id==0 || $value<=0)
					{
					$this->re = API_ERR_WRONG_REQUEST;
					}
				else
					{
					$ub_data = $this->db->uni_select_one('users_bals',['user_id'=>$this->udata['user_id'],'ub_id'=>$ub_id]);
					if ($ub_data===false)
						{
						$this->re = API_ERR_WRONG_REQUEST;
						}
					else
						{
						// step 1, create
						$result1 = $this->db->newVoucher($ub_data['bal_id'],$value,$this->udata['user_id'],$ub_data['ub_id']);
						if ($result1['result']!=PAY_ERR_OK)
							{
							$this->re = API_ERR_WRONG_REQUEST;
							$this->ei = getPayErrText(@$result1['result'],@$result1['min'],@$result1['max']);
							}
						else
							{
							// end, return voucher data
							$this->re = API_ERR_OK;
							$this->rr['voucher_id'] = $result1['voucher_id'];
							$this->rr['voucher_code'] = $result1['voucher_code'];
							}
						}
					}
				

			break;	

			case 'change':

				$ub_id_from = (int)@$_REQUEST['my_id'];
				$ub_id_to = (int)@$_REQUEST['to_id'];
				$value = floatval(htmlspecialchars(@$_REQUEST['value']));
				$paycode = trim(@$_REQUEST['paycode']);

				if ($ub_id_from==0 || $value<=0 || $ub_id_to==0)
					{
					$this->re = API_ERR_WRONG_REQUEST;
					}
				else
					{
					// step 1, create
					$result1 = $this->db->changeInNew($this->udata['user_id'],$ub_id_from,$ub_id_to,$value,$paycode);
					if ($result1['result']!=PAY_ERR_OK)
						{
						$this->re = API_ERR_WRONG_REQUEST;
						$this->ei = getPayErrText(@$result1['result'],@$result1['min'],@$result1['max']);
						}
					else
						{
						// step 2, confirm
						$pay_id = $result1['pay_id'];
						$result2 = $this->db->changeInConfirm($this->udata['user_id'],$pay_id);
						if ($result2['result']!=PAY_ERR_OK)
							{
							$this->re = API_ERR_WRONG_REQUEST;
							$this->ei = getPayErrText(@$result2['result'],@$result2['min'],@$result2['max']);
							}
						else
							{
							// step 3, confirm
							$this->re = API_ERR_OK;
							$this->rr['pay_id'] = $pay_id;
							}
							
						}
					}

			break;

			case 'payInfo':

				$pay_id = (int)@$_REQUEST['pay_id'];

				$pay_data = $this->db->uni_select_one('payments',['user_id'=>$this->udata['user_id'],'pay_id'=>$pay_id]);
				if ($pay_data===false)
					{
					$this->re = API_ERR_WRONG_REQUEST;
					$this->ei = 'Платеж не найден';
					}
				else
					{
					$bal_data = $this->db->getBalData($pay_data['bal_id']);

					if (in_array($pay_data['pay_status'], [PAY_STATUS_NEW,PAY_STATUS_IN_WORK,PAY_STATUS_PAYS,PAY_STATUS_USER_PAYS,
						PAY_STATUS_SEND_PROPS,PAY_STATUS_PENDING,PAY_STATUS_GO_PAY]) && $pay_data['pay_end']<time())
						{
						$status = 'Время истекло';
						}
					else
						{
						$status = payStatusText($pay_data['pay_status']);
						}

					$this->re = API_ERR_OK;
					$this->rr = [
								'pay_id' 	=> $pay_id,
								'type' 		=> payTypeText($pay_data['pay_type']),
								'status' 	=> $status,
								'dt_create' => date('d.m.Y H:i:s',$pay_data['pay_dt']),
								'dt_end' 	=> date('d.m.Y H:i:s',$pay_data['pay_end']),
								'value'  	=> cutZeros($pay_data['pay_value']),
								'com'  		=> cutZeros($pay_data['pay_com']),
								'title' 	=> $bal_data['bal_title'],
								'name' 		=> $bal_data['bal_name'],
								'link'		=> $config['site']['url'] . '/' . _LANG_ . '/payment/' . getPayTypeUrl($pay_data['pay_type']) . '?pay=' . $pay_id
								];
					}

			break;

			default:

				$this->re = API_ERR_WRONG_ACT;

			break;
			}
		}

	public function remoteResult()
		{
		$out = ['error'=>$this->re, 'info'=>$this->ei, 'data'=>$this->rr]; //var_dump($out);
		echo json_encode($out);
		}

	}