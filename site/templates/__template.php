<?php

/*
	$var_page_path - путь до запрашиваемой страницы
	$var_alert - false если нет алертов, массив данных если есть
		[type] - тип, может быть error/info/success
		[mess] - сообщение, нужно забить его в файле __translate.php для корректного перевода
		[adds] - дополнительные поня, для некоторых алертов
*/

?>

<!DOCTYPE html>
<html>
<head><base href="/">
	<title>[L:TITLE_MAIN]</title>
	<meta name="description" content="Singin page example" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<!--begin::Fonts-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<!--end::Fonts-->
	<!--begin::Page Custom Styles(used by this page)-->
	<link href="assets/css/pages/login/login-4.css" rel="stylesheet" type="text/css" />
	<!--end::Page Custom Styles-->
	<!--begin::Global Theme Styles(used by all pages)-->
	<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/style.css?rand=<?=rand(0,1000000);?>" rel="stylesheet" type="text/css" />
	<!--end::Global Theme Styles-->
	<!--begin::Layout Themes(used by all pages)-->
	<!--end::Layout Themes-->
	<link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
</head>
<body style="padding:50px;">

	<script>
		var ajaxurl = '<?=$var_ajax_url;?>';
		var nowlang = '<?=_LANG_;?>';
	</script>

	<style>
		.modal { display: none !important; }
	</style>

<h4>
	<a href="/ru/">Главная</a> | 
	<a href="/ru/news">Новости</a> | 
	<a href="/ru/auth">Авторизация</a> | 
	<a href="/ru/auth#signup">Регистрация</a> | 
	<a href="/ru/auth#forgot">Восстановление пароля</a> | 
	<a href="/ru/feedback">Обратная связь</a> | 
	<a href="/ru/payment/changeout">Обменник (внешний)</a> | 
	<a href="/ru/rates">Экспорт курсов</a> | 

[IFLANG:ru]
	[ <a href="#">RU</a> | <a href="<?=$var_current_page_en;?>">EN</a> ]
[/IFLANG]
[IFLANG:en]
	[ <a href="<?=$var_current_page_ru;?>">RU</a> | <a href="#">EN</a> ]
[/IFLANG]
</h4>

<div class="all-alerts" id="all-alerts">
</div>

<?php
	// модалы
	require_once(_TEMPLATES_.'/__modals.php');

	// универсальные алерты
	if ($var_alert!==false)
		{
		?>

		<script>
			$(document).ready(function(){
				showAlert("[L:<?=$var_alert['mess'];?>]");
			});
		</script>

		<?php
		}

	// подключаем страницу
	require_once($var_page_path);
?>
	<!-- global -->
	<script src="assets/plugins/global/plugins.bundle.js"></script>
	<script src="assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
	<script src="assets/js/scripts.bundle.js"></script>

	<!-- personal scripts -->
	<script src="/assets/js/funcs.js?rand=<?=rand(0,1000000);?>"></script>
	<script src="/assets/js/site/<?=$var_get_page;?>.js?rand=<?=rand(0,1000000);?>"></script>
</body>
</html>