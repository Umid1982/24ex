<?php

class OfficeMerchantoneModel extends OfficeModel
	{

	function vars2()
		{
		if ($_GET['id']=='new')
            {
            $m_data = $this->db->get_columns_defaults('merchants');

            $m_data['m_num'] = 'new';
            $m_data['m_num_line'] = '- будет сгенерирован автоматически -';
            $m_data['m_api_key'] = '- будет сгенерирован автоматически -';

            $is_new = true;
            }
        else
            {
            $m_num = $_GET['id'];
            $m_data = $this->db->uni_select_one('merchants',['user_id'=>$this->userId(),'m_num'=>$m_num]);
            if ($m_data===false)
                {
                header('Location: /'._LANG_.'/office/merchants/');
                die();
                }

            $m_data['m_num_line'] = $m_data['m_num'];

            $is_new = false;

            //$this->all_vars['check_code'] = sha1($m_data['m_api_key'].$m_data['user_id'].$m_data['m_num']);
            }

        foreach ($m_data as $k=>$v)
            {
            $this->all_vars[$k] = $v;
            }

        if ($m_data['m_pss']=='') $this->all_vars['m_pss'] = [];
        else $this->all_vars['m_pss'] = explode('|',$m_data['m_pss']);

        $this->all_vars['is_new'] = $is_new;

        $this->all_vars['is_confirm'] = $m_data['m_is_confirm']==1;
        $this->all_vars['is_moder'] = $m_data['m_is_moder']==1;

        $this->all_vars['can_edit'] = $m_data['m_is_moder']==1 || $m_data['m_is_confirm']==1;

        $this->all_vars['m_moder_dt_line'] = date('d.m.Y H:i:s',$m_data['m_moder_dt']);

        // paysys
        if (!$is_new)
            {
            $all_pss = $this->db->uni_select('paysys',['paysys_type'=>'in','paysys_merch'=>1]);

            if (count($all_pss)>0) foreach ($all_pss as $k=>$one_ps)
                {
                $bal_data = $this->db->getBalData($one_ps['bal_id']);

                $one_ps['bal_rate'] = $bal_data['bal_rate'];
                $one_ps['bal_icon'] = getBalIcon($bal_data['bal_icon']);

                $all_pss[$k] = $one_ps;
                }

            $this->all_vars['all_pss'] = $all_pss;
            }
		}

	function ajax2()
		{
        
        if ($_GET['ajax']=='saveMerchant')
            {
            if ($_POST['m_num']=='new')
                {
                $is_new = true;

                $m_num = $this->db->genMerchantId();
                $m_api_key = sha1($m_num.rand(1000,9999));

                $has_data = true;
                $is_confirm = false;
                $is_moder = false;
                }
            else
                {
                $is_new = false;

                $m_num = $_POST['m_num'];
                $m_data = $this->db->uni_select_one('merchants',['user_id'=>$this->userId(),'m_num'=>$m_num]);
                if ($m_data===false)
                    {
                    $this->ajax_return['info'] = 'Магазин не найден';
                    $has_data = false;
                    }
                else
                    {
                    $m_api_key = $m_data['m_api_key'];

                    $has_data = true;
                    $is_moder = $m_data['m_is_moder']==1;
                    $is_confirm = $m_data['m_is_confirm']==1;
                    }
                }

            if ($has_data)
                {
                $m_title = trim($_POST['m_title']);
                $m_order_uniq = isset($_POST['m_order_uniq']) ? 1 : 0;
                $m_comm_who = (int)$_POST['m_comm_who'];

                if (!$is_confirm && !$is_moder)
                    {
                    $m_domain = trim($_POST['m_domain'],'/ ');
                    }
                else
                    {
                    $m_domain = $m_data['m_domain'];
                    }

                if ($m_title=='')
                    {
                    $this->ajax_return['info'] = 'Введите название';
                    }
                else if (!preg_match('#^http[s]?\:\/\/[^/]+\.[^/]+$#si', $m_domain))
                    {
                    $this->ajax_return['info'] = 'Неверный формат домена';
                    }
                else if ($this->db->uni_select_one('merchants',['m_domain'=>$m_domain,'m_num'=>['eq'=>'<>','val'=>$m_num]])!==false)
                    {
                    $this->ajax_return['info'] = 'Домен уже есть в системе';
                    }
                else
                    {
                    $m_url_success = trim($_POST['m_url_success']);
                    $m_url_error = trim($_POST['m_url_error']);
                    $m_url_callback = trim($_POST['m_url_callback']);

                    if ( strpos($m_url_success, $m_domain)===false || 
                        strpos($m_url_error, $m_domain)===false || 
                        strpos($m_url_callback, $m_domain)===false )
                        {
                        $this->ajax_return['info'] = 'Ссылки на страницы успешной/неуспешной оплаты и обработчика должны быть на основном домене';
                        }
                    else
                        {

                        $upd = [
                                'm_title'       => $m_title,
                                'm_domain'      => $m_domain,
                                'm_url_success' => $m_url_success,
                                'm_url_error'   => $m_url_error,
                                'm_url_callback'=> $m_url_callback,
                                'm_order_uniq'  => $m_order_uniq,
                                'm_comm_who'    => $m_comm_who,
                                ];

                        if ($is_new)
                            {
                            $upd['m_num'] = $m_num;
                            $upd['m_api_key'] = $m_api_key;
                            $upd['user_id'] = $this->userId();
                            $upd['m_prc'] = $this->db->setGet('merchant_prc');

                            $this->db->uni_insert('merchants',$upd);
                            }
                        else
                            {
                            $this->db->uni_update('merchants',['m_num'=>$m_num,'user_id'=>$this->userId()],$upd);
                            }

                        $this->ajax_return['result'] = true;
                        $this->ajax_return['is_new'] = $is_new;
                        $this->ajax_return['m_num'] = $m_num;
                        }
                    }
                }
            }

        if ($_GET['ajax']=='checkMerchant')
            {
            $m_num = @$_POST['id'];
            $m_data = $this->db->uni_select_one('merchants',['user_id'=>$this->userId(),'m_num'=>$m_num]);
            $check_timeout = $this->db->setGet('merchant_timeout');

            if ($m_data===false)
                {
                $this->ajax_return['info'] = 'Магазин не найден';
                }
            else if ($m_data['m_is_confirm']==1)
                {
                $this->ajax_return['info'] = 'Магазин уже был проверен';
                }
            else if (($m_data['m_confirm_dt']+$check_timeout)>time())
                {
                $this->ajax_return['info'] = 'Вы проверяете сайт слишком часто';
                }
            else
                {
                // обновляем время проверки
                $this->db->uni_update('merchants',['m_num'=>$m_num],['m_confirm_dt'=>time()]);

                //$check_code = sha1($m_data['m_api_key'].$m_data['user_id'].$m_data['m_num']);
                //$check_url = trim($m_data['m_domain'],'/').'/merch_check.txt';
                $check_url = $m_data['m_url_callback'];

                $check = checkMerchant($check_url,$m_num,$m_data['m_api_key']);
                if (!$check['total'])
                    {
                    //$this->ajax_return['info'] = 'Не найден проверочный код по адресу '.$check_url;
                    $this->ajax_return['errors'] = $check;
                    }
                else
                    {
                    $this->db->uni_update('merchants',['user_id'=>$this->userId(),'m_num'=>$m_num],['m_is_confirm'=>1]);
                    $this->db->logWrite(LOG_MERCH_CONFIRM,$m_num,'',ACC_USER,$this->userId());

                    $this->ajax_return['result'] = true;
                    }
                }
            }

        if ($_GET['ajax']=='moderMerchant')
            {
            $m_num = @$_POST['id'];
            $m_data = $this->db->uni_select_one('merchants',['user_id'=>$this->userId(),'m_num'=>$m_num]);

            if ($m_data===false)
                {
                $this->ajax_return['info'] = 'Магазин не найден';
                }
            else if ($m_data['m_is_confirm']==0)
                {
                $this->ajax_return['info'] = 'Сначала нужно пройти проверку домена';
                }
            else if ($m_data['m_is_moder']!=0)
                {
                $this->ajax_return['info'] = 'Сейчас нельзя отправить магазин на проверку';
                }
            else
                {
                $this->db->uni_update('merchants',['user_id'=>$this->userId(),'m_num'=>$m_num],['m_is_moder'=>2,'m_moder_dt'=>time()]);
                $this->db->logWrite(LOG_MERCH_MODER_SEND,$m_num,'',ACC_USER,$this->userId());

                $this->db->writeAdminAlerts('order','Магазин ID:'.$m_num.' - получен запрос на проверку/модерацию');

                $this->ajax_return['result'] = true;
                }
            }

        if ($_GET['ajax']=='pssMerchant')
            {
            $m_num = @$_POST['id'];
            $m_data = $this->db->uni_select_one('merchants',['user_id'=>$this->userId(),'m_num'=>$m_num]);

            if ($m_data===false)
                {
                $this->ajax_return['info'] = 'Магазин не найден';
                }
            else
                {
                if (@count($_POST['pss'])) $pss = implode('|',$_POST['pss']);
                else $pss = '';

                $this->db->uni_update('merchants',['user_id'=>$this->userId(),'m_num'=>$m_num],['m_pss'=>$pss]);
                $this->db->logWrite(LOG_MERCH_PSS_SAVE,$m_num,'',ACC_USER,$this->userId());

                $this->ajax_return['result'] = true;
                }
            }

		}

	}