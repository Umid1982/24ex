<?

class Paysys
	{
	protected $db = false;

	function __construct($db)
		{
		$this->db = $db;
		}

	// варианты вывода по типу
	function getVariants($type)
		{
		$ret = [];

		$vars = $this->getOneVariants();
		foreach ($vars as $var_name=>$one)
			{
			if ($one[$type])
				{
				$ret[$this->name.'||'.$var_name] = $one;
				}
			}

		return $ret;
		}

	function getInfo($var_name)
		{
		$vars = $this->getOneVariants();
		if (isset($vars[$var_name])) return $vars[$var_name];
		else return false;
		}

	function getSuccessUrl()
		{
		$url = '/'._LANG_.'/office/paystat';
		return $url;
		}

	function getCancelUrl()
		{
		$url = '/'._LANG_.'/office/paystat';
		return $url;
		}
	}