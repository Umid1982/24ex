
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
                                        <a href="/<?=$var_adm_path;?>/?page=bals">Управление балансами</a> - <?=$var_bal_title;?>
                                    </span>
                                </h3>
                            </div>
                        </div>
                        <!--end:: Card header-->
                        <!--begin::Card body-->
                        <div class="card-body">
                         <form id="balone_form">
                            <input type="hidden" name="bal_id" value="<?=$var_bal_id;?>" enctype="multipart/form-data" />

                            <div class="form-row">
                              <div class="form-group col-md-2">

                                <div class="image-input image-input-outline" id="bal_icon_block">
                                 <div class="image-input-wrapper" style="background-image: url(<?=$var_bal_icon;?>)"></div>

                                 <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Редактировать">
                                  <i class="fa fa-pen icon-sm text-muted"></i>
                                  <input type="file" name="bal_icon" accept=".svg"/>
                                  <input type="hidden" />
                                 </label>

                                 <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Удалить">
                                  <i class="ki ki-bold-close icon-xs text-muted"></i>
                                 </span>
                                </div>

                              </div>
                              <div class="col-md-10">
                                <div class="row">


                                  <div class="form-group col-md-3">
                                    <label for="bal_title">Название</label>
                                    <input type="text" name="bal[bal_title]" class="form-control" id="bal_title" value="<?=$var_bal_title;?>" />
                                  </div>
                                  <div class="form-group col-md-2">
                                    <label for="bal_name">Обозначение</label>
                                    <input type="text" name="bal[bal_name]" class="form-control" id="bal_name" value="<?=$var_bal_name;?>" />
                                  </div>
                                  <div class="form-group col-md-3">
                                    <label for="bal_type_id">Тип баланса</label>
                                    <select class="form-control" name="bal[bal_type_id]" id="bal_type_id">
                                        <option value="">- выберите тип баланса -</option>
                                        <? if (count($var_bal_types)) { foreach($var_bal_types as $one_bt) { ?>
                                            <option value="<?=$one_bt['bal_type_id'];?>" <?=($one_bt['bal_type_id']==$var_bal_type_id) ? 'selected' : '';?>><?=$one_bt['bal_type_title'];?></option>
                                        <? } } ?>
                                    </select>
                                  </div>

                                  <div class="form-group col-md-2">
                                    <div class="col-md-12">Активен</div>
                                    <div class="col-12" style="padding-top:10px;">
                                     <span class="switch switch-icon">
                                      <label>
                                       <input type="checkbox" name="bal[bal_status_active]" value="1" <?=(($var_bal_status_active==1) ? 'checked' : '')?>/>
                                       <span></span>
                                      </label>
                                     </span>
                                    </div>
                                  </div>
                                  <div class="form-group col-md-2">
                                    <div class="col-md-12">Базовый</div>
                                    <div class="col-12" style="padding-top:10px;">
                                     <span class="switch switch-icon">
                                      <label>
                                       <input type="checkbox" name="bal[bal_default]" value="1" <?=(($var_bal_default==1) ? 'checked' : '')?>/>
                                       <span></span>
                                      </label>
                                     </span>
                                    </div>
                                  </div>

                                  <div class="form-group col-md-12">
                                    <? if ($var_bal_id!=0) { ?>
                                        <a href="/<?=$var_adm_path;?>/?page=changeone&bal_id=<?=$var_bal_id;?>" class="btn btn-xs btn-outline-primary btn-block">Управление обменами и остатками</a>
                                    <? } ?>
                                  </div>

                                </div>
                            </div>
                            </div>
                            
                            <div class="form-row">
                              <div class="form-group col-md-2">
                                <div class="col-md-12">Пополнение</div>
                                <div class="col-12" style="padding-top:10px;">
                                 <span class="switch switch-icon">
                                  <label>
                                   <input type="checkbox" name="bal[bal_status_payin]" value="1" <?=(($var_bal_status_payin==1) ? 'checked' : '')?>/>
                                   <span></span>
                                  </label>
                                 </span>
                                </div>
                              </div>
                              <div class="form-group col-md-4">
                                <label for="bal_min_payin">Минимум</label>
                                <input type="text" name="bal[bal_min_payin]" class="form-control" id="bal_min_payin" value="<?=$var_bal_min_payin;?>" />
                              </div>
                              <div class="form-group col-md-4">
                                <label for="bal_max_payin">Максимум</label>
                                <input type="text" name="bal[bal_max_payin]" class="form-control" id="bal_max_payin" value="<?=$var_bal_max_payin;?>" />
                              </div>
                              <div class="form-group col-md-2">
                                <label for="bal_com_payin">Комиссия %</label>
                                <input type="text" name="bal[bal_com_payin]" class="form-control" id="bal_com_payin" value="<?=$var_bal_com_payin;?>" />
                              </div>
                            </div>
                            
                            <div class="form-row">
                              <div class="form-group col-md-2">
                                <div class="col-md-12">Вывод</div>
                                <div class="col-md-12" style="padding-top:10px;">
                                 <span class="switch switch-icon">
                                  <label>
                                   <input type="checkbox" name="bal[bal_status_payout]" value="1" <?=(($var_bal_status_payout==1) ? 'checked' : '')?>/>
                                   <span></span>
                                  </label>
                                 </span>
                                </div>
                              </div>
                              <div class="form-group col-md-3">
                                <label for="bal_min_payout">Минимум</label>
                                <input type="text" name="bal[bal_min_payout]" class="form-control" id="bal_min_payout" value="<?=$var_bal_min_payout;?>" />
                              </div>
                              <div class="form-group col-md-3">
                                <label for="bal_max_payout">Максимум</label>
                                <input type="text" name="bal[bal_max_payout]" class="form-control" id="bal_max_payout" value="<?=$var_bal_max_payout;?>" />
                              </div>
                              <div class="form-group col-md-2">
                                <label for="bal_max_payout_day">Лимит в день</label>
                                <input type="text" name="bal[bal_max_payout_day]" class="form-control" id="bal_max_payout_day" value="<?=$var_bal_max_payout_day;?>" />
                              </div>
                              <div class="form-group col-md-2">
                                <label for="bal_com_payout">Комиссия %</label>
                                <input type="text" name="bal[bal_com_payout]" class="form-control" id="bal_com_payout" value="<?=$var_bal_com_payout;?>" />
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-2">
                                <div class="col-md-12">Ваучеры-создание</div>
                                <div class="col-md-12" style="padding-top:10px;">
                                 <span class="switch switch-icon">
                                  <label>
                                   <input type="checkbox" name="bal[bal_status_voucher]" value="1" <?=(($var_bal_status_voucher==1) ? 'checked' : '')?>/>
                                   <span></span>
                                  </label>
                                 </span>
                                </div>
                              </div>
                              <div class="form-group col-md-4">
                                <label for="bal_min_payout">Минимум</label>
                                <input type="text" name="bal[bal_min_voucher]" class="form-control" id="bal_min_voucher" value="<?=$var_bal_min_voucher;?>" />
                              </div>
                              <div class="form-group col-md-4">
                                <label for="bal_max_payout">Максимум</label>
                                <input type="text" name="bal[bal_max_voucher]" class="form-control" id="bal_max_voucher" value="<?=$var_bal_max_voucher;?>" />
                              </div>
                              <div class="form-group col-md-2">
                                <label for="bal_com_payout">Комиссия %</label>
                                <input type="text" name="bal[bal_com_voucher]" class="form-control" id="bal_com_voucher" value="<?=$var_bal_com_voucher;?>" />
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-2">
                                <div class="col-md-12">Переводы</div>
                                <div class="col-12" style="padding-top:10px;">
                                 <span class="switch switch-icon">
                                  <label>
                                   <input type="checkbox" name="bal[bal_status_transfer]" value="1" <?=(($var_bal_status_transfer==1) ? 'checked' : '')?>/>
                                   <span></span>
                                  </label>
                                 </span>
                                </div>
                              </div>
                              <div class="form-group col-md-3">
                                <label for="bal_min_payin">Минимум</label>
                                <input type="text" name="bal[bal_min_transfer]" class="form-control" id="bal_min_transfer" value="<?=$var_bal_min_transfer;?>" />
                              </div>
                              <div class="form-group col-md-3">
                                <label for="bal_max_payin">Максимум</label>
                                <input type="text" name="bal[bal_max_transfer]" class="form-control" id="bal_max_transfer" value="<?=$var_bal_max_transfer;?>" />
                              </div>
                              <div class="form-group col-md-2">
                                <label for="bal_com_payin">Комиссия %</label>
                                <input type="text" name="bal[bal_com_transfer]" class="form-control" id="bal_com_transfer" value="<?=$var_bal_com_transfer;?>" />
                              </div>
                              <div class="form-group col-md-2">
                                <div class="col-md-12">Авто-переводы</div>
                                <div class="col-12" style="padding-top:10px;">
                                 <span class="switch switch-icon">
                                  <label>
                                   <input type="checkbox" name="bal[bal_transfer_auto]" value="1" <?=(($var_bal_transfer_auto==1) ? 'checked' : '')?>/>
                                   <span></span>
                                  </label>
                                 </span>
                                </div>
                              </div>
                              <!--div class="form-group col-md-2">
                                <div class="col-md-12">Авто-конверсия</div>
                                <div class="col-12" style="padding-top:10px;">
                                 <span class="switch switch-icon">
                                  <label>
                                   <input type="checkbox" name="bal[bal_transfer_change]" value="1" <?=(($var_bal_transfer_change==1) ? 'checked' : '')?>/>
                                   <span></span>
                                  </label>
                                 </span>
                                </div>
                              </div-->

                              
                            </div>

                            <div class="form-row">
                                
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-3">
                                <label>Источник курса</label>
                                <select class="form-control" name="bal[bal_rate_from]">
                                    <option value="0" <?=($var_bal_rate_from==0 ? 'selected' : '');?>>Курс вручную (в параметр вписать курс)</option>
                                    <option value="1" <?=($var_bal_rate_from==1 ? 'selected' : '');?>>CoinPayments</option>
                                    <option value="2" <?=($var_bal_rate_from==2 ? 'selected' : '');?>>Cryptonator</option>
                                    <option value="3" <?=($var_bal_rate_from==3 ? 'selected' : '');?>>CBR</option>
                                    <option value="4" <?=($var_bal_rate_from==4 ? 'selected' : '');?>>Privat24</option>
                                </select>
                              </div>
                              <div class="form-group col-md-2">
                                <label>Параметр парсинга</label>
                                <input type="text" name="bal[bal_rate_arg]" class="form-control" value="<?=$var_bal_rate_arg;?>" />
                              </div>
                              <div class="form-group col-md-3">
                                <label>Курс сейчас</label>
                                <input type="text" class="form-control" value="<?=$var_bal_rate;?>" readonly="readonly" />
                              </div>
                              <div class="form-group col-md-4 text-left">
                                <label>% накрутки курса</label>
                                <input type="text" name="bal[bal_rate_com]" class="form-control" value="<?=$var_bal_rate_com;?>" />
                              </div>
                            </div>
                            
                            <div class="form-row">
                              <div class="form-group col-md-9">
                                <label for="bal_min_payin">Можно пополнить с</label>
                                <select class="form-control form-control-lg form-control-solid select2" name="bal_payin_list[]" multiple="multiple">
                                    <? if (count($var_all_pss_in)>0) foreach ($var_all_pss_in as $one_ps) { ?>
                                    <option value="<?=$one_ps['paysys_id'];?>" <?=(in_array($one_ps['paysys_id'],$var_bal_payin_arr) ? 'selected' : '');?>><?=$one_ps['paysys_title'];?></option>
                                    <? } ?>
                                </select>
                              </div>
                              <div class="form-group col-md-3">
                                <div class="col-md-12">Авто-режим пополнения</div>
                                <div class="col-12" style="padding-top:10px;">
                                 <span class="switch switch-icon">
                                  <label>
                                   <input type="checkbox" name="bal[bal_payin_auto]" value="1" <?=(($var_bal_payin_auto==1) ? 'checked' : '')?>/>
                                   <span></span>
                                  </label>
                                 </span>
                                </div>
                              </div>
                              <div class="form-group col-md-12">
                                <label for="bal_max_payin">Можно вывести на</label>
                                <select class="form-control form-control-lg form-control-solid select2" name="bal_payout_list[]" multiple="multiple">
                                    <? if (count($var_all_pss_out)>0) foreach ($var_all_pss_out as $one_ps) { ?>
                                    <option value="<?=$one_ps['paysys_id'];?>" <?=(in_array($one_ps['paysys_id'],$var_bal_payout_arr) ? 'selected' : '');?>><?=$one_ps['paysys_title'];?></option>
                                    <? } ?>
                                </select>
                              </div>
                            </div>

                            <!--button type="button" class="btn btn-danger btn-lg" id="delBalBtn">Удалить</button-->
                            <button type="button" class="btn btn-primary btn-lg" id="saveBalBtn">Сохранить</button>
                          </form>
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