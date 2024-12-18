<!DOCTYPE html>

<html lang="en">
	<!--begin::Head-->
	<head><base href="/">
		<meta charset="utf-8" />
		<title>Payment</title>
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
		<link href="assets/css/style.bundle.office.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/style.css?rand=<?=rand(0,1000000);?>" rel="stylesheet" type="text/css" />
		<!--end::Global Theme Styles-->
		<!--begin::Layout Themes(used by all pages)-->
		<!--end::Layout Themes-->
		<link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled sidebar-enabled page-loading">

		<script>
			var ajaxurl = '<?=$var_ajax_url;?>';
			var nowlang = '<?=_LANG_;?>';
		</script>


		<?
		// модалы
		require_once(_TEMPLATES_.'/__modals.php');
		?>

		<div class="all-alerts" id="all-alerts">
		</div>

		<? require_once($var_page_path); ?>

		<!--begin::Global Config(global config for global JS scripts)-->

		<!--end::Global Config-->
		<!--begin::Global Theme Bundle(used by all pages)-->
		<script src="assets/plugins/global/plugins.bundle.js"></script>
		<script src="assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
		<script src="assets/js/scripts.bundle.js"></script>

		<script src="/assets/js/funcs.js?rand=<?=rand(0,1000000);?>"></script>
		<script src="/assets/js/site/<?=$var_get_page;?>.js?rand=<?=rand(0,1000000);?>"></script>
		
	</body>
	<!--end::Body-->
</html>