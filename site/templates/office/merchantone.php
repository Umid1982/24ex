<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<!--begin::Subheader-->
	<div class="subheader py-2 py-lg-12 subheader-transparent" id="kt_subheader">
		<div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<!--begin::Info-->
			<div class="d-flex align-items-center flex-wrap mr-1">
				<!--begin::Heading-->
				<div class="d-flex flex-column">
					<!--begin::Title-->
					<h2 class="text-white font-weight-bold my-2 mr-5">Редактировать магазин</h2>
					<!--end::Title-->
					<!--begin::Breadcrumb-->
					<div class="d-flex align-items-center font-weight-bold my-2">
						<!--begin::Item-->
						<a href="/" class="opacity-75 hover-opacity-100">
							<i class="flaticon2-shelter text-white icon-1x"></i>
						</a>
						<!--end::Item-->
						<!--begin::Item-->
						<span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>
						<a href="/<?=_LANG_;?>/office/" class="text-white text-hover-white opacity-75 hover-opacity-100">Личный кабинет</a>
						<span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>
						<a href="/<?=_LANG_;?>/office/merchants/" class="text-white text-hover-white opacity-75 hover-opacity-100">Магазины</a>
						<span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>
						<a href="javascript:return false;" class="text-white text-hover-white opacity-75 hover-opacity-100">Редактировать магазин</a>
						<!--end::Item-->
					</div>
					<!--end::Breadcrumb-->
				</div>
				<!--end::Heading-->
			</div>
			<!--end::Info-->
		</div>
	</div>
	<!--end::Subheader-->
	<!--begin::Entry-->
	<div class="d-flex flex-column-fluid">
		<!--begin::Container-->
		<div class="container">
			<!--begin::Card-->
			<div class="card card-custom">
				<!--begin::Card header-->
				<div class="card-header card-header-tabs-line nav-tabs-line-3x">
					<!--begin::Toolbar-->
					<div class="card-toolbar">
						<ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
							<!--begin::Item-->
							<li class="nav-item mr-3">
								<a class="nav-link active" data-toggle="tab" href="#kt_user_edit_tab_1">
									<span class="nav-icon">
										<span class="svg-icon">
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
									</span>
									<span class="nav-text font-size-lg">Настройки</span>
								</a>
							</li>
							<!--end::Item-->
						<? if (!$var_is_new) { ?>
							<!--begin::Item-->
							<li class="nav-item mr-3">
								<a class="nav-link" data-toggle="tab" href="#kt_user_edit_tab_2">
									<span class="nav-icon">
										<span class="svg-icon">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Shield-user.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											        <rect x="0" y="0" width="24" height="24"/>
											        <path d="M17.2718029,8.68536757 C16.8932864,8.28319382 16.9124644,7.65031935 17.3146382,7.27180288 C17.7168119,6.89328641 18.3496864,6.91246442 18.7282029,7.31463817 L22.7282029,11.5646382 C23.0906029,11.9496882 23.0906029,12.5503176 22.7282029,12.9353676 L18.7282029,17.1853676 C18.3496864,17.5875413 17.7168119,17.6067193 17.3146382,17.2282029 C16.9124644,16.8496864 16.8932864,16.2168119 17.2718029,15.8146382 L20.6267538,12.2500029 L17.2718029,8.68536757 Z M6.72819712,8.6853647 L3.37324625,12.25 L6.72819712,15.8146353 C7.10671359,16.2168091 7.08753558,16.8496835 6.68536183,17.2282 C6.28318808,17.6067165 5.65031361,17.5875384 5.27179713,17.1853647 L1.27179713,12.9353647 C0.909397125,12.5503147 0.909397125,11.9496853 1.27179713,11.5646353 L5.27179713,7.3146353 C5.65031361,6.91246155 6.28318808,6.89328354 6.68536183,7.27180001 C7.08753558,7.65031648 7.10671359,8.28319095 6.72819712,8.6853647 Z" fill="#000000" fill-rule="nonzero"/>
											        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-345.000000) translate(-12.000000, -12.000000) " x="11" y="4" width="2" height="16" rx="1"/>
											    </g>
											</svg>
											<!--end::Svg Icon-->
										</span>
									</span>
									<span class="nav-text font-size-lg">Подключение на сайте</span>
								</a>
							</li>
							<!--end::Item-->
							<!--begin::Item-->
							<li class="nav-item mr-3">
								<a class="nav-link" data-toggle="tab" href="#kt_user_edit_tab_3">
									<span class="nav-icon">
										<span class="svg-icon">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Shield-user.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24" />
													<path d="M4,4 L11.6314229,2.5691082 C11.8750185,2.52343403 12.1249815,2.52343403 12.3685771,2.5691082 L20,4 L20,13.2830094 C20,16.2173861 18.4883464,18.9447835 16,20.5 L12.5299989,22.6687507 C12.2057287,22.8714196 11.7942713,22.8714196 11.4700011,22.6687507 L8,20.5 C5.51165358,18.9447835 4,16.2173861 4,13.2830094 L4,4 Z" fill="#000000" opacity="0.3" />
													<path d="M12,11 C10.8954305,11 10,10.1045695 10,9 C10,7.8954305 10.8954305,7 12,7 C13.1045695,7 14,7.8954305 14,9 C14,10.1045695 13.1045695,11 12,11 Z" fill="#000000" opacity="0.3" />
													<path d="M7.00036205,16.4995035 C7.21569918,13.5165724 9.36772908,12 11.9907452,12 C14.6506758,12 16.8360465,13.4332455 16.9988413,16.5 C17.0053266,16.6221713 16.9988413,17 16.5815,17 C14.5228466,17 11.463736,17 7.4041679,17 C7.26484009,17 6.98863236,16.6619875 7.00036205,16.4995035 Z" fill="#000000" opacity="0.3" />
												</g>
											</svg>
											<!--end::Svg Icon-->
										</span>
									</span>
									<span class="nav-text font-size-lg">Проверка и модерация</span>
								</a>
							</li>
							<!--end::Item-->
							<!--begin::Item-->
							<li class="nav-item mr-3">
								<a class="nav-link" data-toggle="tab" href="#kt_user_edit_tab_4">
									<span class="nav-icon">
										<span class="svg-icon">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Shield-user.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											        <rect x="0" y="0" width="24" height="24"/>
											        <path d="M2,6 L21,6 C21.5522847,6 22,6.44771525 22,7 L22,17 C22,17.5522847 21.5522847,18 21,18 L2,18 C1.44771525,18 1,17.5522847 1,17 L1,7 C1,6.44771525 1.44771525,6 2,6 Z M11.5,16 C13.709139,16 15.5,14.209139 15.5,12 C15.5,9.790861 13.709139,8 11.5,8 C9.290861,8 7.5,9.790861 7.5,12 C7.5,14.209139 9.290861,16 11.5,16 Z" fill="#000000" opacity="0.3" transform="translate(11.500000, 12.000000) rotate(-345.000000) translate(-11.500000, -12.000000) "/>
											        <path d="M2,6 L21,6 C21.5522847,6 22,6.44771525 22,7 L22,17 C22,17.5522847 21.5522847,18 21,18 L2,18 C1.44771525,18 1,17.5522847 1,17 L1,7 C1,6.44771525 1.44771525,6 2,6 Z M11.5,16 C13.709139,16 15.5,14.209139 15.5,12 C15.5,9.790861 13.709139,8 11.5,8 C9.290861,8 7.5,9.790861 7.5,12 C7.5,14.209139 9.290861,16 11.5,16 Z M11.5,14 C12.6045695,14 13.5,13.1045695 13.5,12 C13.5,10.8954305 12.6045695,10 11.5,10 C10.3954305,10 9.5,10.8954305 9.5,12 C9.5,13.1045695 10.3954305,14 11.5,14 Z" fill="#000000"/>
											    </g>
											</svg>
											<!--end::Svg Icon-->
										</span>
									</span>
									<span class="nav-text font-size-lg">Валюты</span>
								</a>
							</li>
							<!--end::Item-->
						<? } ?>
						</ul>
					</div>
					<!--end::Toolbar-->
				</div>
				<!--end::Card header-->
				<!--begin::Card body-->
				<div class="card-body">
					<div class="tab-content">
							<!--begin::Tab-->
							<div class="tab-pane show active px-7" id="kt_user_edit_tab_1" role="tabpanel">

								<form class="form" id="m_data">

								<input type="hidden" name="m_num" value="<?=$var_m_num;?>" />

								<!--begin::Row-->
								<div class="row">
									<div class="col-xl-2"></div>
									<div class="col-xl-7 my-2">
										<!--begin::Row-->
										<div class="row">
											<label class="col-3"></label>
											<div class="col-9">
												<h6 class="text-dark font-weight-bold mb-10">Настройки магазина:</h6>
											</div>
										</div>
										<!--end::Row-->
										<!--begin::Group-->
										<div class="form-group row">
											<label class="col-form-label col-3 text-lg-right text-left">ID магазина</label>
											<div class="col-9">
												<input class="form-control form-control-lg form-control-solid" type="text" readonly="readonly" value="<?=$var_m_num_line;?>" />
												<b></b>
											</div>
										</div>
										<!--end::Group-->
										<!--begin::Group-->
										<div class="form-group row">
											<label class="col-form-label col-3 text-lg-right text-left">API ключ</label>
											<div class="col-9">
												<input class="form-control form-control-lg form-control-solid" type="text" readonly="readonly" value="<?=$var_m_api_key;?>" />
											</div>
										</div>
										<!--end::Group-->
										<!--begin::Group-->
										<div class="form-group row">
											<label class="col-form-label col-3 text-lg-right text-left">Название</label>
											<div class="col-9">
												<input class="form-control form-control-lg form-control-solid" type="text" name="m_title" value="<?=$var_m_title;?>" />
											</div>
										</div>
										<!--end::Group-->
										<!--begin::Group-->
										<div class="form-group row">
											<label class="col-form-label col-3 text-lg-right text-left">Домен</label>
											<div class="col-9">
												<input class="form-control form-control-lg form-control-solid" type="text" name="m_domain" value="<?=$var_m_domain;?>" <?=($var_can_edit ? 'readonly' : '');?> />
											</div>
										</div>
										<!--end::Group-->
										<!--begin::Group-->
										<div class="form-group row">
											<label class="col-form-label col-3 text-lg-right text-left">URL успешной оплаты</label>
											<div class="col-9">
												<input class="form-control form-control-lg form-control-solid" type="text" name="m_url_success" value="<?=$var_m_url_success;?>" />
											</div>
										</div>
										<!--end::Group-->
										<!--begin::Group-->
										<div class="form-group row">
											<label class="col-form-label col-3 text-lg-right text-left">URL неуспешной оплаты</label>
											<div class="col-9">
												<input class="form-control form-control-lg form-control-solid" type="text" name="m_url_error" value="<?=$var_m_url_error;?>" />
											</div>
										</div>
										<!--end::Group-->
										<!--begin::Group-->
										<div class="form-group row">
											<label class="col-form-label col-3 text-lg-right text-left">URL обработчика</label>
											<div class="col-9">
												<input class="form-control form-control-lg form-control-solid" type="text" name="m_url_callback" value="<?=$var_m_url_callback;?>" />
											</div>
										</div>
										<!--end::Group-->
										<!--begin::Group-->
										<div class="form-group row">
											<label class="col-form-label col-3 text-lg-right text-left">Кто оплачивает коммисию</label>
											<div class="col-9">
												<select class="form-control form-control-lg form-control-solid" name="m_comm_who">
													<option value="0" <?=(@$var_m_comm_who==0 ? 'selected' : '');?> >Продавец</option>
													<option value="1" <?=(@$var_m_comm_who==1 ? 'selected' : '');?> >Покупатель</option>
												</select>
											</div>
										</div>
										<!--end::Group-->
										<!--begin::Group-->
										<div class="form-group row mb-2">
											<label class="col-form-label col-3 text-lg-right text-left">Уникальные заказы</label>
											<div class="col-9">
												<span class="switch switch-icon">
										        <label>
										         <input type="checkbox" <?=(@$var_m_order_uniq==1 ? 'checked' : '');?> name="m_order_uniq" value="1" />
										         <span></span>
										        </label>
										       </span>
											</div>
										</div>
										<!--end::Group-->

										<div class="form-group row mb-2">
											<div class="col-3"></div>
											<div class="col-9">
												<br>
												<a href="#" onClick="return saveMerchant();" id="saveMerchantBtn" class="btn btn-light-primary font-weight-bold">Сохранить</a>
											</div>
										</div>

									</div>
								</div>
								<!--end::Row-->
								
								</form>

							</div>
							<!--end::Tab-->

						<? if (!$var_is_new) { ?>


							<!--begin::Tab CODE INSERT-->
							<div class="tab-pane px-7" id="kt_user_edit_tab_2" role="tabpanel">
								<!--begin::Body-->
								<div class="card-body">
									<!--begin::Row-->
									<div class="row">
										<div class="col-xl-2"></div>
										<div class="col-xl-7">
											<!--begin::Row-->
											<div class="row">
												<label class="col-2"></label>
												<div class="col-11">
													<h6 class="text-dark font-weight-bold mb-10">Формирование оплаты заказа:</h6>
												</div>
											</div>
											<!--end::Row-->
										
											<!--begin::Row-->
											<div class="row mb-5">
												<label class="col-2"></label>
												<div class="col-11">
													<textarea class="form-control" style="height:600px">
&lt;?

// параметры запроса
$m_shop = '<?=$var_m_num;?>';	// ID магазина
$m_orderid = 'order_1'; // номер заказа
$m_amount = '9.99'; // сумма заказа, в $
$m_desc = 'Test'; // Название товара или описание
$m_api_key = '<?=$var_m_api_key;?>'; // API ключ

$arHash = array(
	$m_shop,
	$m_orderid,
	$m_amount,
	$m_desc,
	$m_api_key,
);
$m_sign = strtoupper(sha1(implode(':', $arHash))); // генерируем подпись

?&gt;

<form method="post" action="<?=$var_site_url;?>/ru/payment/neworder">
	<input type="hidden" name="m_shop" value="&lt;?=$m_shop?&gt;">
	<input type="hidden" name="m_orderid" value="&lt;?=$m_orderid?&gt;">
	<input type="hidden" name="m_amount" value="&lt;?=$m_amount?&gt;">
	<input type="hidden" name="m_desc" value="&lt;?=$m_desc?&gt;">
	<input type="hidden" name="m_sign" value="&lt;?=$m_sign?&gt;">
	<input type="submit" value="send" />
</form>
													</textarea>
													
												</div>
											</div>
											<!--end::Row-->

											<br><br>

											<!--begin::Row-->
											<div class="row">
												<label class="col-2"></label>
												<div class="col-11">
													<h6 class="text-dark font-weight-bold mb-10">Пример обработчика:</h6>
												</div>
											</div>
											<!--end::Row-->
										
											<!--begin::Row-->
											<div class="row mb-5">
												<label class="col-2"></label>
												<div class="col-11">
													<textarea class="form-control" style="height:600px">
&lt;?

// ваши данные
$m_shop = '<?=$var_m_num;?>';	// ID магазина
$m_api_key = '<?=$var_m_api_key;?>'; // API ключ

// все возможные статусы оплаты
define('PAY_SUCCESS', 0);			// оплата прошла
define('PAY_REJECT',  1);			// оплата отклонена

// все возможные коды ответов
define('ANSWER_OK', 0);					// запрос успешно обработан
define('ANSWER_ERR_SIGN', 1);			// неверная подпись
define('ANSWER_ERR_SHOP', 2);			// неверный магазин
define('ANSWER_ERR_ORDER_WRONG', 3);	// неверный заказ
define('ANSWER_ERR_ORDER_STATUS', 4);	// заказ уже был отменен или подтвержден

// получает параметры запроса
$r_shop = $_REQUEST['r_shop']; 		// ID магазина
$r_orderid = $_REQUEST['r_orderid']; 	// ID заказа
$r_status = $_REQUEST['r_status'];	// статус оплаты
$r_sign = $_REQUEST['r_sign'];		// подпись

$return = ['request'=>true,'answer'=>-1];

$arHash = array(
	$r_shop,
	$r_orderid,
	$r_status,
	$m_api_key,
);
$m_sign = strtoupper(sha1(implode(':', $arHash))); // генерируем подпись

// сравниваем подпись
if ($r_sign!=$m_sign)
	{
	$return['answer'] = ANSWER_ERR_SIGN;
	}
// сравниваем магазин
else if ($r_shop!=$m_shop)
	{
	$return['answer'] = ANSWER_ERR_SHOP;
	}
else
	{
	// для теста
	if ($r_orderid=='test') // всё ок
		{
		$return['answer'] = ANSWER_OK;
		}
	else if ($r_orderid=='fail') // заказ уже был обработан
		{
		$return['answer'] = ANSWER_ERR_ORDER_STATUS;
		}
	else // любой другой заказ
		{
		$return['answer'] = ANSWER_ERR_ORDER_WRONG;
		}
	}

header('Content-type: application/json');
die(json_encode($return));

?&gt;
													</textarea>
													
													<br>

													<p>
														Обработчик ОБЯЗАТЕЛЬНО должен возвращать корректный ответ
													</p>

												</div>
											</div>
											<!--end::Row-->
										</div>
									</div>
									<!--end::Row-->
								</div>
								<!--end::Body-->
							</div>
							<!--end::Tab-->



							<!--begin::Tab CHECK-->
							<div class="tab-pane px-7" id="kt_user_edit_tab_3" role="tabpanel">
								<!--begin::Body-->
								<div class="card-body">
									<!--begin::Row-->
									<div class="row">
										<div class="col-xl-2"></div>
										<div class="col-xl-7">
											<!--begin::Row-->
											<div class="row">
												<label class="col-3"></label>
												<div class="col-9">
													<h6 class="text-dark font-weight-bold mb-10">Проверка сайта:</h6>
												</div>
											</div>
											<!--end::Row-->
										<? if ($var_is_confirm) { ?>
											<!--begin::Row-->
											<div class="row mb-5">
												<label class="col-3"></label>
												<div class="col-9">
													<div class="alert alert-custom alert-light-success fade show py-4" role="alert">
														<div class="alert-icon">
															<i class="flaticon-warning"></i>
														</div>
														<div class="alert-text font-weight-bold">Ваш магазин был проверен</div>
													</div>
												</div>
											</div>
											<!--end::Row-->
										<? } else { ?>
											<!--begin::Row-->
											<div class="row mb-5">
												<label class="col-3"></label>
												<div class="col-9">
													<div class="alert alert-custom alert-light-danger fade show py-4" role="alert">
														<div class="alert-icon">
															<i class="flaticon-warning"></i>
														</div>
														<div class="alert-text font-weight-bold">
															Для проверки сайта необходимо настроить обработчик на Вашем сайте<br>
															Обработчит должен корректно отвечать на все наши запросы<br><br>
															Для проверки должен обрабатывать заказ с номером `test` как успешно обработаный, а также заказ с номером `fail` как ошибочный.<br>
															Приммер настройки смотрите в разделе `Подключение на сайте`.
														</div>
													</div>

													<div id="errors_block"></div>
												</div>
											</div>
											<!--end::Row-->
											<!--begin::Row-->
											<div class="row mb-5">
												<label class="col-3"></label>
												<div class="col-9">
													<button type="button" onClick="return checkMerchant('<?=$var_m_num;?>');" id="checkMerchantBtn" class="btn btn-light-primary font-weight-bold">Проверить</button>
												</div>
											</div>
											<!--end::Row-->
										<? } ?>
											

											<? if ($var_is_confirm) { ?>

												<!--begin::Row-->
												<div class="row">
													<label class="col-3"></label>
													<div class="col-9">
														<h6 class="text-dark font-weight-bold mb-10">Модерация сайта:</h6>
													</div>
												</div>
												<!--end::Row-->
											<? if ($var_m_is_moder==1) { ?>
												<!--begin::Row-->
												<div class="row mb-5">
													<label class="col-3"></label>
													<div class="col-9">
														<div class="alert alert-custom alert-light-success fade show py-4" role="alert">
															<div class="alert-icon">
																<i class="flaticon-warning"></i>
															</div>
															<div class="alert-text font-weight-bold">Ваш магазин успешно прошел модерацию</div>
														</div>
													</div>
												</div>
												<!--end::Row-->
											<? } else if ($var_m_is_moder==2) { ?>
												<!--begin::Row-->
												<div class="row mb-5">
													<label class="col-3"></label>
													<div class="col-9">
														<div class="alert alert-custom alert-light-info fade show py-4" role="alert">
															<div class="alert-icon">
																<i class="flaticon-warning"></i>
															</div>
															<div class="alert-text font-weight-bold">Магазин находится на проверке у модератора.<br>
																									Запрос отправлен: <?=$var_m_moder_dt_line;?></div>
														</div>
													</div>
												</div>
												<!--end::Row-->
											<? } else { ?>
												<!--begin::Row-->
												<div class="row mb-5">
													<label class="col-3"></label>
													<div class="col-9">
														<div class="alert alert-custom alert-light-danger fade show py-4" role="alert">
															<div class="alert-icon">
																<i class="flaticon-warning"></i>
															</div>
															<div class="alert-text font-weight-bold">
																Прежде чем отправлять магазин на модерацию, убедитесь, что Ваш веб-сайт полностью закончен и работоспособен. Проверка сайта занимает 1-3 дня. Если у Вас возникли какие-либо вопросы, пожалуйста, напишите в поддержку выбрав соответствующий раздел.
															</div>
														</div>
													</div>
												</div>
												<!--end::Row-->
												<!--begin::Row-->
												<div class="row mb-5">
													<label class="col-3"></label>
													<div class="col-9">
														<button type="button" onClick="return moderMerchant('<?=$var_m_num;?>');" id="moderMerchantBtn" class="btn btn-light-primary font-weight-bold">Отправить на модерацию</button>
													</div>
												</div>
												<!--end::Row-->
											<? } ?>

										<? } ?>



										</div>
									</div>
									<!--end::Row-->
								</div>
								<!--end::Body-->
							</div>
							<!--end::Tab-->




							<!--begin::Tab MONEY-->
							<div class="tab-pane px-7" id="kt_user_edit_tab_4" role="tabpanel">
								<!--begin::Body-->
								<div class="card-body">
									<!--begin::Row-->
									<div class="row">
										<div class="col-xl-2">
										</div>
										<div class="col-xl-8">
											<!--begin::Row-->
											<div class="row">
												<div class="col-12">
													<h6 class="text-dark font-weight-bold mb-10">Принимаемые валюты на сайте:</h6>
												</div>
											</div>
											<!--end::Row-->

											<!--begin::Row-->
											<div class="row">

												<form id="merch_pss" style="width:100%">

													<input type="hidden" name="id" value="<?=$var_m_num;?>" />

													<table class="table" id="kt_datatable" style="width:100%">
														<thead>
															<tr>
																<th style="width:50px">&nbsp;</th>
																<th>Название</th>
																<th>Информация</th>
																<th>Курс к $</th>
															</tr>
														</thead>
														<tbody>

												<? if (count($var_all_pss)>0) { foreach ($var_all_pss as $one_ps) { ?>
													
															<tr data-row="0" class="datatable-row" style="left: 0px;">
																<td class="datatable-cell">
																	<span class="switch switch-icon switch-sm">
																    <label>
																     <input type="checkbox" name="pss[]" value="<?=$one_ps['paysys_id'];?>" <?=array_search($one_ps['paysys_id'],$var_m_pss)!==false ? 'checked' : '';?> />
																     <span></span>
																    </label>
																   </span>
																</td>
																<td class="datatable-cell">
																	<img src="<?=$one_ps['bal_icon'];?>" style="width:20px;" /> <?=$one_ps['paysys_title'];?>
																</td>
																<td class="datatable-cell"><?=$one_ps['paysys_info'];?></td>
																<td class="datatable-cell"><?=$one_ps['bal_rate'];?></td>
															</tr>

												<? } } ?>

														</tbody>
													</table>

												</form>

											</div>

											<div class="row">
												<div class="col-12">
													<button type="button" onClick="return pssMerchant('<?=$var_m_num;?>');" id="pssMerchantBtn" class="btn btn-light-primary font-weight-bold">Сохранить валюты</button>
												</div>
											</div>

											</div>
											<!--end::Row-->

										</div>
									</div>
									<!--end::Row-->
								</div>
								<!--end::Body-->
								<!--begin::Footer-->
								<div class="card-footer pb-0">
								</div>
								<!--end::Footer-->
							</div>
							<!--end::Tab-->




					<? } ?>

							


						</div>
				</div>
				<!--begin::Card body-->
			</div>
			<!--end::Card-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Entry-->
</div>
<!--end::Content-->
	