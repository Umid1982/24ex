
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <!--begin::Dashboard-->

            <!--begin::Row-->
            <form id="settings_form">
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
                                        Пользовательские настройки
                                    </span>
                                </h3>
                            </div>
                        </div>
                        <!--end:: Card header-->
                        <!--begin::Card body-->
                        <div class="card-body">
                        
                            <div class="form-group">
                                <label>Префикс для балансов юзеров</label>
                                <input type="text" class="form-control" name="sets[ub_pref]" value="<?=$var_ub_pref;?>"/>
                                <span class="form-text text-muted">Все номера кошельков буду начинать с него + длина ниже</span>
                            </div>   
                        
                            <div class="form-group">
                                <label>Длина номеров балансов юзеров</label>
                                <input type="text" class="form-control" name="sets[ub_len]" value="<?=$var_ub_len;?>"/>
                                <span class="form-text text-muted">Максимум 128</span>
                            </div>
                        
                            <div class="form-group">
                                <label>Длина кодов ваучеров</label>
                                <input type="text" class="form-control" name="sets[voucher_len]" value="<?=$var_voucher_len;?>"/>
                                <span class="form-text text-muted">Максимум 128</span>
                            </div>   
                        
                            <div class="form-group">
                                <label>Длина API кодов</label>
                                <input type="text" class="form-control" name="sets[api_len]" value="<?=$var_api_len;?>"/>
                                <span class="form-text text-muted">Максимум 128</span>
                            </div>  
                        
                            <div class="form-group">
                                <label>Время жизни денежных заявок</label>
                                <input type="text" class="form-control" name="sets[pay_life]" value="<?=$var_pay_life;?>"/>
                                <span class="form-text text-muted">Время указывается в минутах</span>
                            </div>   
                        
                            <div class="form-group">
                                <label>Реф. план по умолчанию для ВСЕХ при пополнения балансов</label>
                                <select class="form-control" name="sets[depo_rp_id]">
                                    <option value="0">Без реф. плана</option>
                                    <? if (count($var_refplans)>0) foreach ($var_refplans as $onerp) { ?>
                                        <option value="<?=$onerp['rp_id'];?>" <?=($var_depo_rp_id==$onerp['rp_id'] ? 'selected' : '');?> >
                                            <?=$onerp['rp_title'];?> (<?=$onerp['prcs_line'];?>)</option>
                                    <? } ?>
                                </select>
                                <span class="form-text text-muted">По умолчанию будет использован этот план</span>
                            </div>  
                            
                        </div>
                    </div>
                    <!--end:: Card-->

                    <!--begin::Card-->
                    <div class="card card-custom gutter-b">
                        <!--begin::Card header-->
                        <div class="card-header h-auto">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">
                                        Связь
                                    </span>
                                </h3>
                            </div>
                        </div>
                        <!--end:: Card header-->
                        <!--begin::Card body-->
                        <div class="card-body">
                           
                            <div class="form-group">
                                <label>Канал Telegram (c @)</label>
                                <input type="text" class="form-control" name="sets[tg_chat]" value="<?=$var_tg_chat;?>"/>
                                <span class="form-text text-muted">Обязательно бот должен быть добавлен на канал!</span>
                            </div>  
                            
                        </div>
                    </div>
                    <!--end:: Card-->

                </div>


                <div class="col-xl-6">
                    <!--begin::Charts Widget 5-->
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b">
                        <!--begin::Card header-->
                        <div class="card-header h-auto">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">
                                        Безопасность
                                    </span>
                                </h3>
                            </div>
                        </div>
                        <!--end:: Card header-->
                        <!--begin::Card body-->
                        <div class="card-body"> 
                         
                            <div class="form-group">
                                <label>Список заблокированных IP адресов</label>
                                <textarea class="form-control" name="sets[ips_lock_list]" rows="10"><? if (@count($var_ips_lock_list)>0) echo implode("\r\n",$var_ips_lock_list); ?></textarea>
                            </div>
                        
                            <div class="form-group">
                                <label>Задержка запросов к API</label>
                                <input type="text" class="form-control" name="sets[api_timeout]" value="<?=$var_api_timeout;?>"/>
                                <span class="form-text text-muted">В секундах, для одного пользователя</span>
                            </div> 
                            
                        </div>
                    </div>
                    <!--end:: Card-->

                    <!--begin::Card-->
                    <div class="card card-custom gutter-b">
                        <!--begin::Card header-->
                        <div class="card-header h-auto">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">
                                        Настройки магазинов
                                    </span>
                                </h3>
                            </div>
                        </div>
                        <!--end:: Card header-->
                        <!--begin::Card body-->
                        <div class="card-body">
                        
                            <div class="form-group">
                                <label>Длина ID мерчантов</label>
                                <input type="text" class="form-control" name="sets[merchant_len]" value="<?=$var_merchant_len;?>"/>
                                <span class="form-text text-muted">Максимум 128</span>
                            </div>  
                        
                            <div class="form-group">
                                <label>Таймаут проверки сайта</label>
                                <input type="text" class="form-control" name="sets[merchant_timeout]" value="<?=$var_merchant_timeout;?>"/>
                                <span class="form-text text-muted">В секундах, для одного магазина</span>
                            </div>
                        
                            <div class="form-group">
                                <label>Комиссия по умолчанию для магазинов</label>
                                <input type="text" class="form-control" name="sets[merchant_prc]" value="<?=$var_merchant_prc;?>"/>
                                <span class="form-text text-muted">%</span>
                            </div>  
                        
                            <div class="form-group">
                                <label>Кол-во попыток отправки на Callback</label>
                                <input type="text" class="form-control" name="sets[merchant_try]" value="<?=$var_merchant_try;?>"/>
                                <span class="form-text text-muted">по крону</span>
                            </div>  
                            
                        </div>
                    </div>
                    <!--end:: Card-->

                </div>

                <div class="col-xl-12">
                    <button onClick="saveMainSettings()" type="button" class="btn btn-primary btn-lg btn-block">Сохранить настройки</button>
                </div>


            </div>
            </form>
            <!--end::Row-->
            
            <!--end::Dashboard-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>
<!--end::Content-->                          