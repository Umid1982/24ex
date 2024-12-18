
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
                        <div class="card-header h-auto border-1">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">
                                        Ваучеры&nbsp;&nbsp;
                                        <button class="btn btn-sm btn-light-success font-weight-bolder py-2 px-5" onClick="newVShow();">Создать</button>
                                    </span>
                                </h3>
                            </div>
                        </div>
                        <!--end:: Card header-->
                        <!--begin::Card body-->
                        <div class="card-body">

                                <!--begin::Search Form-->
                                <div class="mb-7">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 my-2 my-md-0">
                                            <div class="d-flex align-items-center">
                                                <label class="mr-3 mb-0 d-none d-md-block">Пользователь: </label>
                                                <input type="hidden" id="kt_datatable_search_user" value="" />
                                                <input type="text" class="form-control" placeholder="Поиск пользователя" id="user_search" value="" />
                                                <button type="button" onClick="showAllUsers()" class="btn btn-info">Все</button>
                                            </div>
                                        </div>
                                        <div class="col-md-4 my-2 my-md-0">
                                            <div class="d-flex align-items-center">
                                                <label class="mr-3 mb-0 d-none d-md-block">Статус: </label>
                                                <select class="form-control" id="kt_datatable_search_status">
                                                    <option value="">Все</option>
                                                    <option value="0">Активные</option>
                                                    <option value="1">Замороженные</option>
                                                </select>
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


<!-- modal edit/create voucher -->
<div class="modal fade" tabindex="-1" role="dialog" id="editVoucherModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Управление ваучером</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="editVoucherForm">

                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Сумма</label>
                        <div class="col-lg-9 col-xl-6">
                            <input class="form-control form-control-lg form-control-solid" type="text" name="value" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Баланс ваучера</label>
                        <div class="col-lg-9 col-xl-6">
                            <select class="form-control" name="bal">
                                <option value="">- баланс ваучера -</option>
                                <? if (count($var_all_bals)>0) { foreach ($var_all_bals as $one_bal) { ?>
                                    <option value="<?=$one_bal['bal_id'];?>"><?=$one_bal['bal_title'];?> (<?=$one_bal['bal_name'];?>)</option>
                                <? } } ?>
                            </select>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onClick="newVoucher()">Создать</button>
            </div>
        </div>
    </div>
</div>