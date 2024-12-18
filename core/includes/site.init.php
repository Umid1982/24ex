<?php

// модели и темплейты
define(	'_SITE_',		_ROOT_.'/site'		);
define(	'_MODELS_',		_SITE_.'/models'	);
define(	'_TEMPLATES_',	_SITE_.'/templates'	);

// главная модель первой
require_once( _MODELS_ . '/model.php' );

// все модели
$all_models = glob( _MODELS_ . '/*.model.php' );
array_multisort(array_map('strlen', $all_models), $all_models);
foreach ($all_models as $one) { require_once($one); }
