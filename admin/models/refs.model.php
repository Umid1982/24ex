<?php

class RefsModel extends Model
	{
	protected $rights_mask = CAN_SUPER;

	function vars()
		{

		}

	function ajax()
		{
		if ($_GET['ajax']=='getRefPlans')
			{
			$refplans = $this->db->getRefPlans($this->ajax_pg,$this->ajax_pp,$this->ajax_sort,@$this->ajax_qs['generalSearch'],@$this->ajax_qs['type']);

			$total = $this->db->last_count();
			$pg_max = ceil($total / $this->ajax_pp);

			foreach ($refplans as $k=>$v)
				{
				$refplans[$k]['rp_id'] = (int)$v['rp_id'];
				$refplans[$k]['prcs_line'] = '% '.implode('<span style="color:#aaa">-</span>',json_decode($v['rp_prcs'],true));
				$refplans[$k]['type_line'] = refPlanTypeText($v['rp_type']);
				}

			$this->ajax_return['data'] = $refplans;
			$this->ajax_return['meta'] = [
									        "page" => $this->ajax_pg,
									        "pages" => $pg_max,
									        "perpage" => $this->ajax_pp,
									        "total" => $total,
									        "sort" => @$this->ajax_sort['sort'],
									        "field" => @$this->ajax_sort['field']
										 ];	
			}

		if ($_GET['ajax']=='newRefPlan')
			{
			$ins = [
					'rp_title' => 'Новый план',
					'rp_type' => NULL,
					'rp_prcs' => json_encode([1=>0])
					];
			$this->db->uni_insert('ref_plans',$ins);

			$this->ajax_return['result'] = true;
			}

		if ($_GET['ajax']=='getRefPlanDetails')
			{
			$rp_data = $this->db->uni_select_one('ref_plans',['rp_id'=>(int)$_POST['rp_id']]);
			if ($rp_data!==false)
				{
				$this->ajax_return['result'] = true;

				$prcs = json_decode($rp_data['rp_prcs'],true);

				$rp_data['prcs'] = $prcs;
				$rp_data['levels_cc'] = count($prcs);

				$this->ajax_return['rp_data'] = $rp_data;
				}
			}

		if ($_GET['ajax']=='saveRefPlan')
			{
			$rp_id = (int)$_POST['rp_id'];
			$rp_title = trim($_POST['rp_title']);
			$rp_type = $_POST['rp_type'];
			$levels = (int)$_POST['levels'];
			$prcs = $_POST['prcs'];

			if ($rp_type=='')
				{
				$this->ajax_return['info'] = 'Выберите тип плана';
				}
			else if ($rp_title=='')
				{
				$this->ajax_return['info'] = 'Введите название';
				}
			else
				{
				$ready_prcs = [];
				for ($i=1;$i<=$levels;$i++) $ready_prcs[$i] = (double)@$prcs[$i];

				$upd = [
						'rp_title' 	=> $rp_title,
						'rp_type' 	=> $rp_type,
						'rp_prcs' 	=> json_encode($ready_prcs),
						];

				$this->db->uni_update('ref_plans',['rp_id'=>$rp_id],$upd);

				$this->ajax_return['result'] = true;
				}
			}

		if ($_GET['ajax']=='delRefPlan')
			{
			$id = (int)$_POST['id'];

			if (!$this->db->uni_delete('ref_plans',['rp_id'=>$id]))
				{
				$this->ajax_return['info'] = 'Нельзя удалить план, имеются связи с другими элементами';
				}
			else
				{
				$this->ajax_return['result'] = true;
				}
			}
		}
	}