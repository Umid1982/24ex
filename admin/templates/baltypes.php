
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
                                        Типы балансов&nbsp;&nbsp;
                                        <button class="btn btn-sm btn-light-success font-weight-bolder py-2 px-5" onClick="editBalType(0);">Создать</button>
                                    </span>
                                </h3>
                            </div>
                        </div>
                        <!--end:: Card header-->
                        <!--begin::Card body-->
                        <div class="card-body">
                            <table class="table table-hover" id="balTypesTable">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Название</th>
                                        <th scope="col">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <? foreach ($var_bal_types as $one_bal_type) { ?>
                                    <tr bal_type_id="<?=$one_bal_type['bal_type_id'];?>">
                                        <td class="align-middle">#<?=$one_bal_type['bal_type_id'];?></td>
                                        <td class="align-middle"><?=$one_bal_type['bal_type_title'];?></td>
                                        <td class="align-middle text-right">
                                            <a class="btn btn-sm btn-clean btn-icon mr-2" href="javascript:editBalType(<?=$one_bal_type['bal_type_id'];?>);">
                                            <span class="svg-icon svg-icon-md">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"/>
                                                        <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero"\ transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>
                                                        <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>
                                                    </g>
                                                </svg>
                                            </span> 
                                            </a>
                                        </td>
                                    </tr>
                                    <? } ?>
                                </tbody>
                            </table>
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
<div class="modal fade" tabindex="-1" role="dialog" id="editBalTypeModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Управление типом баланса</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="editBalTypeForm">

                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Название типа баланса</label>
                        <div class="col-lg-9 col-xl-6">
                            <input class="form-control form-control-lg form-control-solid" type="text" name="bal_type_title" />
                        </div>
                    </div>

                    <input type="hidden" name="bal_type_id" value="" />

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onClick="deleteBalType()" id="deleteBalTypeBtn">Удалить</button>
                <button type="button" class="btn btn-primary" onClick="saveBalType()">Сохранить</button>
            </div>
        </div>
    </div>
</div>