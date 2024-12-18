
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
                    <div class="card card-custom gutter-b table-logs">
                        <!--begin::Card header-->
                        <div class="card-header h-auto border-0">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">
                                        <? if ($var_acc_line=='') { ?> Все логи
                                        <? } else { ?><a href="/<?=$var_adm_path;?>/?page=logs&account=">Все логи</a> <? echo $var_acc_line; } ?>
                                    </span>
                                </h3>
                            </div>
                        </div>
                        <!--end:: Card header-->
                        <!--begin::Card body-->
                        <div class="card-body">
                            <script> var now_acc = '<?=$var_now_acc;?>';</script>
                            <form action="/<?=$var_adm_path;?>/">
                                
                                <!--begin::Search Form-->
                                <div class="mb-7">
                                    <div class="row align-items-center">
                                        <div class="col-xl-4">
                                            <div class="input-icon">
                                                <input type="text" class="form-control" placeholder="Выбор пользователя, начните набирать текст для поиска" name="account" value="<?=$var_acc_search_line;?>" />
                                                <span>
                                                    <i class="flaticon2-search-1 text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-xl-3">
                                            <button onClick="selectAcc('')" type="button" class="btn btn-success">Все</button>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <button onClick="selectAcc('user_0')" type="button" class="btn btn-info">Неавторизованные</button>
                                        </div>
                                        <div class="col-xl-5">
                                            <select class="form-control" id="kt_datatable_log_type">
                                                <option value=""> - все типы логов -</option>
                                                <? foreach ($var_all_log_types as $k=>$v) { ?>
                                                    <option value="<?=$k;?>"><?=$v;?></option>
                                                <? } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Search Form-->

                                <input type="hidden" name="page" value="logs" />
                                
                            </form>

                            <hr>

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
<div class="modal fade" tabindex="-1" role="dialog" id="editAdminModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Управление администратором</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="editAdminForm">

                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Логин</label>
                        <div class="col-lg-9 col-xl-6">
                            <input class="form-control form-control-lg form-control-solid" type="text" name="admin_login" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Тип админа</label>
                        <div class="col-lg-9 col-xl-6">
                            <select class="form-control form-control-lg form-control-solid" name="admin_type">
                                <option value="0">Супер-админ</option>
                                <option value="1">Админ</option>
                                <option value="2">Модератор</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Задать новый пароль</label>
                        <div class="col-lg-9 col-xl-6">
                            <input class="form-control form-control-lg form-control-solid" type="text" name="admin_new_pass" />
                        </div>
                    </div>

                    <input type="hidden" name="admin_id" value="" />

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onClick="deleteAdmin()" id="deleteAdminBtn">Удалить</button>
                <button type="button" class="btn btn-primary" onClick="saveAdmin()">Сохранить</button>
            </div>
        </div>
    </div>
</div>