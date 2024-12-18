<script type="text/javascript">
	var chart_months = ['<?=implode("','",$var_chart_months);?>'];

	var chart_count_total = [<?=implode(",",$var_chart_count_total);?>];
	var chart_count_done = [<?=implode(",",$var_chart_count_done);?>];
	var chart_count_cancel = [<?=implode(",",$var_chart_count_cancel);?>];
	var chart_count_wait = [<?=implode(",",$var_chart_count_wait);?>];
	
	var chart_sum_total = [<?=implode(",",$var_chart_sum_total);?>];
	var chart_sum_done = [<?=implode(",",$var_chart_sum_done);?>];
	var chart_sum_cancel = [<?=implode(",",$var_chart_sum_cancel);?>];
	var chart_sum_wait = [<?=implode(",",$var_chart_sum_wait);?>];
</script>

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
					<h2 class="text-white font-weight-bold my-2 mr-5">Магазины</h2>
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

					<div class="row">

						<div class="col-lg-6">
							<!--begin::Card-->
							<div class="card card-custom gutter-b">
								<!--begin::Header-->
								<div class="card-header h-auto">
									<!--begin::Title-->
									<div class="card-title py-5">
										<h3 class="card-label">Количество заказов</h3>
									</div>
									<!--end::Title-->
								</div>
								<!--end::Header-->
								<div class="card-body">
									<!--begin::Chart-->
									<div id="chart_count"></div>
									<!--end::Chart-->
								</div>
							</div>
							<!--end::Card-->
						</div>

						<div class="col-lg-6">
							<!--begin::Card-->
							<div class="card card-custom gutter-b">
								<!--begin::Header-->
								<div class="card-header h-auto">
									<!--begin::Title-->
									<div class="card-title py-5">
										<h3 class="card-label">Суммы заказов</h3>
									</div>
									<!--end::Title-->
								</div>
								<!--end::Header-->
								<div class="card-body">
									<!--begin::Chart-->
									<div id="chart_sum"></div>
									<!--end::Chart-->
								</div>
							</div>
							<!--end::Card-->
						</div>

						<div class="col-lg-12">

							<table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Месяц</th>
                                        <th scope="col" colspan="2">Завершенные</th>
                                        <th scope="col" colspan="2">Отклоненные</th>
                                        <th scope="col" colspan="2">В ожидании</th>
                                        <th scope="col" colspan="2">Всего</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <? foreach ($var_table_stat as $dt=>$data) { ?>
                                	<tr>
                                		<th><?=$dt;?></th>

                                		<td style="color:green"><?=$data['done']['cc'];?></td>
                                		<td style="color:green"><?=cutZeros($data['done']['sum']);?>$</td>

                                		<td style="color:red"><?=$data['cancel']['cc'];?></td>
                                		<td style="color:red"><?=cutZeros($data['cancel']['sum']);?>$</td>

                                		<td style="color:#9a9e3d"><?=$data['wait']['cc'];?></td>
                                		<td style="color:#9a9e3d"><?=cutZeros($data['wait']['sum']);?>$</td>

                                		<td style="color:blue"><?=$data['total']['cc'];?></td>
                                		<td style="color:blue"><?=cutZeros($data['total']['sum']);?>$</td>
                                	</tr>
                            	<? } ?>
                                </tbody>
                            </table>

						</div>


					</div>

				</div>
			</div>
			<!--end::Card-->
			
		</div>
		<!--end::Container-->
	</div>
	<!--end::Entry-->
</div>
<!--end::Content-->
