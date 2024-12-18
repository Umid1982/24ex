<?php

class View
	{
	private $def_lang;

	function __construct($def_lang)
		{
		$this->def_lang = $def_lang;
		}

	function render($page,$vars)
		{
		global $config;

		$temp = preg_replace('#\/[^\/]+$#i', '/', '/'.$page);
		$tpl_path = _TEMPLATES_.$temp.'__template.php';
		if (!file_exists($tpl_path)) goRedir('/');

		$var_page_path = _TEMPLATES_.'/'.$page.'.php';
		if (!file_exists($var_page_path)) goRedir('/');

		ob_start();
		extract($vars, EXTR_PREFIX_ALL, 'var');
		require_once($tpl_path);
		$html = ob_get_clean();

		$html = $this->translate($html);

		return $html;
		}

	function translate($text)
		{
		// TRANSLATE FOR SITE NOW ONLY ===========
		$trans_path = _TEMPLATES_.'/__translate_'._LANG_.'.php';
		if (!file_exists($trans_path)) $trans_path = _TEMPLATES_.'/__translate_'.$this->def_lang.'.php';
		if (!file_exists($trans_path)) $translates = array();
		else require_once($trans_path);

		if (isset($translates) && count($translates)>0) foreach ($translates as $k=>$v) $text = str_replace('[L:'.$k.']', $v, $text);

		// IFLAND
		$text = preg_replace('#\[IFLANG\:'._LANG_.'\](.+?)\[\/IFLANG\]#si', '$1', $text);
		$text = preg_replace('#\[IFLANG\:[^\]]+\](.+?)\[\/IFLANG\]#si', '', $text);		

		return $text;
		}

	}