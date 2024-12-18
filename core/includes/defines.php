<?php

// ADMIN TYPES
define(	'AT_SUPER',					0 );
define(	'AT_ADMIN',					1 );
define(	'AT_MODER',					2 );

// FOR LOGS
define( 'ACC_UNAUTH',				0 );
define( 'ACC_USER',					1 );
define( 'ACC_ADMIN',				2 );

// СТАТУС БАЛАНСОВ
define(	'BAL_STATUS_OFF',			0 );
define(	'BAL_STATUS_ON',			1 );
define(	'BAL_STATUS_OFF_PAYIN',		2 );
define(	'BAL_STATUS_OFF_PAYOUT',	3 );

// ИСТОЧНИКИ БАЛАНСОВ
define( 'BAL_SRC_HAND',				0 );
define( 'BAL_SRC_COINPAYMENTS',		1 );
define( 'BAL_SRC_CRYPTONATOR',		2 );
define( 'BAL_SRC_CBR',				3 );
define( 'BAL_SRC_PRIVAT24',			4 );

// PAYTYPES
define( 'PAY_TYPE_IN', 				0 );
define( 'PAY_TYPE_OUT', 			1 );
define( 'PAY_TYPE_CHANGE_IN', 		2 );
define( 'PAY_TYPE_CHANGE_OUT', 		3 );
define( 'PAY_TYPE_TRANSFER', 		4 );
define( 'PAY_TYPE_ORDER',			5 );

// PAY STATUS
define( 'PAY_STATUS_NEW',			0 );
define( 'PAY_STATUS_IN_WORK',		1 );
define( 'PAY_STATUS_CANCEL',		2 );
define( 'PAY_STATUS_REJECT',		3 );
define( 'PAY_STATUS_DONE',			4 );
define( 'PAY_STATUS_PAYS' , 		5 );
define( 'PAY_STATUS_PENDING',		6 );
define( 'PAY_STATUS_GO_PAY',		7 );
define( 'PAY_STATUS_USER_PAYS',		8 );
define( 'PAY_STATUS_SEND_PROPS',	9 );

// PS TYPES
define( 'PS_TYPE_IN', 				0 );
define( 'PS_TYPE_OUT', 				1 );

// VOUCHERS
define( 'VOUCHER_ACTIVE', 			0 );
define( 'VOUCHER_LOCK', 			1 );

// REASONS
define( 'REASON_PAYMENT', 				0 );
//define( 'REASON_BACKOUT',				1 );
define( 'REASON_REFPAY',				2 );
define( 'REASON_PAYOUT_PAY',			3 );
define( 'REASON_PAYOUT_COM',			4 );
define( 'REASON_BACK_PAY',				5 );
define( 'REASON_BACK_COM',				6 );
define( 'REASON_NEW_VOUCHER',			7 );
define( 'REASON_NEW_VOUCHER_COM',		8 );
define( 'REASON_VOUCHER_PAY',			9 );
define( 'REASON_CHANGEIN_PAY',			10 );
define( 'REASON_CHANGEIN_COM',			11 );
define( 'REASON_CHANGEIN_BACK_PAY',		12 );
define( 'REASON_CHANGEIN_BACK_COM',		13 );
define( 'REASON_INVEST_NEW',			14 );
define( 'REASON_INVEST_PAYED',			15 );
define( 'REASON_INVEST_DEFROST',		16 );
define( 'REASON_INVEST_REFPAY_DEPO',	17 );
define( 'REASON_INVEST_REFPAY_PROC',	18 );
define( 'REASON_INVEST_APPEND',			19 );
define( 'REASON_TRANSFER_PAY',			20 );
define( 'REASON_TRANSFER_COM',			21 );
define(	'REASON_TRANSFER_GET_PAY',		22 );
define( 'REASON_TRANSFER_BACK_PAY',		23 );
define( 'REASON_TRANSFER_BACK_COM',		24 );
define( 'REASON_ORDER_PAY', 			25 );
define( 'REASON_ORDER_COM', 			26 );


// INVEST LOGS
define( 'INV_LOG_NEW',				0 );
define( 'INV_LOG_PROC',				1 );
define( 'INV_LOG_PAYED',			2 );
define( 'INV_LOG_DEFROST_VAL',		3 );
define( 'INV_LOG_DEFROST_PROC',		4 );
define( 'INV_LOG_APPEND',			5 );

