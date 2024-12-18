
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
                                        <a href="/<?=$var_adm_path;?>/?page=news">Новости</a> - <?=$var_n_title;?>
                                    </span>
                                </h3>
                            </div>
                        </div>
                        <!--end:: Card header-->
                        <!--begin::Card body-->
                        <div class="card-body">
                         <form id="newsone_form">
                            <input type="hidden" name="n_id" value="<?=$var_n_id;?>" />

                            <div class="form-row">

                              <div class="form-group col-md-10">
                                <label>Заголовок</label>
                                <input type="text" name="n_title" class="form-control" value="<?=$var_n_title;?>" />
                              </div>

                              <div class="form-group col-md-2">
                                <label>Язык</label>
                                <select class="form-control" name="n_lang">
                                    <option value="">Для всех</option>
                                    <? foreach ($var_all_langs as $one_lang) { ?> 
                                        <option value="<?=$one_lang;?>" <?=($var_n_lang==$one_lang ? 'selected' : '');?>><?=strtoupper($one_lang);?></option>
                                    <? } ?>
                                </select>
                              </div>

                              <div class="form-group col-md-12">
                                <label>Текст</label>
                                <textarea class="form-control" name="n_body" id="n_body"><?=$var_n_body;?></textarea>
                              </div>

                              <div class="form-group col-md-4">
                                <label>Статус</label>
                                <select class="form-control" name="n_status">
                                    <option value="0" <?=($var_n_status==N_STATUS_DRAFT ? 'selected' : '');?>>Черновик</option>
                                    <option value="1" <?=($var_n_status==N_STATUS_PUB ? 'selected' : '');?>>Опубликовано</option>
                                </select>
                              </div>

                              <div class="form-group col-md-4">
                                <label>Дата публикации</label>
                                <input type="text" name="n_dt_pub" class="form-control" value="<?=$var_n_dt_pub_line;?>" data-toggle="datetimepicker" />
                              </div>

                              <div class="form-group col-md-4">
                                <label>&nbsp;</label>
                                <p>Если дата публикации установлена в будущем, новость будет запланирована</p>
                              </div>

                            </div>

                            <button type="button" class="btn btn-danger btn-lg" id="delNews">Удалить</button>
                            <button type="button" class="btn btn-primary btn-lg" id="saveNewsBtn">Сохранить</button>
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