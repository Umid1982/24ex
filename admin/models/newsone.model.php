<?php

class NewsoneModel extends Model
	{
	protected $rights_mask = CAN_SUPER;

	function vars()
		{
		if (!isset($_GET['n_id'])) goRedir('news',true);

		$n_id = (int)@$_GET['n_id'];
		if ($n_id!=0)
			{
			$one_news = $this->db->uni_select_one('news',['n_id'=>$n_id]);
			if ($one_news===false) goRedir('news',true);
			}
		else
			{
			$one_news = $this->db->get_columns_defaults('news');
			$one_news['n_title'] = 'Новая новость';
			$one_news['n_dt_pub'] = time();
			}

		$one_news['n_dt_pub_line'] = date('d.m.Y H:i:s',$one_news['n_dt_pub']);

		foreach ($one_news as $k=>$v) $this->all_vars[$k] = $v;
		}

	function ajax()
		{
		global $config;
		
		if ($_GET['ajax']=='tinyUploadImage')
			{
			reset ($_FILES);
			$temp = current($_FILES);
			if (is_uploaded_file($temp['tmp_name']))
				{
			    // Sanitize input
			    if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) 
			    	{
			        header("HTTP/1.1 400 Invalid file name.");
			        return;
			    	}

			    // Verify extension
			    if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png")))
			    	{
			        header("HTTP/1.1 400 Invalid extension.");
			        return;
			    	}

		    	// Accept upload if there was no origin, or if it is an accepted origin
		    	$filetowrite = getUploadDesc(). '/' . $temp['name'];
		    	move_uploaded_file($temp['tmp_name'], $filetowrite);

		    	// Determine the base URL
		    	$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? "https://" : "http://";
		    	$baseurl = $protocol . $_SERVER["HTTP_HOST"] . "/";

		    	// Respond to the successful upload with JSON.
		    	// Use a location key to specify the path to the saved image resource.
		    	// { location : '/your/uploaded/image/file'}
				$this->ajax_return['result'] = true;
				$this->ajax_return['location'] = $baseurl . preg_replace('#^.+?/(uploads/)#si','uploads/',$filetowrite);
		  		}
		  	else
		  		{
		    	// Notify editor that the upload failed
		    	header("HTTP/1.1 500 Server Error");
		  		}


			}

		if ($_GET['ajax']=='saveNews')
			{
			$id = (int)@$_POST['id'];
			$title = trim(@$_POST['title']);
			$status = (int)$_POST['status'];
			$lang  = trim(@$_POST['lang']);
			$body  = trim(@$_POST['body']);
			$dt_pub = strtotime(trim(@$_POST['dt']));

			if ($title=='' || $body=='')
				{
				$this->ajax_return['info'] = 'Заполните заголовок, текст и язык!';
				}
			else if ($lang!='' && !in_array($lang, $config['lang']['list']))
				{
				$this->ajax_return['info'] = 'Выбран некорректный язык!';
				}
			else
				{
				$upd = [
						'n_title'=>$title,
						'n_lang'=>$lang,
						'n_body'=>$body,
						'n_dt_pub'=>$dt_pub,
						'n_status'=>$status,
						'n_dt_tg'=>0,
						];

				if ($id==0)
					{
					$upd['admin_id'] = $this->adminId();
					$upd['n_dt_create'] = time();
					$id = $this->db->uni_insert('news',$upd);

					$this->db->logWrite(LOG_NEWS_NEW,'',$id,ACC_ADMIN,$this->adminId());		
					}
				else
					{
					$this->db->uni_update('news',['n_id'=>$id],$upd);

					$this->db->logWrite(LOG_NEWS_UPD,$id,'',ACC_ADMIN,$this->adminId());	
					}

				$this->ajax_return['result'] = true;
				$this->ajax_return['n_id'] = $id;
				}
			}

		if ($_GET['ajax']=='delNews')
			{
			$id = (int)@$_POST['id'];

			if ($id!=0)
				{
				$this->db->uni_delete('news',['n_id'=>$id]);
				$this->db->logWrite(LOG_NEWS_DEL,$id,'',ACC_ADMIN,$this->adminId());	
				$this->ajax_return['result'] = true;
				}
			}

		}

	}