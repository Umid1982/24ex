<script>
var change_json = JSON.parse('<?=$var_changes_json;?>');
</script>



		<!--begin::Main-->
		<div class="d-flex flex-column flex-root">
		
			
			
			<div class="login login-4 wizard d-flex flex-column flex-lg-row flex-column-fluid wizard" id="kt_login">
				<!--begin::Content-->
				<div class="login-container d-flex flex-center flex-row flex-row-fluid order-2 order-lg-1 flex-row-fluid bg-white py-lg-0 pb-lg-0 pt-15 pb-12">
					<!--begin::Container-->
					<div class="login-content login-content-signup d-flex flex-column">
						<!--begin::Aside Top-->
						<div class="d-flex flex-column-auto flex-column px-10">
							<!--begin::Aside header-->
							<a href="javascript:return false;" class="login-logo pb-lg-4 pb-10">
								<img src="assets/media/logos/logo-4.png" class="max-h-70px" alt="" />
							</a>
							
							<div class="wizard-nav pt-5 pt-lg-15 pb-10">
								<!--begin::Wizard Steps-->
								<div class="wizard-steps d-flex flex-column flex-sm-row">
									<!--begin::Wizard Step 1 Nav-->
									<div class="wizard-step flex-grow-1 flex-basis-0" data-wizard-type="step" <?=($var_step==1 ? 'data-wizard-state="current"' : '');?> >
										<div class="wizard-wrapper pr-7">
											<div class="wizard-icon">
												<i class="wizard-check ki ki-check"></i>
												<span class="wizard-number">1</span>
											</div>
											<div class="wizard-label">
												<h3 class="wizard-title">Обмен</h3>
												<div class="wizard-desc">Выберите пару</b></div>
											</div>
											<span class="svg-icon pl-6">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<polygon points="0 0 24 0 24 24 0 24" />
														<rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000)" x="7.5" y="7.5" width="2" height="9" rx="1" />
														<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
													</g>
												</svg>
											</span>
										</div>
									</div>
									<!--end::Wizard Step 1 Nav-->
									<!--begin::Wizard Step 2 Nav-->
									<div class="wizard-step flex-grow-1 flex-basis-0" data-wizard-type="step" <?=($var_step==2 ? 'data-wizard-state="current"' : '');?> >
										<div class="wizard-wrapper pr-7">
											<div class="wizard-icon">
												<i class="wizard-check ki ki-check"></i>
												<span class="wizard-number">2</span>
											</div>
											<div class="wizard-label">
												<h3 class="wizard-title">Подтверждение</h3>
												<div class="wizard-desc">Введенных данных</div>
											</div>
											<span class="svg-icon pl-6">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<polygon points="0 0 24 0 24 24 0 24" />
														<rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000)" x="7.5" y="7.5" width="2" height="9" rx="1" />
														<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
													</g>
												</svg>
											</span>
										</div>
									</div>
									<!--end::Wizard Step 2 Nav-->
									<!--begin::Wizard Step 3 Nav-->
									<div class="wizard-step flex-grow-1 flex-basis-0" data-wizard-type="step" <?=($var_step==3 ? 'data-wizard-state="current"' : '');?> >
										<div class="wizard-wrapper">
											<div class="wizard-icon">
												<i class="wizard-check ki ki-check"></i>
												<span class="wizard-number">3</span>
											</div>
											<div class="wizard-label">
												<h3 class="wizard-title">Статус</h3>
												<div class="wizard-desc">Текущей заявки</div>
											</div>
										</div>
									</div>
									<!--end::Wizard Step 3 Nav-->
								</div>
								<!--end::Wizard Steps-->
							</div>
							<!--end: Wizard Nav-->
						</div>
						<!--end::Aside Top-->
						<!--begin::Signin-->
						
						<div class="login-form">
							<!--begin::Form-->
								<!--begin: Wizard Step 1-->
								<div data-wizard-type="step-content" <?=($var_step==1 ? 'data-wizard-state="current"' : '');?> >
									

								  <div class="form-group m-0">
									   <div class="row">

											<div id="change_list_from" class="col-lg-6 change_list">
												<h4>Что меняем</h4>
											</div>

											<div id="change_list_to" class="col-lg-6 change_list">
												<h4>На что меняем</h4>
											</div>
										</div>
										<div class="row" id="props">

											<div class="col-lg-6 pt-5">

												<h2>Сумму меняем</h2>
												<p><input type="text" id="from_val" onKeyUp="calcVals('from')" value="0" /> <span id="from_name"></span></p>
												<p>
													Min: <span id="from_min"></span><br>
													Max: <span id="from_max"></span><br>
													Комиссия: <span id="from_com">0</span><br>
													У вас: <span id="from_ub_value">0</span>
												</p>

											</div>
											<div class="col-lg-6 pt-5">

												<h2>Сумму получаем</h2>
												<p><input type="text" id="to_val" onKeyUp="calcVals('to')" value="0" /> <span id="to_name"></span></p>
												<p>
													Min: <span id="to_min"></span><br>
													Max: <span id="to_max"></span><br>
													Резерв: <span id="to_ch_value">0</span><br>
													У вас: <span id="to_ub_value">0</span>
												</p>
											</div>

										<? if ($var_need_paypass) { ?>
											<div class="col-lg-12">
												<h2>Введите пллатежный пароль</h2>
												<p><input type="text" id="pay_pass" value="" style="width:300px"; /></p>
											</div>
										<? } else { ?>
											<input type="hidden" id="pay_pass" value="" />
										<? } ?>

									   </div>
								  </div>

									<div class="text-right">
									<button type="button" class="btn btn-primary font-weight-bolder font-size-h6 pl-8 pr-4 py-4 my-3" onClick="goChange()">Далее
										<span class="svg-icon svg-icon-md ml-2">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Right-2.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<polygon points="0 0 24 0 24 24 0 24" />
													<rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000)" x="7.5" y="7.5" width="2" height="9" rx="1" />
													<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
												</g>
											</svg>
											<!--end::Svg Icon-->
										</span></button>
									</div>
									  
								</div>
								<!--end: Wizard Step 1-->
								<!--begin: Wizard Step 2-->
								<div class="pb-5" data-wizard-type="step-content" <?=($var_step==2 ? 'data-wizard-state="current"' : '');?> >
									<!--begin::Title-->
									<div class="pt-lg-0 pt-5 ">
										<h3 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">
											Обмен <img src="<?=$var_from_bal_icon;?>" style="width:24px" alt=""> <?=$var_from_bal_title;?> [<?=$var_from_bal_name;?>] на 
													<img src="<?=$var_to_bal_icon;?>" style="width:24px" alt=""> <?=$var_to_bal_title;?> [<?=$var_to_bal_name;?>]
										</h3>
									</div>
									
									<div class="bg-white rounded">

										<form id="step2_form">

											<input type="hidden" name="pay_id" value="<?=$var_pay_id;?>" />
										
											<div class="card card-custom card-fit card-border p-10">
												<div class="card-body pt-2 text-center">

													<div class="d-flex align-items-center justify-content-between mb-2 h5">
														<span class="font-weight-bold mr-2">Будет списано:</span>
														<span class="text-hover-primary"><b><?=($var_ps_data_pay_ch_value+$var_pay_com);?></b> <?=$var_from_bal_name;?></span>
													</div>

													<br>

													<div class="d-flex align-items-center justify-content-between mb-2 h5">
														<span class="font-weight-bold mr-2">Будет зачислено:</span>
														<span class="text-hover-primary"><b><?=($var_pay_value);?></b> <?=$var_to_bal_name;?></span>
													</div>

												</div>
											</div>
										
									</div>

									<div>
									<button type="button" class="btn btn-primary font-weight-bolder font-size-h6 pr-4 py-4 my-3 float-left" onClick="cancelChange()">
										<span class="svg-icon svg-icon-md ml-2">
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
										    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										        <polygon points="0 0 24 0 24 24 0 24"/>
										        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-12.000000, -12.000000) " x="11" y="5" width="2" height="14" rx="1"/>
										        <path d="M3.7071045,15.7071045 C3.3165802,16.0976288 2.68341522,16.0976288 2.29289093,15.7071045 C1.90236664,15.3165802 1.90236664,14.6834152 2.29289093,14.2928909 L8.29289093,8.29289093 C8.67146987,7.914312 9.28105631,7.90106637 9.67572234,8.26284357 L15.6757223,13.7628436 C16.0828413,14.136036 16.1103443,14.7686034 15.7371519,15.1757223 C15.3639594,15.5828413 14.7313921,15.6103443 14.3242731,15.2371519 L9.03007346,10.3841355 L3.7071045,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(9.000001, 11.999997) scale(-1, -1) rotate(90.000000) translate(-9.000001, -11.999997) "/>
										    </g>
										</svg><!--end::Svg Icon-->
									</span>Отменить обмен</button>	

									<button type="button" class="btn btn-primary font-weight-bolder font-size-h6 pl-8 pr-4 py-4 my-3 float-right" onClick="confirmChange()">Подтвердить обмен 
										<span class="svg-icon svg-icon-md ml-2">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Right-2.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<polygon points="0 0 24 0 24 24 0 24" />
													<rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000)" x="7.5" y="7.5" width="2" height="9" rx="1" />
													<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
												</g>
											</svg>
											<!--end::Svg Icon-->
										</span></button>									
									</div>
									
									<!--end::Row-->
								</div>
								<!--end: Wizard Step 2-->
								<!--begin: Wizard Step 3-->
								<div class="pb-5" data-wizard-type="step-content" <?=($var_step==3 ? 'data-wizard-state="current"' : '');?> >
									<!--begin::Title-->
									<div class="pt-lg-0 pt-5 pb-15">
										<? if ($var_expired) { ?>
											<h3 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg"><i class="flaticon2-hourglass-1 icon-2x"></i> Время заявки истекло</h3>
											<div class="text-muted font-weight-bold font-size-h4">Вы не успели оплатить заявку в отведенное время, создайте новую или обратитесь в поддержку</div>
										<? } else if ($var_pay_status == PAY_STATUS_REJECT || $var_pay_status == PAY_STATUS_CANCEL ) { ?>
											<h3 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg"><i class="flaticon2-cancel icon-2x text-danger"></i> Заявка отменена</h3>
											<div class="text-muted font-weight-bold font-size-h4">Если не вы отменяли заявку - свяжитесь с поддержкой</div>
										<? } else if ($var_pay_status == PAY_STATUS_IN_WORK || $var_pay_status == PAY_STATUS_SEND_PROPS) { ?>
											<h3 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg"><i class="flaticon-like icon-2x text-success"></i> Успешно</h3>
											<div class="text-muted font-weight-bold font-size-h4">Заявка в работе! В ближайшее время средства поступят на Ваш счет</div>
										<? } else if ($var_pay_status == PAY_STATUS_DONE) { ?>
											<h3 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg"><i class="flaticon-like icon-2x text-success"></i> Успешно</h3>
											<div class="text-muted font-weight-bold font-size-h4">Заявка успешно обработана, средства зачислены на Ваш счет</div>											
										<? } ?>
									</div>
									
								</div>
								<!--end: Wizard Step 5-->
								
							</form>
							<!--end::Form-->
						</div>
						<!--end::Signin-->
					</div>
					<!--end::Container-->
				</div>
				<!--begin::Content-->
				<!--begin::Aside-->
				<div class="login-aside order-1 order-lg-2 bgi-no-repeat bgi-position-x-right">
					<div class="login-conteiner bgi-no-repeat bgi-position-x-right bgi-position-y-bottom" style="background-image: url(assets/media/svg/illustrations/login-visual-4.svg);">
						<h3 class="pt-lg-40 pl-lg-20 pb-lg-0 pl-10 py-20 m-0 d-flex justify-content-lg-start font-weight-boldest display5 display1-lg text-white">We Got
						<br />A Surprise
						<br />For You</h3>
					</div>
				</div>
				<!--end::Aside-->
			</div>
			<!--end::Login-->
		</div>
		<!--end::Main-->
