<!DOCTYPE html>
<!--
Template Name: Metronic - Bootstrap 4 HTML, React, Angular 11 & VueJS Admin Dashboard Theme
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: https://1.envato.market/EA4JP
Renew Support: https://1.envato.market/EA4JP
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">
	<!--begin::Head-->
	<head><base href="../">
		<meta charset="utf-8" />
		<title>Admin panel</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Page Vendors Styles(used by this page)-->
		<link href="assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Page Vendors Styles-->
		<!-- jquery ui -->
		<link href="assets/css/jquery-ui.css" rel="stylesheet" type="text/css" />
		<!--begin::Global Theme Styles(used by all pages)-->
		<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/style.bundle.admin.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/admin.css?rand=<?=rand(0,1000000);?>" rel="stylesheet" type="text/css" />
		<!--end::Global Theme Styles-->
		<!--begin::Layout Themes(used by all pages)-->
		<!--end::Layout Themes-->
		<link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-mobile-fixed sidebar-enabled page-loading">
		<script>
				var ajaxurl = "<?=$var_ajax_url;?>"; 
				var adminurl = "<?=$var_adm_path;?>";
				var nowpage = "<?=$var_now_page;?>";
				var maxpg = "<?=$var_paging_max;?>";
				var nowpg = "<?=$var_paging_now;?>";
				var nowtm = "<?=$var_now_time;?>";
		</script>

		<!--begin::Main-->
		<!--begin::Header Mobile-->
		<div id="kt_header_mobile" class="header-mobile header-mobile-fixed">
			<!--begin::Logo-->
			<a href="admin/">
				<img alt="Logo" src="assets/media/logos/logo-letter-1.png" class="logo-default max-h-30px" />
			</a>
			<!--end::Logo-->
			<!--begin::Toolbar-->
			<div class="d-flex align-items-center">
				<button class="btn p-0 burger-icon burger-icon-left rounded-0" id="kt_header_mobile_toggle">
					<span></span>
				</button>
				<button class="btn btn-hover-icon-primary p-0 ml-5" id="kt_sidebar_mobile_toggle">
					<span class="svg-icon svg-icon-xl">
						<!--begin::Svg Icon | path:/metronic/theme/html/demo10/dist/assets/media/svg/icons/Design/Substract.svg-->
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<rect x="0" y="0" width="24" height="24" />
								<path d="M6,9 L6,15 C6,16.6568542 7.34314575,18 9,18 L15,18 L15,18.8181818 C15,20.2324881 14.2324881,21 12.8181818,21 L5.18181818,21 C3.76751186,21 3,20.2324881 3,18.8181818 L3,11.1818182 C3,9.76751186 3.76751186,9 5.18181818,9 L6,9 Z" fill="#000000" fill-rule="nonzero" />
								<path d="M10.1818182,4 L17.8181818,4 C19.2324881,4 20,4.76751186 20,6.18181818 L20,13.8181818 C20,15.2324881 19.2324881,16 17.8181818,16 L10.1818182,16 C8.76751186,16 8,15.2324881 8,13.8181818 L8,6.18181818 C8,4.76751186 8.76751186,4 10.1818182,4 Z" fill="#000000" opacity="0.3" />
							</g>
						</svg>
						<!--end::Svg Icon-->
					</span>
				</button>
				<button class="btn btn-hover-icon-primary p-0 ml-2" id="kt_aside_mobile_toggle">
					<span class="svg-icon svg-icon-xl">
						<!--begin::Svg Icon | path:/metronic/theme/html/demo10/dist/assets/media/svg/icons/General/User.svg-->
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<polygon points="0 0 24 0 24 24 0 24" />
								<path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
								<path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
							</g>
						</svg>
						<!--end::Svg Icon-->
					</span>
				</button>
			</div>
			<!--end::Toolbar-->
		</div>
		<!--end::Header Mobile-->

		<div class="d-flex flex-column flex-root">
			<!--begin::Page-->
			<div class="d-flex flex-row flex-column-fluid page">
				<!--begin::Aside-->
				<div class="aside aside-left d-flex flex-column" id="kt_aside">
					<!--begin::Brand-->
					<div class="aside-brand d-flex flex-column align-items-center flex-column-auto py-9">
						<!--begin::Logo-->
						<div class="btn p-0 symbol symbol-40 symbol-success" href="?page=index" id="kt_quick_user_toggle">
							<div class="symbol-label">
								<img alt="Logo" src="assets/media/svg/avatars/007-boy-2.svg" class="h-75 align-self-end" />
							</div>
						</div>
						<!--end::Logo-->
					</div>
					<!--end::Brand-->
					<!--begin::Nav Wrapper-->
					<div class="aside-nav d-flex flex-column align-items-center flex-column-fluid pb-10">

						<a href="/<?=$var_adm_path;?>/?page=admsettings" class="btn btn-icon btn-lg btn-borderless mb-1 position-relative 
							<?=($var_get_page=='admsettings' ? 'active' : '');?>" data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window" title="Персональные настройки">
							<span class="svg-icon svg-icon-xxl">
								<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Group.svg-->
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
									<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								        <rect x="0" y="0" width="24" height="24"/>
								        <path d="M6,2 L18,2 C19.6568542,2 21,3.34314575 21,5 L21,19 C21,20.6568542 19.6568542,22 18,22 L6,22 C4.34314575,22 3,20.6568542 3,19 L3,5 C3,3.34314575 4.34314575,2 6,2 Z M12,11 C13.1045695,11 14,10.1045695 14,9 C14,7.8954305 13.1045695,7 12,7 C10.8954305,7 10,7.8954305 10,9 C10,10.1045695 10.8954305,11 12,11 Z M7.00036205,16.4995035 C6.98863236,16.6619875 7.26484009,17 7.4041679,17 C11.463736,17 14.5228466,17 16.5815,17 C16.9988413,17 17.0053266,16.6221713 16.9988413,16.5 C16.8360465,13.4332455 14.6506758,12 11.9907452,12 C9.36772908,12 7.21569918,13.5165724 7.00036205,16.4995035 Z" fill="#000000"/>
								    </g>
								</svg>
								<!--end::Svg Icon-->
									</span>
							</span>
						</a>

					</div>
					<!--end::Nav Wrapper-->							
					<!--begin::Footer-->
					<div class="aside-footer d-flex flex-column align-items-center flex-column-auto py-8">
						<!--begin::Notifications-->
						<a href="/<?=$var_adm_path;?>/?page=feedback" class="btn btn-icon btn-lg btn-borderless mb-1 position-relative 
							<?=($var_get_page=='feedback' ? 'active' : '');?>" data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window" title="Обратная связь">
							<span class="svg-icon svg-icon-xxl">
								<!--begin::Svg Icon | path:assets/media/svg/icons/Design/Layers.svg-->
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
									<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										<polygon points="0 0 24 0 24 24 0 24" />
										<path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero" />
										<path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3" />
									</g>
								</svg>
								<!--end::Svg Icon-->
							</span>

						<? if ($var_feedbacks_count>0) { ?>
							<span class="label label-sm label-light-danger label-rounded font-weight-bolder position-absolute top-0 right-0 mt-1 mr-1"><?=$var_feedbacks_count;?></span>
						<? }?>
						</a>
						<!--end::Notifications-->
					</div>
					<!--end::Footer-->

				</div>
				<!--end::Aside-->
				<!--begin::Wrapper-->
				<div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">

				<? require_once _TEMPLATES_.'/__header_menu.php'; ?>

					
					<? require_once $var_page_path; ?>

				</div>
				<!--end::Wrapper-->
				

				<!--begin::Aside Secondary-->
				<div class="sidebar sidebar-right d-flex flex-row-auto flex-column" id="kt_sidebar">

					<!--begin::Aside Secondary Content-->
					<div class="sidebar-content flex-column-fluid pb-10 pt-9 px-5 px-lg-10">
						
						
						<!--begin::List Widget 9-->
						<div class="card card-custom card-shadowless bg-white">
							<!--begin::Header-->
							<div class="card-header align-items-center border-0 mt-4">
								<h3 class="card-title align-items-start flex-column">
									<span class="font-weight-bolder text-dark">Лог действий</span>
									<span class="d-block text-muted mt-2 font-size-sm">Последние 10 действий на аккаунте</span>
								</h3>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body pt-4">
								<!--begin::Timeline-->
								<div class="timeline timeline-6 mt-3">

								<? foreach ($var_admin_logs as $one_log) { ?>

									<!--begin::Item-->
									<div class="timeline-item align-items-start table-logs">
										<!--begin::Label-->
										<div class="timeline-label font-weight-bolder text-dark-75 font-size-lg"><?=$one_log['dt_line'];?></div>
										<!--end::Label-->
										<!--begin::Badge-->
										<div class="timeline-badge">
											<i class="fa fa-genderless text-warning icon-xl"></i>
										</div>
										<!--end::Badge-->
										<!--begin::Text-->
										<div class="timeline-content font-weight-mormal font-size-lg pl-3"><?=$one_log['text'];?></div>
										<!--end::Text-->
									</div>
									<!--end::Item-->

								<? } ?>

									
								</div>
								<!--end::Timeline-->
							</div>
							<!--end: Card Body-->
						</div>
						<!--end: List Widget 9-->
					</div>
					<!--end::Aside Secondary Content-->
				</div>
				<!--end::Aside Secondary-->

			</div>
			<!--end::Page-->
		</div>
		<!--end::Main-->
		
		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop">
			<span class="svg-icon">
				<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Up-2.svg-->
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<polygon points="0 0 24 0 24 24 0 24" />
						<rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1" />
						<path d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z" fill="#000000" fill-rule="nonzero" />
					</g>
				</svg>
				<!--end::Svg Icon-->
			</span>
		</div>
		<!--end::Scrolltop-->
		
		<!--begin::Demo Panel-->
		<div id="kt_demo_panel" class="offcanvas offcanvas-right p-10">
		<!-- begin::User Panel-->
		<div id="kt_quick_user" class="offcanvas offcanvas-left p-10">
			<!--begin::Header-->
			<div class="offcanvas-header d-flex align-items-center justify-content-between pb-5">
				<h3 class="font-weight-bold m-0">Добро пожаловать</h3>
				<a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_user_close">
					<i class="ki ki-close icon-xs text-muted"></i>
				</a>
			</div>
			<!--end::Header-->
			<!--begin::Content-->
			<div class="offcanvas-content pr-5 mr-n5">
				<!--begin::Header-->
				<div class="d-flex align-items-center mt-5">
					<div class="symbol symbol-100 mr-5">
						<div class="symbol-label" style="background-image:url('assets/media/users/300_21.jpg')"></div>
						<i class="symbol-badge bg-success"></i>
					</div>
					<div class="d-flex flex-column">
						<a href="#" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary"><?=$var_admin_login;?></a>
						<div class="text-muted mt-1"><?=$var_admin_type;?></div>
						<br>
						<a href="/<?=$var_adm_path;?>/?action=logout" class="btn btn-sm btn-light-primary font-weight-bolder py-2 px-5">Выйти</a>
					</div>
				</div>
				<!--end::Header-->
				
			</div>
			<!--end::Content-->
		</div>
		<!-- end::User Panel-->

		<div class="all-alerts" id="all-alerts">
		</div>

		</div>
		<!--end::Demo Panel-->
		<!--begin::Global Config(global config for global JS scripts)-->
		<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1200 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#663259", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#F3F6F9", "dark": "#212121" }, "light": { "white": "#ffffff", "primary": "#F4E1F0", "secondary": "#ECF0F3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#212121", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#ECF0F3", "gray-300": "#E5EAEE", "gray-400": "#D6D6E0", "gray-500": "#B5B5C3", "gray-600": "#80808F", "gray-700": "#464E5F", "gray-800": "#1B283F", "gray-900": "#212121" } }, "font-family": "Poppins" };</script>
		<!--end::Global Config-->
		<!--begin::Global Theme Bundle(used by all pages)-->
		<script src="assets/plugins/global/plugins.bundle.js"></script>
		<script src="assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
		<script src="assets/js/scripts.bundle.js"></script>
		<!--jQuery:min-->
		<script src="assets/js/jquery-ui.min.js"></script>
		<!--end::Global Theme Bundle-->
		<!--begin::Page Vendors(used by this page)-->
		<script src="assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
		<script src="//maps.google.com/maps/api/js?key=AIzaSyBTGnKT7dt597vo9QgeQ7BFhvSRP4eiMSM"></script>
		<script src="assets/plugins/custom/gmaps/gmaps.js"></script>
		<!--end::Page Vendors-->

		<!--ckeditor-->
		<script src="https://cdn.tiny.cloud/1/fp7ktt3rt313hlyg5bkdxrbk0px5lep7qzyg16izg9sl1gov/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

		<!--begin::Page Scripts(used by this page)-->
		<script src="assets/js/funcs.js?rand=<?=rand(0,1000000);?>"></script>
		<script src="assets/js/admin/global.js?rand=<?=rand(0,1000000);?>"></script>
		<script src="assets/js/admin/<?=$var_get_page;?>.js?rand=<?=rand(0,1000000);?>"></script>
		<!--end::Page Scripts-->
	</body>
	<!--end::Body-->
</html>