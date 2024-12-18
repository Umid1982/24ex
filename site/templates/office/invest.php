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
					<h2 class="text-white font-weight-bold my-2 mr-5">Инвестиции</h2>
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
						<a href="/<?=_LANG_;?>/office/invest/" class="text-white text-hover-white opacity-75 hover-opacity-100">Инвестиции</a>
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

                    <!--begin::Search Form-->
                    <div class="mb-7">
                        <div class="row align-items-center">
                            <div class="col-lg-9 col-xl-3">
                            	<h3>Новый депозит</h3>
                            </div>
                            <div class="col-lg-9 col-xl-4">
								<div class="d-flex align-items-center">
                                    <select class="form-control" id="newInvestPlanId">
                                        <option value="0">- выберите инвестиционный план -</option>
										<? if (count($var_invest_plans)>0) foreach ($var_invest_plans as $one_plan) { ?>
											<option value="<?=$one_plan['plan_id'];?>"><?=$one_plan['plan_name'];?> [<?=$one_plan['plan_min']?>-<?=$one_plan['plan_max']?> <?=$one_plan['bal_name'];?>], <?=$one_plan['plan_proc']?>% за <?=$one_plan['plan_time']?>ч. на <?=$one_plan['plan_max_time']?>ч.</option>
										<? } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-9 col-xl-3">
								<div class="d-flex align-items-center">
                                    <input type="text" id="newInvestVal" class="form-control" placeholder="Сумма депозита" />
                                </div>
                            </div>
                            <div class="col-lg-9 col-xl-2">
								<div class="d-flex align-items-center">
                                    <button type="button" class="btn btn-block btn-info" onClick="newInvest()">Открыть депозит</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Search Form-->
                    <!--begin: Datatable-->
                    <div class="datatable datatable-bordered datatable-head-custom" id="kt_datatable">
                    </div>
                    <!--end: Datatable-->					

				</div>
			</div>
			<!--end::Card-->
			
		</div>
		<!--end::Container-->
	</div>
	<!--end::Entry-->
</div>
<!--end::Content-->
