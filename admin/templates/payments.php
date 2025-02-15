
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
                                        Все платежи
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
                                    <div class="col-md-5 my-2 my-md-0">
                                        <div class="d-flex align-items-center">
                                            <label class="mr-3 mb-0 d-none d-md-block">Пользователь: </label>
                                            <input type="hidden" id="kt_datatable_search_user" value="" />
                                            <input type="text" class="form-control" placeholder="Поиск пользователя" id="user_search" value="" />
                                            <button type="button" onClick="showAllUsers()" class="btn btn-info">Все</button>
                                        </div>
                                    </div>
                                    <div class="col-md-3 my-2 my-md-0">
                                        <div class="d-flex align-items-center">
                                            <label class="mr-3 mb-0 d-none d-md-block">Тип: </label>
                                            <select class="form-control" id="kt_datatable_search_type">
                                                <option value="">Все</option>
                                                <option value="0">Пополнение</option>
                                                <option value="1">Вывод</option>
                                                <option value="2">Внутренний обмен</option>
                                                <option value="3">Обмен на сайте</option>
                                                <option value="4">Переводы</option>
                                                <option value="5">Заказы в магазинах</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 my-2 my-md-0">
                                        <div class="d-flex align-items-center">
                                            <label class="mr-3 mb-0 d-none d-md-block">Статус: </label>
                                            <select class="form-control" id="kt_datatable_search_status">
                                                <option value="">Все</option>
                                                <option value="0">Новые</option>
                                                <option value="5">Оплачена</option>
                                                <option value="1">В работе</option>
                                                <option value="2">Отменена</option>
                                                <option value="3">Отклонена</option>
                                                <option value="4">Завершена</option>
                                                <option value="6">Ожидание подтверждения оплаты</option>
                                                <option value="7">Ожидание оплаты/реквизитов</option>
                                                <option value="8">Отмечена как оплаченная</option>
                                                <option value="9">Реквизиты введены</option>
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