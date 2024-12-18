<?php

class LangsModel extends Model
	{
	protected $rights_mask = CAN_SUPER;

	function vars()
		{
		global $config;

		$this->all_vars['langs'] = $config['lang']['list'];
		}

	function actions()
		{
		if (isset($_GET['export']))
			{
			$lang = $_GET['export'];
			$all_trans = $this->getAllTrans($lang);

			header('Content-type: text/csv');
			header('Content-disposition: attachment; filename='.$lang.'_export.csv');

			$fp = fopen('php://output', 'wb');
			if (count($all_trans)>0) foreach ($all_trans as $one)
				{
				fputcsv($fp, [$one['key'],$one['val']]);
				}
			fclose($fp);

			die();
			}
		}

	function ajax()
		{
		global $config;

		if ($_GET['ajax']=='getLangs')
			{
			$lang = (@$this->ajax_qs['lang']=='') ? $config['lang']['list'][0] : $this->ajax_qs['lang'];
			$all_trans = $this->getAllTrans($lang);
			$this->ajax_return['data'] = $all_trans;			
			}	

		if ($_GET['ajax']=='importLang')
			{
			$lang = $_POST['lang'];
			$method = $_POST['method'];

			if (array_search($lang, $config['lang']['list'])===false)
				{
				$this->ajax_return['info'] = 'Языка нет в списке, проверьте конфиг!';
				}
			else if (!isset($_FILES['import_file']))
				{
				$this->ajax_return['info'] = 'Файл не выбран';
				}
			else if ($_FILES['import_file']['error']!=0)
				{
				$this->ajax_return['info'] = 'Ошибка загрузки файла';
				}
			else if (!preg_match('#^text/.+#',mime_content_type($_FILES['import_file']['tmp_name'])))
				{
				$this->ajax_return['info'] = 'Неверный формат файла, '.mime_content_type($_FILES['import_file']['tmp_name']);
				}
			else
				{
				$lines = file($_FILES['import_file']['tmp_name']);

				$path = _ROOT_.'/site/templates/__translate_'.$lang.'.php';

				$translates = [];
				if ($method!='rewrite')
					{
					include $path;
					}

				if (count($lines)>0) foreach ($lines as $one)
					{
					$temp = str_getcsv($one);
					$translates[$temp[0]] = $temp[1];
					}

				if (@count($translates)==0)
					{
					$this->ajax_return['info'] = 'Файл пустой';
					}
				else
					{
					$ready = formatTransFile($translates);
					file_put_contents($path, $ready);
					if (file_get_contents($path)!=$ready)
						{
						$this->ajax_return['info'] = 'Ошибка записи в файл, файлы __translate_N.php должны быть доступна на запись для работы импорта!';
						}
					else
						{
						$this->ajax_return['result'] = true;
						}
					}
				}
			}	
		}

	private function getAllTrans($lang)
		{
		$path = _ROOT_.'/site/templates/__translate_'.$lang.'.php';

		$all_trans = [];

		if (file_exists($path))
			{
			include $path;
			if (isset($translates))
				{
				if (count($translates)>0)
					{
					foreach ($translates as $k=>$v)
						{
						$all_trans[] = ['key'=>$k,'val'=>$v];
						}
					}
				}
			}

		return $all_trans;
		}
	}