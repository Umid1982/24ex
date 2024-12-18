
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
                                        <a href="/<?=$var_adm_path;?>/?page=changes">Обмены/Остатки</a> - <?=$var_bal_title;?>
                                    </span>
                                </h3>
                            </div>
                        </div>
                        <!--end:: Card header-->
                        <!--begin::Card body-->
                        <div class="card-body">
                         <form id="changeone_form">
                            <input type="hidden" name="bal_id" value="<?=$var_bal_id;?>" enctype="multipart/form-data" />

                            <div class="row">

                              <div class="form-group col-md-4">
                                <label>Текущий остаток</label>
                                <h2><span id="now_ch_value"><?=cutZeros($var_ch_value);?></span> <?=$var_bal_name;?></h2>
                              </div>

                              <div class="form-group col-md-4">
                                <label>Сумма изменения</label>
                                <input type="text" class="form-control" id="ch_value_change" value="" />
                              </div>

                              <div class="form-group col-md-2">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-success form-control" id="ch_change_plus">Внести</button>
                              </div>

                              <div class="form-group col-md-2">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-danger form-control" id="ch_change_minus">Списать</button>
                              </div>

                            </div>

                            <hr>

                            <div class="row">

                              <div class="form-group col-md-3">
                                <div class="col-md-12">Внутренний обмен</div>
                                <div class="col-12" style="padding-top:10px;">
                                 <span class="switch switch-icon">
                                  <label>
                                   <input type="checkbox" name="ch[ch_in_status]" value="1" <?=(($var_ch_in_status==1) ? 'checked' : '')?>/>
                                   <span></span>
                                  </label>
                                 </span>
                                </div>
                              </div>

                              <div class="form-group col-md-7">
                                <label>Можно обменять на (если не выбраны, то все доступные)</label>
                                <select class="form-control form-control-lg form-control-solid select2" name="ch_in_list[]" multiple="multiple">
                                    <? if (count($var_all_chs_in)>0) foreach ($var_all_chs_in as $one_ch) { ?>
                                    <option value="<?=$one_ch['bal_id'];?>" <?=(in_array($one_ch['bal_id'],$var_chs_in_arr) ? 'selected' : '');?>>
                                        <?=('['.$one_ch['bal_name'].'] '.$one_ch['bal_title']);?></option>
                                    <? } ?>
                                </select>
                              </div>

                              <div class="form-group col-md-2">
                                <div class="col-md-12">Авто</div>
                                <div class="col-12" style="padding-top:10px;">
                                 <span class="switch switch-icon">
                                  <label>
                                   <input type="checkbox" name="ch[ch_in_auto]" value="1" <?=(($var_ch_in_auto==1) ? 'checked' : '')?>/>
                                   <span></span>
                                  </label>
                                 </span>
                                </div>
                              </div>

                            </div>

                            <div class="row">
                              <div class="form-group col-md-4">
                                <label>Комиссия %</label>
                                <input type="text" name="ch[ch_in_com]" class="form-control" id="ch_in_com" value="<?=$var_ch_in_com;?>" />
                              </div>
                              <div class="form-group col-md-4">
                                <label>Минимум</label>
                                <input type="text" name="ch[ch_in_min]" class="form-control" id="ch_in_min" value="<?=$var_ch_in_min;?>" />
                              </div>
                              <div class="form-group col-md-4">
                                <label>Максимум</label>
                                <input type="text" name="ch[ch_in_max]" class="form-control" id="ch_in_max" value="<?=$var_ch_in_max;?>" />
                              </div>
                            </div>

                            <hr>

                            <div class="row">

                              <div class="form-group col-md-3">
                                <div class="col-md-12">Обмен на сайте</div>
                                <div class="col-12" style="padding-top:10px;">
                                 <span class="switch switch-icon">
                                  <label>
                                   <input type="checkbox" name="ch[ch_out_status]" value="1" <?=(($var_ch_out_status==1) ? 'checked' : '')?>/>
                                   <span></span>
                                  </label>
                                 </span>
                                </div>
                              </div>

                              <div class="form-group col-md-9">
                                <label>Можно обменять на (если не выбраны, то все доступные)</label>
                                <select class="form-control form-control-lg form-control-solid select2" name="ch_out_list[]" multiple="multiple">
                                    <? if (count($var_all_chs_out)>0) foreach ($var_all_chs_out as $one_ch) { ?>
                                    <option value="<?=$one_ch['bal_id'];?>" <?=(in_array($one_ch['bal_id'],$var_chs_out_arr) ? 'selected' : '');?>>
                                        <?=('['.$one_ch['bal_name'].'] '.$one_ch['bal_title']);?></option>
                                    <? } ?>
                                </select>
                              </div>

                            </div>

                            <div class="row">
                              <div class="form-group col-md-4">
                                <label>Комиссия %</label>
                                <input type="text" name="ch[ch_out_com]" class="form-control" id="ch_out_com" value="<?=$var_ch_out_com;?>" />
                              </div>
                              <div class="form-group col-md-4">
                                <label>Минимум</label>
                                <input type="text" name="ch[ch_out_min]" class="form-control" id="ch_out_min" value="<?=$var_ch_out_min;?>" />
                              </div>
                              <div class="form-group col-md-4">
                                <label>Максимум</label>
                                <input type="text" name="ch[ch_out_max]" class="form-control" id="ch_out_max" value="<?=$var_ch_out_max;?>" />
                              </div>
                            </div>

                            <div class="row">

                              <div class="form-group col-md-4">
                                <label for="bal_type_id">Платежный cпособ (списание)</label>
                                <select class="form-control" name="ch_out_ps_in" id="bal_type_id">
                                    <option value="">- выберите платежный способ -</option>
                                    <? if (count($var_all_pss_in)>0) foreach ($var_all_pss_in as $one_ps) { ?>
                                    <option value="<?=$one_ps['paysys_id'];?>" <?=($one_ps['paysys_id']==$var_ch_out_ps_in ? 'selected' : '');?>><?=$one_ps['paysys_title'];?></option>
                                    <? } ?>
                                </select>
                              </div>

                              <div class="form-group col-md-4">
                                <div class="col-md-12">Авто</div>
                                <div class="col-12" style="padding-top:10px;">
                                 <span class="switch switch-icon">
                                  <label>
                                   <input type="checkbox" name="ch[ch_out_auto]" value="1" <?=(($var_ch_out_auto==1) ? 'checked' : '')?>/>
                                   <span></span>
                                  </label>
                                 </span>
                                </div>
                              </div>

                              <div class="form-group col-md-4">
                                <label for="bal_type_id">Платежный cпособ (зачисление)</label>
                                <select class="form-control" name="ch_out_ps_out" id="bal_type_id">
                                    <option value="">- выберите платежный способ -</option>
                                    <? if (count($var_all_pss_out)>0) foreach ($var_all_pss_out as $one_ps) { ?>
                                    <option value="<?=$one_ps['paysys_id'];?>" <?=($one_ps['paysys_id']==$var_ch_out_ps_out ? 'selected' : '');?>><?=$one_ps['paysys_title'];?></option>
                                    <? } ?>
                                </select>
                              </div>


                            </div>

                            <br>
                            <button type="button" class="btn btn-primary btn-lg" id="saveChBtn">Сохранить</button>


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