<?php

class NewsModel extends Model
	{

	function vars()
		{
		$news = $this->db->getNewsPub(_LANG_);
		if (count($news)>0) foreach ($news as $k=>$v)
			{
			$news[$k]['n_dt_pub_line'] = date("d.m.Y\nH:i",$v['n_dt_pub']);
			}

		$this->all_vars['news'] = $news;
		}

	}