
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
                                        <a href="/<?=$var_adm_path;?>/?page=investplans">Инвестиционные планы</a> - <?=$var_plan_name;?>
                                    </span>
                                </h3>
                            </div>
                        </div>
                        <!--end:: Card header-->
                        <!--begin::Card body-->
                        <div class="card-body">
                         <form id="planone_form">
                            <input type="hidden" name="plan_id" value="<?=$var_plan_id;?>" />

                            <div class="form-row">

                              <div class="form-group col-md-2">
                                <div class="col-md-12">Активный</div>
                                <div class="col-12" style="padding-top:10px;">
                                 <span class="switch switch-icon">
                                  <label>
                                   <input type="checkbox" name="active" value="1" <?=($var_plan_active==1 ? 'checked' : '');?> />
                                   <span></span>
                                  </label>
                                 </span>
                                </div>
                              </div>

                              <div class="form-group col-md-7">
                                <label>Название</label>
                                <input type="text" name="name" class="form-control" value="<?=$var_plan_name;?>" />
                              </div>

                              <div class="form-group col-md-3">
                                <label>Баланс</label>
                                <select class="form-control" name="bal_id">
                                    <? if (count($var_bals)>0) foreach ($var_bals as $onebal) { ?>
                                        <option value="<?=$onebal['bal_id'];?>" <?=($var_bal_id==$onebal['bal_id'] ? 'selected' : '');?> >
                                            [<?=$onebal['bal_name'];?>] <?=$onebal['bal_title'];?></option>
                                    <? } ?>
                                </select>
                              </div>

                              <div class="form-group col-md-2">
                                <div class="col-md-12">Сложный %</div>
                                <div class="col-12" style="padding-top:10px;">
                                 <span class="switch switch-icon">
                                  <label>
                                   <input type="checkbox" name="compound" value="1" <?=($var_plan_compound==1 ? 'checked' : '');?> />
                                   <span></span>
                                  </label>
                                 </span>
                                </div>
                              </div>

                              <div class="form-group col-md-2">
                                <label>% начисления</label>
                                <input type="text" name="proc" class="form-control" value="<?=$var_plan_proc;?>" />
                              </div>

                              <div class="form-group col-md-4">
                                <label>Мин. депозит</label>
                                <input type="text" name="min" class="form-control" value="<?=$var_plan_min;?>" />
                              </div>

                              <div class="form-group col-md-4">
                                <label>Макс. депозит</label>
                                <input type="text" name="max" class="form-control" value="<?=$var_plan_max;?>" />
                              </div>

                              <div class="form-group col-md-4">
                                <div class="col-md-12">Вернуть на баланс по окончанию</div>
                                <div class="col-12" style="padding-top:10px;">
                                 <span class="switch switch-icon">
                                  <label>
                                   <input type="checkbox" name="refund_depo" value="1" <?=($var_plan_refund_depo==1 ? 'checked' : '');?> />
                                   <span></span>
                                  </label>
                                 </span>
                                </div>
                              </div>  

                              <div class="form-group col-md-4">
                                <label>Начислять каждые</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="time" value="<?=$var_plan_time;?>" />
                                    <div class="input-group-append"><span class="input-group-text">часа(ов)</span></div>
                                </div>
                              </div>

                              <div class="form-group col-md-4">
                                <label>Максимальное время</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="max_time" value="<?=$var_plan_max_time;?>" />
                                    <div class="input-group-append"><span class="input-group-text">часа(ов)</span></div>
                                </div>
                              </div>


                            <div class="form-group col-md-1">
                                <div class="col-md-12">Пн.</div>
                                <div class="col-12" style="padding-top:10px;">
                                 <span class="switch switch-icon">
                                  <label>
                                   <input type="checkbox" name="plan_days[1]" value="1" <?=(@$var_days_1 ? 'checked' : '');?> />
                                   <span></span>
                                  </label>
                                 </span>
                                </div>
                            </div>

                            <div class="form-group col-md-1">
                                <div class="col-md-12">Вт.</div>
                                <div class="col-12" style="padding-top:10px;">
                                 <span class="switch switch-icon">
                                  <label>
                                   <input type="checkbox" name="plan_days[2]" value="1" <?=(@$var_days_2 ? 'checked' : '');?> />
                                   <span></span>
                                  </label>
                                 </span>
                                </div>
                            </div>

                            <div class="form-group col-md-1">
                                <div class="col-md-12">Ср.</div>
                                <div class="col-12" style="padding-top:10px;">
                                 <span class="switch switch-icon">
                                  <label>
                                   <input type="checkbox" name="plan_days[3]" value="1" <?=(@$var_days_3 ? 'checked' : '');?> />
                                   <span></span>
                                  </label>
                                 </span>
                                </div>
                            </div>

                            <div class="form-group col-md-1">
                                <div class="col-md-12">Чт.</div>
                                <div class="col-12" style="padding-top:10px;">
                                 <span class="switch switch-icon">
                                  <label>
                                   <input type="checkbox" name="plan_days[4]" value="1" <?=(@$var_days_4 ? 'checked' : '');?> />
                                   <span></span>
                                  </label>
                                 </span>
                                </div>
                            </div>

                            <div class="form-group col-md-1">
                                <div class="col-md-12">Пт.</div>
                                <div class="col-12" style="padding-top:10px;">
                                 <span class="switch switch-icon">
                                  <label>
                                   <input type="checkbox" name="plan_days[5]" value="1" <?=(@$var_days_5 ? 'checked' : '');?> />
                                   <span></span>
                                  </label>
                                 </span>
                                </div>
                            </div>

                            <div class="form-group col-md-1">
                                <div class="col-md-12">Сб.</div>
                                <div class="col-12" style="padding-top:10px;">
                                 <span class="switch switch-icon">
                                  <label>
                                   <input type="checkbox" name="plan_days[6]" value="1" <?=(@$var_days_6 ? 'checked' : '');?> />
                                   <span></span>
                                  </label>
                                 </span>
                                </div>
                            </div>

                            <div class="form-group col-md-1">
                                <div class="col-md-12">Вс.</div>
                                <div class="col-12" style="padding-top:10px;">
                                 <span class="switch switch-icon">
                                  <label>
                                   <input type="checkbox" name="plan_days[7]" value="1" <?=(@$var_days_7 ? 'checked' : '');?> />
                                   <span></span>
                                  </label>
                                 </span>
                                </div>
                            </div>

                              <div class="form-group col-md-5 text-center" style="padding-top:30px;font-size:15px">
                                Дни начисления процентов
                              </div>



                              <div class="form-group col-md-4">
                                <div class="col-md-12">Можно ли разморозить?</div>
                                <div class="col-12" style="padding-top:10px;">
                                 <span class="switch switch-icon">
                                  <label>
                                   <input type="checkbox" name="defrost" value="1" <?=($var_plan_defrost==1 ? 'checked' : '');?> />
                                   <span></span>
                                  </label>
                                 </span>
                                </div>
                              </div>

                              <div class="form-group col-md-4">
                                <label>через</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="time_defrost" value="<?=$var_plan_time_defrost;?>" />
                                    <div class="input-group-append"><span class="input-group-text">часа(ов)</span></div>
                                </div>
                              </div>

                              <div class="form-group col-md-4">
                                <label>Комиссия за разморозку</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="percent_defrost" value="<?=$var_plan_percent_defrost;?>" />
                                    <div class="input-group-append"><span class="input-group-text">%</span></div>
                                </div>
                              </div>  




                              <div class="form-group col-md-6">
                                <label>Реф. план для депозитов</label>
                                <select class="form-control" name="rp_id_depo">
                                    <option value="">Без реф. плана</option>
                                    <? if (count($var_refplans)>0) foreach ($var_refplans as $onerp) { ?>
                                        <option value="<?=$onerp['rp_id'];?>" <?=($var_rp_id_depo==$onerp['rp_id'] ? 'selected' : '');?> >
                                            <?=$onerp['rp_title'];?> (<?=$onerp['prcs_line'];?>)</option>
                                    <? } ?>
                                </select>
                              </div> 

                              <div class="form-group col-md-6">
                                <label>Реф. план для начисления %</label>
                                <select class="form-control" name="rp_id_proc">
                                    <option value="">Без реф. плана</option>
                                    <? if (count($var_refplans)>0) foreach ($var_refplans as $onerp) { ?>
                                        <option value="<?=$onerp['rp_id'];?>" <?=($var_rp_id_proc==$onerp['rp_id'] ? 'selected' : '');?> >
                                            <?=$onerp['rp_title'];?> (<?=$onerp['prcs_line'];?>)</option>
                                    <? } ?>
                                </select>
                              </div> 


                            </div>

                            <button type="button" class="btn btn-danger btn-lg" id="delPlan">Удалить</button>
                            <button type="button" class="btn btn-primary btn-lg" id="savePlan">Сохранить</button>
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