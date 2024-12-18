
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
                                        Платежные способы&nbsp;&nbsp;
                                        <a href="javascript:editPaysys(0)" class="btn btn-sm btn-light-success font-weight-bolder py-2 px-5">Добавить</a>
                                    </span>
                                </h3>
                            </div>
                        </div>
                        <!--end:: Card header-->
                        <!--begin::Card body-->
                        <div class="card-body">

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

<!-- modal edit/create admin -->
<div class="modal fade" tabindex="-1" role="dialog" id="editPaysysModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Платежный способ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="editAdminForm">

                    <div class="form-group row">
                        <label class="col-xl-4 col-lg-3 col-form-label">Иконка</label>
                        <div class="col-lg-6 col-xl-8">
                            <div class="image-input image-input-outline text-right" id="paysys_icon_block">
                             <div class="image-input-wrapper" style="background-image: url();"></div>

                             <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Радактировать">
                              <i class="fa fa-pen icon-sm text-muted"></i>
                              <input type="file" name="paysys_icon" accept=".svg"/>
                              <input type="hidden" />
                             </label>

                             <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Удалить">
                              <i class="ki ki-bold-close icon-xs text-muted"></i>
                             </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-xl-4 col-lg-3 col-form-label">Название</label>
                        <div class="col-lg-6 col-xl-8">
                            <input class="form-control form-control-lg form-control-solid" type="text" name="paysys_title" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-xl-4 col-lg-3 col-form-label">Описание</label>
                        <div class="col-lg-6 col-xl-8">
                            <textarea class="form-control form-control-lg form-control-solid" type="text" name="paysys_info"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-xl-4 col-lg-3 col-form-label">Курс<br>(для ваучера - любой)</label>
                        <div class="col-lg-6 col-xl-8">
                            <select class="form-control form-control-lg form-control-solid selectpicker" name="bal_id" data-live-search="true">
                                <option value="0">- выберите баланс -</option>
                                <? if (count($var_all_bals)>0) { foreach ($var_all_bals as $one_bal) { ?>
                                    <option value="<?=$one_bal['bal_id'];?>"><?=$one_bal['bal_title'];?> (<?=$one_bal['bal_rate'];?>)</option>
                                <? } } ?>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-xl-4 col-lg-3 col-form-label">Тип</label>
                        <div class="col-lg-6 col-xl-8">
                            <select class="form-control form-control-lg form-control-solid selectpicker" name="paysys_type">
                                <option value="0">- выберите тип способа -</option>
                                <option value="in">Пополнение</option>
                                <option value="out">Вывод средств</option>
                            </select>
                        </div>
                    </div>

                    <div id="type_in_block">

                        <div class="form-group row">
                            <label class="col-xl-4 col-lg-3 col-form-label">Cпособ пополнения</label>
                            <div class="col-lg-6 col-xl-8">
                                <select class="form-control form-control-lg form-control-solid selectpicker" name="paysys_name_in" data-live-search="true">
                                    <option value="0">- выберите способ -</option>
                                       <? if (count($var_all_pss_in)>0) foreach ($var_all_pss_in as $ps_name=>$one_ps) { ?>
                                        <option value="<?=$ps_name;?>"><?=$one_ps['title'];?></option>
                                        <? } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-xl-4 col-lg-3 col-form-label">Доступен в магазинах</label>
                            <div class="col-lg-6 col-xl-8">
                               <span class="switch switch-icon">
                                <label>
                                 <input type="checkbox" name="paysys_merch" />
                                 <span></span>
                                </label>
                               </span>
                            Не актуально для ваучеров
                            </div>
                        </div>

                    </div>

                    <div class="form-group row" id="type_out_block">
                        <label class="col-xl-4 col-lg-3 col-form-label">Cпособ вывода</label>
                        <div class="col-lg-6 col-xl-8">
                            <select class="form-control form-control-lg form-control-solid selectpicker" name="paysys_name_out" data-live-search="true">
                                <option value="0">- выберите способ -</option>
                                   <? if (count($var_all_pss_out)>0) foreach ($var_all_pss_out as $ps_name=>$one_ps) { ?>
                                    <option value="<?=$ps_name;?>"><?=$one_ps['title'];?></option>
                                    <? } ?>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="paysys_id" value="" />

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onClick="deletePaysys()" id="deletePaysysBtn">Удалить</button>
                <button type="button" class="btn btn-primary" onClick="savePaysys()">Сохранить</button>
            </div>
        </div>
    </div>
</div>