// USER INVEST STATUS
define( 'INV_STATUS_ON',			0 );
define( 'INV_STATUS_END',			1 );
define( 'INV_STATUS_OFF',			2 );
define( 'INV_STATUS_PAYED',			3 );
define( 'INV_STATUS_DEFROST',		4 );

// A STATUS
define( 'A_STATUS_NEW',				0 );
define( 'A_STATUS_SEND',			1 );
define( 'A_STATUS_ERR',				2 );


// REF PLANS
define( 'RP_DEPOSIT', 				0 );
define( 'RP_INVEST',				1 );

// СТАТУСЫ НОВОСТЕЙ
define( 'N_STATUS_DRAFT', 			0 );
define( 'N_STATUS_PUB',				1 );
define( 'N_STATUS_FUTURE',			2 );

// API ERRORS
define( 'API_ERR_OK', 				0 );
define( 'API_ERR_WRONG_SIGN', 		1 );
define( 'API_ERR_WRONG_ACT', 		2 );
define( 'API_ERR_WRONG_REQUEST', 	3 );
//define( 'API_ERR_WRONG_PAY_ID', 	4 );
define( 'API_ERR_TIMEOUT', 			5 );
define( 'API_ERR_USER_ID', 			6 );

// PAYMENTS ERRORS
define( 'PAY_ERR_OK',				0 );
define( 'PAY_ERR_INPUT_DATA',		1 );
define( 'PAY_ERR_LOCK',				2 );
define( 'PAY_ERR_LIMITS',			3 );
define( 'PAY_ERR_NA',				4 );
define( 'PAY_ERR_UB_NUM',			5 );
define(	'PAY_ERR_UB_NUM_BAL', 		6 );
define( 'PAY_ERR_PAYCODE', 			7 );
define( 'PAY_ERR_UB_VALUE', 		8 );
define( 'PAY_ERR_LIMITS_2',			9 );
define( 'PAY_ERR_CH_VALUE', 		10 );

// NEW ORDER
define( 'CHECK_NEWORDER_OK',		0 );
define( 'CHECK_NEWORDER_FIELDS',	1 );
define( 'CHECK_NEWORDER_NOSHOP',	2 );
define( 'CHECK_NEWORDER_SHOPLOCK',	3 );
define( 'CHECK_NEWORDER_NOPSS', 	4 );
define( 'CHECK_NEWORDER_SIGN',		5 );
define( 'CHECK_NEWORDER_UNIQID', 	6 );
define( 'CHECK_NEWORDER_ERROR', 	7 );

// ORDER STATUS
define( 'ORDER_STATUS_NEW',			0 );
define( 'ORDER_STATUS_PAYS',		1 );
define( 'ORDER_STATUS_DONE',		2 );
define( 'ORDER_STATUS_CANCEL',		3 );

// ORDER CALLBACKS
define( 'ORDER_CALLBACK_NONE',		0 );
define( 'ORDER_CALLBACK_DONE',		1 );
define( 'ORDER_CALLBACK_ERROR',		2 );
define( 'ORDER_CALLBACK_TRY_END',	3 );

// КОЛБЭКИ
define('ANSWER_OK', 				0);		// запрос успешно обработан
define('ANSWER_ERR_UNKNOWN', 	   -1);		// неизвестная ошибка
define('ANSWER_ERR_SIGN', 			1);		// неверная подпись
define('ANSWER_ERR_SHOP', 			2);		// неверный магазин
define('ANSWER_ERR_ORDER_WRONG', 	3);		// неверный заказ
define('ANSWER_ERR_ORDER_STATUS', 	4);		// заказ уже был отменен или подтвержден
define('ANSWER_ERR_EMPTY', 	   		100);	// пустой или некорректный ответ от колбэка

// все возможные статусы оплаты
define('PAY_SUCCESS', 0);			// оплата прошла
define('PAY_REJECT',  1);			// оплата отклонена

// ЛОГИ
define(	'LOG_REG',					0 );
define( 'LOG_LOGIN', 				1 );
define( 'LOG_LOGOUT', 				2 );
define( 'LOG_CHANGE_USER_DATA', 	3 );
define( 'LOG_PASS_CHANGE', 			4 );
define( 'LOG_RESTORE_SEND', 		5 );
define( 'LOG_LOGIN_RESTORE', 		6 );
define( 'LOG_TG_BOT_REG',			7 );
define( 'LOG_TG_BOT_UNREG',			8 );
define( 'LOG_LOGIN_WRONG_2FA',		9 );
define( 'LOG_2FA_ON',				10 );
define( 'LOG_2FA_OFF',				11 );
define( 'LOG_PAYCODE_CHANGE',		12 );
define( 'LOG_LOGIN_WRONG_PASS', 	13 );

