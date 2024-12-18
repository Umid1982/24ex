<?php

// показать ошибки
error_reporting(E_ALL);

// пути универсальные
define(	'_ROOT_',		__DIR__.'/../..'	);
define(	'_CORE_',		__DIR__.'/..'		);
define(	'_CLASSES_',	_CORE_.'/classes'	);
define(	'_INC_',		_CORE_.'/includes'	);
define(	'_PAYSYS_',		_CORE_.'/paysys'	);

// подключаем всё что есть
require_once( _ROOT_ . '/config.php' );
require_once( _INC_  . '/functions.php' );

// подключаем все классы
$all_libs = glob( _CLASSES_ . '/*.php' );
foreach ($all_libs as $one)
	{
	require_once($one);
	}

// константы
require_once( _INC_ . '/defines.php' );