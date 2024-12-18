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
					<h2 class="text-white font-weight-bold my-2 mr-5">Личный кабинет</h2>
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
			<div class="card card-custom gutter-b">
				<div class="card-body">
					<!--begin::Details-->
					<div class="d-flex mb-9">
						<!--begin: Pic-->
						<div class="flex-shrink-0 mr-7 mt-lg-0 mt-3">
							<div class="symbol symbol-50 symbol-lg-120">
								<div class="symbol-label" style="background-image:url('<?=$var_user_avatar;?>')"></div>
							</div>
							<div class="symbol symbol-50 symbol-lg-120 symbol-primary d-none">
								<span class="font-size-h3 symbol-label font-weight-boldest">JM</span>
							</div>
						</div>
						<!--end::Pic-->
						<!--begin::Info-->
						<div class="flex-grow-1">
							<!--begin::Title-->
							<div class="d-flex justify-content-between flex-wrap mt-1">
								<div class="d-flex mr-3">
									<a href="/<?=_LANG_?>/office/cabinet/" class="text-dark-75 text-hover-primary font-size-h5 font-weight-bold mr-3"><?=$var_user_name_line;?></a>
								</div>
							</div>
							<!--end::Title-->
							<!--begin::Content-->
							<div class="d-flex flex-wrap justify-content-between mt-1">
								<div class="d-flex flex-column flex-grow-1 pr-8">
									<div class="d-flex flex-wrap mb-4">
										<a href="javascript:;" class="text-dark-50 text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
										<i class="flaticon2-new-email mr-2 font-size-lg"></i><?=$var_user_email;?></a>
									</div>
									<span class="font-weight-bold text-dark-50">
										Моя реферальная ссылка - <a href="<?=$var_ref_link;?>" target="_blank"><?=$var_ref_link;?></a>
									</span>
									<span class="font-weight-bold text-dark-50">
										Привлекайте друзей для получения % от их вложений
									</span>
								</div>
							</div>
							<!--end::Content-->
						</div>
						<!--end::Info-->
					</div>
					<!--end::Details-->
					<div class="separator separator-solid"></div>
					<!--begin::Items-->
					<div class="d-flex align-items-center flex-wrap mt-8">
						<!--begin::Item-->
						<div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
							<span class="mr-4">
								<i class="flaticon-piggy-bank display-4 text-muted font-weight-bold"></i>
							</span>
							<div class="d-flex flex-column text-dark-75">
								<span class="font-weight-bolder font-size-sm">Общий баланс</span>
								<a href="/<?=_LANG_?>/office/bals/" style="color:inherit">
									<span class="font-weight-bolder font-size-h5">
									<span class="text-dark-50 font-weight-bold">$</span><?=$var_user_bals_sum_usd;?></span>
								</a>
							</div>
						</div>
						<!--end::Item-->
						<!--begin::Item-->
						<div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
							<span class="mr-4">
								<i class="flaticon-confetti display-4 text-muted font-weight-bold"></i>
							</span>
							<div class="d-flex flex-column text-dark-75">
								<span class="font-weight-bolder font-size-sm">На депозитах</span>
								<a href="/<?=_LANG_?>/office/invest/" style="color:inherit">
									<span class="font-weight-bolder font-size-h5">
									<span class="text-dark-50 font-weight-bold">$</span><?=$var_user_invest_sum_usd;?></span>
								</a>
							</div>
						</div>
						<!--end::Item-->
						<!--begin::Item-->
						<div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
							<span class="mr-4">
								<i class="flaticon-pie-chart display-4 text-muted font-weight-bold"></i>
							</span>
							<div class="d-flex flex-column text-dark-75">
								<span class="font-weight-bolder font-size-sm">Ожидает выплаты</span>
								<a href="/<?=_LANG_?>/office/paystat/" style="color:inherit">
									<span class="font-weight-bolder font-size-h5">
									<span class="text-dark-50 font-weight-bold">$</span><?=$var_user_payout_sum_usd;?></span>
								</a>
							</div>
						</div>
						<!--end::Item-->
					</div>
					<!--begin::Items-->
				</div>
			</div>
			<!--end::Card-->
			
		</div>
		<!--end::Container-->
	</div>
	<!--end::Entry-->
</div>
<!--end::Content-->