define( 'LOG_DELETE_ADMIN', 		14 );
define( 'LOG_NEW_ADMIN', 			15 );
define( 'LOG_UPD_ADMIN', 			16 );

define( 'LOG_NEW_BAL_TYPE',			17 );
define( 'LOG_UPD_BAL_TYPE',			18 );
define( 'LOG_DELETE_BAL_TYPE',		19 );

define( 'LOG_DELETE_BAL', 			20 );
define( 'LOG_UPDATE_BAL', 			21 );
define( 'LOG_CREATE_BAL', 			22 );

define( 'LOG_FEEDBACK_MARK_ANS', 	23 );
define( 'LOG_FEEDBACK_SEND_ANS', 	24 );

define( 'LOG_UB_ADD',				25 );
define( 'LOG_UB_DEL',				26 );

define( 'LOG_PAYIN_NEW', 			27 );
define( 'LOG_PAYOUT_NEW',			28 );
define( 'LOG_PAY_CHANGE_COMM',		29 );
define( 'LOG_PAY_CANCEL',			30 );
define( 'LOG_PAY_NEW_STATUS',		31 );
define( 'LOG_PAY_NEW_ADM_COMM',		32 );

define( 'LOG_SECUR_CHANGE_IPS',		33 );

define( 'LOG_USER_LOCK',			34 );
define( 'LOG_USER_UNLOCK',			35 );
define( 'LOG_USER_RESET_PAYCODE',	36 );
define( 'LOG_USER_RESET_2FA',		37 );

define( 'LOG_ONE_IP_LOCK',			38 );
define( 'LOG_UB_CHANGE_LOCK', 		39 );

define( 'LOG_PAYSYS_IN_ERROR', 		40 );

define( 'LOG_MAIN_SETTINGS_SAVE',	41 );

define( 'LOG_NEW_PAYSYS', 			42 );
define( 'LOG_CHANGE_PAYSYS', 		43 );

define( 'LOG_PAYIN_ENTER_PROPS', 	44 );

define( 'LOG_VOUCHER_NEW', 			45 );
define( 'LOG_PAY_VOUCHER', 			46 );

define( 'LOG_PAYSYS_OUT_ERROR', 	47 );

define( 'LOG_PAYIN_GOPAY',			48 );
define( 'LOG_PAYOUT_GOPAY',			49 );

define( 'LOG_UPDATE_CH',			50 );
define( 'LOG_CH_CHANGE_MINUS',		51 );
define( 'LOG_CH_CHANGE_PLUS',		52 );

define( 'LOG_PAYCHANGE_IN_NEW',		53 );
define( 'LOG_PAYCHANGE_OUT_NEW',	54 );

define( 'LOG_ASS_SAVE',				55 );

define( 'LOG_NEWS_NEW',				56 );
define( 'LOG_NEWS_UPD',				57 );
define( 'LOG_NEWS_DEL',				58 );
define( 'LOG_NEWS_TG',				59 );

define( 'LOG_INVEST_PLAN_NEW',		60 );
define( 'LOG_INVEST_PLAN_UPD',		61 );
define( 'LOG_INVEST_PLAN_DEL',		62 );

define(	'LOG_USER_INVEST_NEW',		63 );
define(	'LOG_USER_INVEST_UPD',		64 );
define( 'LOG_USER_INVEST_DEFROST',	65 );
define( 'LOG_USER_INVEST_APPEND',	66 );

define( 'LOG_PAY_TRANFER_NEW', 		67 );
define( 'LOG_PAY_TRANFER_PROPS',	68 );
define( 'LOG_PAY_TRANFER_DONE',		69 );
define( 'LOG_PAY_TRANFER_RESET', 	70 );
define( 'LOG_PAY_TRANFER_PAYS', 	71 );

define( 'LOG_GEN_UAPI', 			72 );

define( 'LOG_MERCH_CONFIRM', 		73 );
define( 'LOG_MERCH_MODER_SEND',		74 );
define( 'LOG_MERCH_PSS_SAVE', 		75 );
define( 'LOG_MERCH_MODER_CHANGE', 	76 );
define( 'LOG_MERCH_CHANGE_PRC', 	77 );

define( 'LOG_ORDER_NEW', 			78 );
define( 'LOG_ORDER_GOPAY',			79 );
define( 'LOG_ORDER_NEW_STATUS',		80 );