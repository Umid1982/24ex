<?php

class FeedbackModel extends Model
	{
	protected $rights_mask = CAN_MODER;

	function ajax()
		{
		if ($_GET['ajax']=='getFeedbacks')
			{
			$msgs = $this->db->getFeedbacks($this->ajax_pg,$this->ajax_pp,$this->ajax_sort,@$this->ajax_qs['generalSearch']);
			$total = $this->db->last_count();
			$pg_max = ceil($total / $this->ajax_pp);
			foreach ($msgs as $k=>$v)
				{
				$msgs[$k]['sup_msg_user_line'] = $this->genUserLine($v['user_id']);
				$msgs[$k]['sup_msg_dt_line'] = date('d.m.Y H:i',$v['sup_msg_dt']);
				$msgs[$k]['sup_msg_ans_line'] = $this->getAnsLine($v['sup_msg_ans_mark'],$v['sup_msg_ans_dt']);
				}

			$this->ajax_return['data'] = $msgs;
			$this->ajax_return['meta'] = [
									        "page" => $this->ajax_pg,
									        "pages" => $pg_max,
									        "perpage" => $this->ajax_pp,
									        "total" => $total,
									        "sort" => @$this->ajax_sort['sort'],
									        "field" => @$this->ajax_sort['field']
										 ];
			}		

		if ($_GET['ajax']=='getFeedbackData')
			{
			$id = (int)@$_POST['id'];
			$data = $this->db->uni_select_one('sup_msgs',['sup_msg_id'=>$id]);
			if ($data!==false)
				{
				$this->ajax_return['msg_id'] = $data['sup_msg_id'];
				$this->ajax_return['msg_title'] = $data['sup_msg_title'];
				$this->ajax_return['msg_text'] = $data['sup_msg_text'];
				$this->ajax_return['msg_from'] = $data['sup_msg_email'];
				$this->ajax_return['msg_user_line'] = $this->genUserLine($data['user_id']);
				$this->ajax_return['msg_dt_line'] = date('d.m.Y H:i',$data['sup_msg_dt']);

				$this->ajax_return['isAns'] = ($data['sup_msg_ans_mark']==1);
				$this->ajax_return['msg_ans_line'] = $this->getAnsLine($data['sup_msg_ans_mark'],$data['sup_msg_ans_dt']);
				$this->ajax_return['msg_ans_text'] = $data['sup_msg_ans_text'];

				$this->ajax_return['result'] = true;
				}
			}

		if ($_GET['ajax']=='markAnswer')
			{
			$id = (int)@$_POST['id'];
			$data = $this->db->uni_select_one('sup_msgs',['sup_msg_id'=>$id]);
			if ($data!==false)
				{
				$upd = [
						'sup_msg_ans_mark'=>1,
						'sup_msg_ans_admin_id'=>$this->adminId(),
						'sup_msg_ans_dt'=>time()
						];
				$this->db->uni_update('sup_msgs',['sup_msg_id'=>$id],$upd);

				$this->db->logWrite(LOG_FEEDBACK_MARK_ANS,'',$id,ACC_ADMIN,$this->adminId());

				$this->ajax_return['result'] = true;
				$this->ajax_return['id'] = $id;
				$this->ajax_return['ans_text'] = $this->getAnsLine(1,time());
				}
			else
				{
				$this->ajax_return['info'] = 'Сообщение не найдено';
				}
			}

		if ($_GET['ajax']=='sendAnswer')
			{
			$msg_id = (int)@$_POST['msg_id'];
			$title = trim(htmlspecialchars(@$_POST['msg_ans_title']));
			$text = trim(htmlspecialchars(@$_POST['msg_ans_text']));

			$data = $this->db->uni_select_one('sup_msgs',['sup_msg_id'=>$msg_id]);

			if ($text=='' || $title=='')
				{
				$this->ajax_return['info'] = 'Введите Заголовок и Текст';
				}
			else if ($data===false)
				{
				$this->ajax_return['info'] = 'Сообщение не найдено';
				}
			else if ($data['sup_msg_ans_mark']==1)
				{
				$this->ajax_return['info'] = 'Сообщение уже было отвечено';
				}
			else
				{
				$send_data = [
							'msg_text' => $data['sup_msg_text'],
							'msg_ans_title' => $title,
							'msg_ans_text' => $text,
							];
				$send_result = sendMail('feedback_answer',$send_data,$data['sup_msg_email']);

				if (/*!$send_result*/1==2)
					{
					$this->ajax_return['info'] = 'Ошибка отправки E-mail, настройте SMTP';
					}
				else
					{
					$upd = [
							'sup_msg_ans_text'=>$text,
							'sup_msg_ans_mark'=>1,
							'sup_msg_ans_admin_id'=>$this->adminId(),
							'sup_msg_ans_dt'=>time()
							];
					$this->db->uni_update('sup_msgs',['sup_msg_id'=>$msg_id],$upd);

					$this->db->logWrite(LOG_FEEDBACK_SEND_ANS,$msg_id,$text,ACC_ADMIN,$this->adminId());

					if ($data['user_id']!=0) $this->db->writeUserAlerts($data['user_id'],'feedback','Ответ службы подержки: '.$text);

					$this->ajax_return['result'] = true;
					}
				}
			}
		}

	function getAnsLine($mark,$dt)
		{
		if ($mark==0)
			{
			$aline = 'не отвечен';
			}
		else
			{
			$aline = date('d.m.Y H:i',$dt);
			}

		return $aline;
		}

	}