
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
							</div>
							
						</div>
						<!--end::Aside Top-->
						<!--begin::Signin-->
						
						<div class="login-form">
							<!--begin::Form-->
							<form class="form px-10" novalidate="novalidate" id="step1_form">
								<input type="hidden" name="pay_id" value="<?=$var_pay_id;?>" />
								<!--begin: Wizard Step 1-->
								<div data-wizard-type="step-content" data-wizard-state="current">
									
									  <div class="pt-lg-0 pt-5 pb-15">
									  	<h3 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg"><i class="flaticon2-cancel icon-2x text-danger"></i> Ошибка</h3>
											<div class="text-muted font-weight-bold font-size-h4">
										<? if ($var_check == CHECK_NEWORDER_FIELDS) { ?>
											Не переданы все необходимые поля
										<? } else if ($var_check == CHECK_NEWORDER_NOSHOP) { ?>
											Магазин не найден
										<? } else if ($var_check == CHECK_NEWORDER_SHOPLOCK) { ?>
											Магазин заблокирован
										<? } else if ($var_check == CHECK_NEWORDER_NOPSS) { ?>
											Нет доступных способов оплаты
										<? } else if ($var_check == CHECK_NEWORDER_SIGN) { ?>
											Неверная подпись безопасности
										<? } else if ($var_check == CHECK_NEWORDER_UNIQID) { ?>
											Данный заказ уже был обработан
										<? } else if ($var_check == CHECK_NEWORDER_ERROR) { ?>
											Неизвестная ошибка, обратитесь в техническую поддержку
										<? } ?>
										</div>
									</div>

									<div class="text-left">
									<button type="button" class="btn btn-primary font-weight-bolder font-size-h6 pr-4 py-4 my-3 float-left" onClick="history.back()">
										<span class="svg-icon svg-icon-md ml-2">
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
										    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										        <polygon points="0 0 24 0 24 24 0 24"/>
										        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-12.000000, -12.000000) " x="11" y="5" width="2" height="14" rx="1"/>
										        <path d="M3.7071045,15.7071045 C3.3165802,16.0976288 2.68341522,16.0976288 2.29289093,15.7071045 C1.90236664,15.3165802 1.90236664,14.6834152 2.29289093,14.2928909 L8.29289093,8.29289093 C8.67146987,7.914312 9.28105631,7.90106637 9.67572234,8.26284357 L15.6757223,13.7628436 C16.0828413,14.136036 16.1103443,14.7686034 15.7371519,15.1757223 C15.3639594,15.5828413 14.7313921,15.6103443 14.3242731,15.2371519 L9.03007346,10.3841355 L3.7071045,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(9.000001, 11.999997) scale(-1, -1) rotate(90.000000) translate(-9.000001, -11.999997) "/>
										    </g>
										</svg><!--end::Svg Icon-->
									</span>Назад</button>	
									</div>

									  
								</div>
								<!--end: Wizard Step 1-->
								
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
						<!--begin::Aside title-->
						<h3 class="pt-lg-40 pl-lg-20 pb-lg-0 pl-10 py-20 m-0 d-flex justify-content-lg-start font-weight-boldest display5 display1-lg text-white">We Got
						<br />A Surprise
						<br />For You</h3>
						<!--end::Aside title-->
					</div>
				</div>
				<!--end::Aside-->
			</div>
			<!--end::Login-->
		</div>
		<!--end::Main-->
