<?php

// АДРЕСА САЙТА
$config['site']['url'] = 'https://24ex.net';
$config['site']['admin_path'] = 'admin';
$config['site']['callback'] = 'callback.php';

// ТАЙМИНГ ДЛЯ ВОССТАНОВЛЕНИЯ ПАРОЛЯ В МИНУТАХ
$config['site']['restore_time'] = 15;

// ТГ БОТ
$config['tg']['bot'] = 'coincash_test';
$config['tg']['token'] = '1685891551:AAHcEileaobDe1DhSWAzaZ1iJCmlcYT_d6I';

// GOOGLE 2FA
$config['2fa']['name'] = 'PIO.MONSTER';

// БАЗА
$config['db']['host'] = 'localhost';
$config['db']['login'] = 'root';
$config['db']['pass'] = 'root';
$config['db']['base'] = 'piomonster';

// ЯЗЫКИ
$config['lang']['list'] = ['ru', 'en'];        // все доступные языки
$config['lang']['default'] = 'ru';            // дефолтный язык

// ЯЗЫКИ ДЛЯ АДМИНКИ
$config['lang_admin']['list'] = ['ru'];
$config['lang_admin']['default'] = 'ru';

// ДАННЫЕ МЕЙЛЕРА
$config['mail']['from'] = '--';
$config['mail']['pass'] = '--';
$config['mail']['smtp'] = '--';
$config['mail']['name'] = '--';
$config['mail']['port'] = 587;
$config['mail']['subject_default'] = 'PIO.MONSTER'; // заголовок берется из <title> тэга письма, если тэга нет, то будет браться этот

// COINPAYMENTSAPI
$config['coinpayments']['merchant_id'] = '9f2f3b5af7d2af30fbd663b013a70fd3';
$config['coinpayments']['ipn_secret'] = 'MY_TESTING_IPN_SECRET';
$config['coinpayments']['private'] = '7eBE97B28187bD2190604819F11e85AeA9a6e3e46f49a062aE46A6EacE9dCb8B'; //'0660A0EAa6C6578b92F2E142cD8103160C1e381bc71a76942638ef6047dEA780';
$config['coinpayments']['public'] = 'afc8fd7261473fbf8a804718d574a795a8f5b75f13c9bb355b3376c7f46b1b0a'; //'d7438e4cb4a8cc82c95cd8ae91bd27d8058318e19add48e4b3e4c797ccc924e0';

// PAYEER
$config['payeer']['shop_id'] = '1351427710';
$config['payeer']['secret'] = 'MY_TESTING_IPN_SECRET';
$config['payeer']['enc_key'] = '7eBE97B28187bD2190604819F11e85AeA9a6e3e46f49a062aE46A6EacE9dCb8B';

// Google oAuth
$config['google']['client'] = '582442005005-jdtr5fd6s9g6vroinbo3egsm2bd8gfn6.apps.googleusercontent.com';
$config['google']['secret'] = '_TXhFuo-Dl0gRfL_SQxPVUvZ';

// Аватары сжатие
$config['avatar']['size'] = 300;

return $config;