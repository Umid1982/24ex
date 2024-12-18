
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <!--begin::Dashboard-->

            <!--begin::Row-->
            <div class="row mt-0 mt-lg-8">


                <div class="col-xl-6">
                    <!--begin::Charts Widget 5-->
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b">
                        <!--begin::Card header-->
                        <div class="card-header h-auto">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">
                                        Telegram уведомления
                                    </span>
                                </h3>
                            </div>
                        </div>
                        <!--end:: Card header-->
                        <!--begin::Card body-->
                        <div class="card-body">

                            <? if ($var_admin_tg=='') { ?>

                            <p>Для работы уведомлений необходимо привязать Ваш аккаунт</p>
                            <p>Наш телеграм бот <b>@<?=$var_tg_bot;?></b></p>
                            <p>Для <b>привязки</b> напишите боту команду <code>/regadmin <?=$var_admin_login;?>:<?=$var_admin_tg_code;?></code></p>

                            <? } else { ?>

                            <p>Привязан TG аккаунт <b>@<?=$var_admin_tg;?></b></p>
                            <p>Для <b>отвязки</b> напишите боту команду <code>/unregadmin <?=$var_admin_login;?>:<?=$var_admin_tg_code;?></code></p>
                        
                            <form class="form" id="ass_form">

                                <div class="row">
                                <label class="col-9 col-form-label">Сообщения обратной связи</label>
                                  <div class="col-3">
                                   <span class="switch switch-icon">
                                    <label>
                                     <input type="checkbox" <?=(@$var_tga['feedback']==1 ? 'checked' : '');?> name="tga[feedback]" value="1" />
                                     <span></span>
                                    </label>
                                   </span>
                                  </div>
                                </div>

                                <div class="row">
                                <label class="col-9 col-form-label">Выводы</label>
                                  <div class="col-3">
                                   <span class="switch switch-icon">
                                    <label>
                                     <input type="checkbox" <?=(@$var_tga['payout']==1 ? 'checked' : '');?> name="tga[payout]" value="1" />
                                     <span></span>
                                    </label>
                                   </span>
                                  </div>
                                </div>

                                <div class="row">
                                <label class="col-9 col-form-label">Пополнение</label>
                                  <div class="col-3">
                                   <span class="switch switch-icon">
                                    <label>
                                     <input type="checkbox" <?=(@$var_tga['payin']==1 ? 'checked' : '');?> name="tga[payin]" value="1" />
                                     <span></span>
                                    </label>
                                   </span>
                                  </div>
                                </div>

                                <div class="row">
                                <label class="col-9 col-form-label">Переводы</label>
                                  <div class="col-3">
                                   <span class="switch switch-icon">
                                    <label>
                                     <input type="checkbox" <?=(@$var_tga['transfer']==1 ? 'checked' : '');?> name="tga[transfer]" value="1" />
                                     <span></span>
                                    </label>
                                   </span>
                                  </div>
                                </div>

                                <div class="row">
                                <label class="col-9 col-form-label">Внутренние обмены</label>
                                  <div class="col-3">
                                   <span class="switch switch-icon">
                                    <label>
                                     <input type="checkbox" <?=(@$var_tga['changein']==1 ? 'checked' : '');?> name="tga[changein]" value="1" />
                                     <span></span>
                                    </label>
                                   </span>
                                  </div>
                                </div>

                                <div class="row">
                                <label class="col-9 col-form-label">Обмены на сайте</label>
                                  <div class="col-3">
                                   <span class="switch switch-icon">
                                    <label>
                                     <input type="checkbox" <?=(@$var_tga['changeout']==1 ? 'checked' : '');?> name="tga[changeout]" value="1" />
                                     <span></span>
                                    </label>
                                   </span>
                                  </div>
                                </div>

                                <div class="row">
                                <label class="col-9 col-form-label">Заказы по магазинам</label>
                                  <div class="col-3">
                                   <span class="switch switch-icon">
                                    <label>
                                     <input type="checkbox" <?=(@$var_tga['order']==1 ? 'checked' : '');?> name="tga[order]" value="1" />
                                     <span></span>
                                    </label>
                                   </span>
                                  </div>
                                </div>

                            </form>

                            <br>
                            <p>
                                <button onClick="saveASS()" type="button" class="btn btn-primary btn-lg btn-block">Сохранить настройки</button>
                            </p>

                            <? } ?>
                            
                        </div>
                    </div>
                    <!--end:: Card-->
                </div>


                <div class="col-xl-6">
                    
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