
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <!--begin::Dashboard-->
            <!--begin::Row-->
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">
                    <!--begin::Charts Widget 5-->
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b">
                        <!--begin::Card header-->
                        <div class="card-header h-auto border-0">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">
                                        <a href="/<?=$var_adm_path;?>/?page=merchants">Все магазины</a> - Магазин ID:<?=$var_m_num;?>
                                    </span>
                                </h3>
                            </div>
                        </div>
                        <!--end:: Card header-->
                        <!--begin::Card body-->
                        <div class="card-body">
                         <form id="planone_form">
                            <input type="hidden" name="plan_id" value="<?=$var_plan_id;?>" />

                            <div class="form-row">

                              <div class="form-group col-md-6">
                                <label>Название</label>
                                <input type="text" readonly="readonly" class="form-control" value="<?=$var_m_title;?>" />
                              </div>

                              <div class="form-group col-md-3">
                                <label>Оплачивает заказ</label>
                                <input type="text" name="name" readonly="readonly" class="form-control" value="<?=($var_m_comm_who==0 ? 'Продавец' : 'Покупатель');?>" />
                              </div>

                              <div class="form-group col-md-3">
                                <label>Уникальные номера заказов</label>
                                <input type="text" name="name" readonly="readonly" class="form-control" value="<?=($var_m_order_uniq==0 ? 'Нет' : 'Да');?>" />
                              </div>

                              <div class="form-group col-md-4">
                                <label>ID</label>
                                <input type="text" readonly="readonly" class="form-control" value="<?=$var_m_num;?>" />
                              </div>

                              <div class="form-group col-md-4">
                                <label>API ключ</label>
                                <input type="text" readonly="readonly" class="form-control" value="<?=$var_m_api_key;?>" />
                              </div>

                              <div class="form-group col-md-4">
                                <label>Домен</label>
                                <input type="text" readonly="readonly" class="form-control" value="<?=$var_m_domain;?>" />
                              </div>

                              <div class="form-group col-md-4">
                                <label>URL успешной оплаты</label>
                                <input type="text" readonly="readonly" class="form-control" value="<?=$var_m_url_success;?>" />
                              </div>

                              <div class="form-group col-md-4">
                                <label>URL неуспешной оплаты</label>
                                <input type="text" readonly="readonly" class="form-control" value="<?=$var_m_url_error;?>" />
                              </div>

                              <div class="form-group col-md-4">
                                <label>URL обработчика оплаты</label>
                                <input type="text" readonly="readonly" class="form-control" value="<?=$var_m_url_callback;?>" />
                              </div>

                              <div class="form-group col-md-12">
                                <label>Подключенные платежные способы</label>
                                <? if (count($var_m_pss)>0) { foreach ($var_m_pss as $one_ps) { ?>
                                	<input type="text" readonly="readonly" class="form-control" value="<?=$one_ps['paysys_title'] . ' ( $ '.$one_ps['bal_rate'].' )';?>" />
                                <? } } else { ?>
                                	<p><small>- ничего не выбрано пользователем -</small></p>
                                <? } ?>
                              </div>

                              <div class="col-md-6">

                            <? if ($var_is_confirm) { ?>
                              	<div class="alert alert-custom alert-light-success fade show py-4" role="alert">
									<div class="alert-icon">
										<i class="flaticon-warning"></i>
									</div>
									<div class="alert-text font-weight-bold">Магазин был успешно проверен</div>
								</div>
                          	<? } else { ?>
                              	<div class="alert alert-custom alert-light-warning fade show py-4" role="alert">
									<div class="alert-icon">
										<i class="flaticon-warning"></i>
									</div>
									<div class="alert-text font-weight-bold">Магазин не проверен</div>
								</div>
                          	<? } ?>

                          	</div>
                          	<div class="col-md-6">

                            <? if ($var_m_is_moder==0) { ?>
                              	<div class="alert alert-custom alert-light-danger fade show py-4" role="alert">
									<div class="alert-icon">
										<i class="flaticon-warning"></i>
									</div>
									<div class="alert-text font-weight-bold">Магазин отключен</div>
								</div>
                          	<? } else if ($var_m_is_moder==1) { ?>
                              	<div class="alert alert-custom alert-light-success fade show py-4" role="alert">
									<div class="alert-icon">
										<i class="flaticon-warning"></i>
									</div>
									<div class="alert-text font-weight-bold">
										Сайт прошел модерацию, <?=$var_m_moder_dt_line;?><br>
										<button type="button" class="btn btn-light-danger btn-sm" onClick="moderMerchant('<?=$var_m_num;?>',0)">Отключить магазин</button>
										</div>
								</div>
                          	<? } else if ($var_m_is_moder==2) { ?>
                              	<div class="alert alert-custom alert-light-warning fade show py-4" role="alert">
									<div class="alert-icon">
										<i class="flaticon-warning"></i>
									</div>
									<div class="alert-text font-weight-bold">
										Запрос на модерацию, <?=$var_m_moder_dt_line;?><br>
										<button type="button" class="btn btn-light-success btn-sm" onClick="moderMerchant('<?=$var_m_num;?>',1)">Включить магазин</button>
										</div>
								</div>
                          	<? } ?>
                              
                              </div>

                              <div class="col-md-12">
                              	<hr>
                              </div>

                              <div class="form-group col-md-6">
                                <label>Комиссия за платежи, %</label>
                                <input type="text" name="m_prc" class="form-control" value="<?=$var_m_prc;?>" />
                              </div>

                              <div class="form-group col-md-6">
                                <label>&nbsp;</label>
                                <button class="btn btn-success form-control" type="button" onClick="saveMerchantPrc('<?=$var_m_num;?>')">Изменить комиссию</button>
                              </div>

                            </div>

                            <!--button type="button" class="btn btn-danger btn-lg" id="delPlan">Удалить</button>
                            <button type="button" class="btn btn-primary btn-lg" id="savePlan">Сохранить</button-->
                          </form>
                         </div>
                        <!--end:: Card body-->                        
                    </div>
                    <!--end:: Card-->
                    <!--end:: Charts Widget 5-->
                </div>
            </div>
            <!--end::Row-->
            
            <!--end::Dashboard-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>
<!--end::Content-->                          

<? /*
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
									</div>
								</div>
								<!--end::Row-->
								<!--begin::Footer-->
								<div class="card-footer pb-0">
									<div class="row">
										<div class="col-xl-2"></div>
										<div class="col-xl-7">
											<div class="row">
												<div class="col-3"></div>
												<div class="col-9">
													<a href="#" onClick="return saveMerchant();" id="saveMerchantBtn" class="btn btn-light-primary font-weight-bold">Сохранить</a>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!--end::Footer-->
								</form>

							</div>
							<!--end::Tab-->

						<? if (!$var_is_new) { ?>

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
															Для того, чтобы Ваш сайт прошел проверку создайте в корне сайта файл с именем <code>merch_check.txt</code> и содержанием <code><?=$var_check_code;?></code><br><br>
															ПОСЛЕ ПРОВЕРКИ ВЫ НЕ СМОЖЕТЕ ИЗМЕНИТЬ ДОМЕН!
														</div>
													</div>
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
															<div class="alert-text font-weight-bold">Магазин находится на проверке у модератора</div>
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
								<!--begin::Footer-->
								<div class="card-footer pb-0">
								</div>
								<!--end::Footer-->
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
	*/