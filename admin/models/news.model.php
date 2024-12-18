<?php

class NewsModel extends Model
	{
	protected $rights_mask = CAN_MODER;

	function ajax()
		{
		global $config;

		if ($_GET['ajax']=='getNews')
			{
			$news = $this->db->getNews($this->ajax_pg,$this->ajax_pp,$this->ajax_sort,
										@$this->ajax_qs['generalSearch'],@$this->ajax_qs['n_lang'],@$this->ajax_qs['n_status']);
			$total = $this->db->last_count();
			$pg_max = ceil($total / $this->ajax_pp);
			foreach ($news as $k=>$v)
				{
				$news[$k]['n_id'] = (int)$v['n_id'];
				$news[$k]['n_dt_create_line'] = date("d.m.Y\nH:i",$v['n_dt_create']);
				$news[$k]['n_dt_pub_line'] = date("d.m.Y\nH:i",$v['n_dt_pub']);
				$news[$k]['n_dt_tg_line'] = date("d.m.Y\nH:i",$v['n_dt_tg']);

				$admin_data = $this->db->uni_select_one('admins',['admin_id'=>$v['admin_id']]);
				$news[$k]['admin_login'] = $admin_data['admin_login'];

				if ($v['n_dt_pub']>time() && $v['n_status']==N_STATUS_PUB) $v['n_status'] = N_STATUS_FUTURE;
				$news[$k]['n_status_line'] = getNewsStatusLine($v['n_status']);

				if ($v['n_lang']=='') $news[$k]['n_lang'] = '<span style="color:blue">ВСЕ</span>';
				else $news[$k]['n_lang'] = strtoupper($v['n_lang']);
				}

			$this->ajax_return['data'] = $news;
			$this->ajax_return['meta'] = [
									        "page" => $this->ajax_pg,
									        "pages" => $pg_max,
									        "perpage" => $this->ajax_pp,
									        "total" => $total,
									        "sort" => @$this->ajax_sort['sort'],
									        "field" => @$this->ajax_sort['field']
										 ];
			}


		if ($_GET['ajax']=='sendNewsTg')
			{
			$id = (int)@$_POST['id'];

			$newsone = $this->db->uni_select_one('news',['n_id'=>$id]);
			if ($newsone===false)
				{
				$this->ajax_return['info'] = 'Новость не найдена';
				}
			else if ($newsone['n_status']==N_STATUS_DRAFT || ($newsone['n_status']==N_STATUS_PUB && $newsone['n_dt_pub']>time()))
				{
				$this->ajax_return['info'] = 'Новость должна быть в статусе `Опубликовано`';
				}
			else
				{
				$tg_chat = $this->db->setGet('tg_chat');
				if ($tg_chat=='')
					{
					$this->ajax_return['info'] = 'Канал не задан в настройках';
					}
				else
					{
					$TG = new TG($config['tg']['token']);
					$chan_chat_id = $TG->getChanChatId($tg_chat);
					if ($chan_chat_id===false)
						{
						$this->ajax_return['info'] = 'Канал не найден или бот не добавлен на канал!';
						}
					else
						{
						$text = html2tg($newsone['n_body']); //die($text);
						$ret = $TG->sendMess($chan_chat_id,$text,true);
						if ($ret['ok'])
							{
							$this->ajax_return['result'] = true;
							$this->db->uni_update('news',['n_id'=>$id],['n_dt_tg'=>time()]);

							$this->db->logWrite(LOG_NEWS_TG,'',$id,ACC_ADMIN,$this->adminId());
							}
						else
							{
							$this->ajax_return['info'] = 'Не получилось отправить сообщение, возможно слишком навороченный формат новости';
							}
						}
					}
				}

			
			}

		}

	}