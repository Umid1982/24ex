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
					<h2 class="text-white font-weight-bold my-2 mr-5">Мои кошельки</h2>
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
						<a href="/<?=_LANG_;?>/office/bals/" class="text-white text-hover-white opacity-75 hover-opacity-100">Мои балансы</a>
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
                            <div class="col-lg-9 col-xl-4">
                                <div class="input-icon">
                                    <input type="text" class="form-control" placeholder="Поиск..." id="kt_datatable_search_query" value="" />
                                    <span>
                                        <i class="flaticon2-search-1 text-muted"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-9 col-xl-4">
                            	Универсальный номер кошелька
                            	<h3 style="color:green"><?=$var_user_bal_num;?>&nbsp;&nbsp;
                            	<a href="javascript:copyToClipboard('<?=$var_user_bal_num;?>')"><i class="flaticon2-copy"></i></a></h3>
                            </div>
                            <div class="col-lg-9 col-xl-4">
								<div class="d-flex align-items-center">
                                    <select class="form-control" id="balForAdd">
                                        <option value="0">- выберите баланс для добавления -</option>
										<? if (count($var_add_bals)>0) foreach ($var_add_bals as $one_bal) { ?>
											<option value="<?=$one_bal['bal_id'];?>"><?=$one_bal['bal_title'];?> [<?=$one_bal['bal_name'];?>]</option>
										<? } ?>
                                    </select>
                                	<button class="btn btn-success" onClick="addBal($('#balForAdd').val())">Добавить</button>
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