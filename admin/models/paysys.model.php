<?php

class PaysysModel extends Model
	{
	protected $rights_mask = CAN_SUPER;

	function vars()
		{
		$this->all_vars['all_bals'] = $this->db->uni_select('bals');
		$this->all_vars['all_pss_in']  = getAllPaysysVars($this->pss,'in');
		$this->all_vars['all_pss_out'] = getAllPaysysVars($this->pss,'out');
		}

	function ajax()
		{
		if ($_GET['ajax']=='deletePaysys')
			{
			$id = (int)$_POST['id'];
			if ($this->db->uni_delete('paysys',['paysys_id'=>$id]))
				{
				$this->ajax_return['result'] = true;
				}
			else
				{
				$this->ajax_return['info'] = 'Удаление невозможно, есть связи с другими элементами системы';
				}
			}

		if ($_GET['ajax']=='getPaysys')
			{
			$sort = (count($this->ajax_sort)>0) ? [$this->ajax_sort['field']=>$this->ajax_sort['sort']] : [];
			$from = (($this->ajax_pg-1)*$this->ajax_pp);
			
			$paysys = $this->db->uni_select('paysys',[],$sort,false,$from,$this->ajax_pp);
			$total = $this->db->last_count();
			$pg_max = ceil($total / $this->ajax_pp);

			foreach ($paysys as $k=>$v)
				{
				$temp = explode('||',$v['paysys_name']);
				$bd = $this->db->uni_select_one('bals',['bal_id'=>$v['bal_id']]);

				$ps_data = $this->pss[$temp[0]]->getInfo($temp[1]);
				$paysys[$k]['paysys_system_name'] = $ps_data['title'];
				$paysys[$k]['bal_rate'] = $temp[0]=='voucher' ? ' - ' : $bd['bal_rate'];

				$paysys[$k]['paysys_icon'] = getBalIcon($v['paysys_icon']);
				}

			$this->ajax_return['data'] = $paysys;
			$this->ajax_return['meta'] = [
									        "page" => $this->ajax_pg,
									        "pages" => $pg_max,
									        "perpage" => $this->ajax_pp,
									        "total" => $total,
									        "sort" => @$this->ajax_sort['sort'],
									        "field" => @$this->ajax_sort['field']
										 ];	
			}

		if ($_GET['ajax']=='getPaysysData')
			{
			$ps_id = (int)$_POST['id'];
			$ps_data = $this->db->uni_select_one('paysys',['paysys_id'=>$ps_id]);
			if ($ps_data!==false)
				{
				$ps_data['paysys_icon'] = getBalIcon($ps_data['paysys_icon']);
				foreach ($ps_data as $k=>$v) $this->ajax_return[$k] = $v;
				}

			$this->ajax_return['result'] = true;
			}

		if (@$_GET['ajax']=='savePaysys')
			{
			$paysys_id = (int)@$_POST['paysys_id'];
			$paysys_title = htmlspecialchars(@$_POST['paysys_title']);
			$paysys_info = htmlspecialchars(@$_POST['paysys_info']);
			$bal_id = (int)@$_POST['bal_id'];

			$paysys_merch = isset($_POST['paysys_merch']) ? 1 : 0;
			
			if ($paysys_id!=0)
				{
				$old_data = $this->db->uni_select_one('paysys',['paysys_id'=>$paysys_id]);
				}

			$file_ok = false;

			$file = $_FILES['paysys_icon'];
			if ($file['error']==0)
				{
				$fn = preg_replace('#\.[^\.]+$#si','',$file['name']) . '_' . date('His').'.svg';
				$mime = mime_content_type($file['tmp_name']);
				$fld = getUploadDesc();

				if ($mime!='image/svg+xml' && $mime!='image/svg') $error_file = 'Можно загрузить только SVG ('.$mime.')';
				else if (!move_uploaded_file($file['tmp_name'], $fld.'/'.$fn)) $error_file = 'Можно загрузить только SVG';
				else { $file_ok = true; $paysys_icon = preg_replace('#^.+?/uploads/#','uploads/', $fld.'/'.$fn); }
				}
			else
				{
				$file_ok = true;
				$paysys_icon = @$old_data['paysys_icon'];
				}

			if (!$file_ok)
				{
				$this->ajax_return['info'] = $error_file;
				}
			else
				{
				$bd = $this->db->uni_select_one('bals',['bal_id'=>$bal_id]);

				if ($bd===false)
					{
					$this->ajax_return['info'] = 'Выберите баланс для курса';
					}
				else if ($paysys_title=='')
					{
					$this->ajax_return['info'] = 'Название обязательно!';
					}
				else if ($this->db->uni_select_one('paysys',['paysys_id'=>['eq'=>'<>','val'=>$paysys_id],'paysys_title'=>$paysys_title])!==false)
					{
					$this->ajax_return['info'] = 'Данное название уже есть!';
					}
				else
					{
					if ($paysys_id==0) // NEW PAYSYS
						{
						$paysys_type = htmlspecialchars(@$_POST['paysys_type']);
						$paysys_name = htmlspecialchars(@$_POST['paysys_name_'.$paysys_type]);

						$temp = explode('||',$paysys_name);
						$ps_name = $temp[0];
						$ps_var = @$temp[1];

						if (!isset($this->pss[$ps_name]))
							{
							$this->ajax_return['info'] = 'Системный способ не найден';
							}
						else if (!$this->pss[$ps_name]->getInfo($ps_var))
							{
							$this->ajax_return['info'] = 'Системный способ не найден';
							}
						else if ($paysys_type===0)
							{
							$this->ajax_return['info'] = 'Выберите тип способа!';
							}
						else
							{
							$ins = [
									'paysys_title' 	=> $paysys_title,
									'paysys_info' 	=> $paysys_info,
									'bal_id' 		=> $bal_id,
									'paysys_icon'	=> $paysys_icon,

									'paysys_type' 	=> $paysys_type,
									'paysys_name' 	=> $paysys_name,

									'paysys_merch'	=> $paysys_merch,
									];

							$ps_id = $this->db->uni_insert('paysys',$ins);

							$this->ajax_return['result'] = true;

							$this->db->logWrite(LOG_NEW_PAYSYS,'',$ps_id,ACC_ADMIN,$this->adminId());
							}
						}
					else // UPDATE
						{
						$upd = [
								'paysys_title' 	=> $paysys_title,
								'paysys_info' 	=> $paysys_info,
								'bal_id' 		=> $bal_id,
								'paysys_icon'	=> $paysys_icon,
								'paysys_merch'	=> $paysys_merch,
								];

						$this->db->uni_update('paysys',['paysys_id'=>$paysys_id],$upd);
						$this->ajax_return['result'] = true;

						$this->db->logWrite(LOG_CHANGE_PAYSYS,$paysys_id,'',ACC_ADMIN,$this->adminId());
						}
					}
				}
			}
		}
	}