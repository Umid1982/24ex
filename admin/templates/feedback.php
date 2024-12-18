
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
                                        Сообщения обратной связи
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
                                    <div class="col-lg-9 col-xl-3">
                                        <div class="input-icon">
                                            <input type="text" class="form-control" placeholder="Поиск..." id="kt_datatable_search_query" value="<?=$var_search;?>" />
                                            <span>
                                                <i class="flaticon2-search-1 text-muted"></i>
                                            </span>
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

<!-- modal edit/create admin -->
<div class="modal fade" tabindex="-1" role="dialog" id="feedbackModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Просмотр сообщения</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <!--begin::Contact-->

                    <div class="card card-custom">
                     <div class="card-header">
                      <div class="card-title">
                       <h3 class="card-label" id="msg_title">
                        Text title
                       </h3>
                      </div>
                     </div>
                     <div class="card-body" id="msg_text">
                      Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled.
                     </div>
                    </div>

                    <br>

                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="font-weight-bold mr-2">От кого:</span>
                        <span class="" id="msg_from"></span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="font-weight-bold mr-2">Пользователь:</span>
                        <span class="" id="msg_user_line"></span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="font-weight-bold mr-2">Дата:</span>
                        <span class="" id="msg_dt_line"></span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="font-weight-bold mr-2">Отвечен:</span>
                        <span class="" id="msg_ans_line"></span>
                    </div>
                </div>

                <div class="modal-footer">
                    <div id="ready_ans"></div>
                    <form id="answer_form" style="width:100%">
                        <input type="hidden" name="msg_id" value="" />
                        <button type="button" class="btn btn-primary btn-block" onClick="feedbackMarkAnswer()">Отметить как отвеченное</button>

                        <hr>
                        <div class="form-group">
                            <label>Заголовок <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="msg_ans_title" value="" />
                        </div>
                        <div class="form-group mb-1">
                            <label for="msg_ans_text">Текст <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="msg_ans_text" name="msg_ans_text" rows="10"></textarea>
                        </div>
                        <br>
                        <button type="button" class="btn btn-info btn-block" onClick="feedbackSendAnswer()">Отправить ответный E-mail</button>

                    </form>
                </div>
        </div>
    </div>
</div>