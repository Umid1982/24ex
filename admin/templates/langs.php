
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
                                        Переводы
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
                                            <label class="mr-3 mb-0 d-none d-md-block">Язык: </label>
                                            <select class="form-control" id="kt_datatable_search_lang">
                                                <? if (count($var_langs)>0) foreach ($var_langs as $one_lang) { ?>
                                                    <option value="<?=$one_lang;?>"><?=$one_lang;?></option>
                                                <? } ?>
                                            </select>
                                            &nbsp;&nbsp;
                                            <a href="javascript:exportLang();" class="btn btn-primary font-weight-bolder">Экспорт</a>
                                            &nbsp;&nbsp;
                                            <a href="javascript:importLang();" class="btn btn-danger font-weight-bolder">Импорт</a>

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

<!-- import langs csv -->
<div class="modal fade" tabindex="-1" role="dialog" id="importLangModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Импорт CSV</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="importLangForm" class="dropzone" enctype="multipart/form-data">

                    <input type="hidden" name="lang" value="" />
                    <input name="import_file" type="file" />
                    <select name="method">
                        <option value="add" selected>Дополнить перевод</option>
                        <option value="rewrite">Обнулить и перезаписать</option>
                    </select>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onClick="goImportLang()">Импортировать</button>
            </div>
        </div>
    </div>
</div>