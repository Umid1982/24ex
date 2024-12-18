<?php

define('COINCASH_ADMIN',true);

// модели и темплейты
define(	'_ADMIN_',		_ROOT_.'/'.$config['site']['admin_path'] );
define(	'_MODELS_',		_ADMIN_.'/models' );
define(	'_TEMPLATES_',	_ADMIN_.'/templates' );

// главная модель первой
require_once( _MODELS_ . '/model.php' );

// все модели
$all_models = glob( _MODELS_ . '/*.model.php' );
array_multisort(array_map('strlen', $all_models), $all_models);
foreach ($all_models as $one) { require_once($one); }

// admin masks
define( 'R_SUPER', 	0b1000 );
define( 'R_ADMIN', 	0b0100 );
define( 'R_MODER', 	0b0010 );
define( 'R_UNAUTH', 0b0001 );

define( 'CAN_SUPER',  R_SUPER );
define( 'CAN_ADMIN',  R_SUPER | R_ADMIN );
define( 'CAN_MODER',  R_SUPER | R_ADMIN | R_MODER );
define( 'CAN_UNAUTH', R_UNAUTH );

// per page admin
define( 'PER_PAGE', 20 );