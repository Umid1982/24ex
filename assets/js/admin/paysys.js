'use strict';
// Class definition

var KTDatatable;
initKTDatatable(
				'getPaysys',
				[
					{
						field: 'paysys_id',
						title: 'ID',
						width: 30,
						class: 'align-top',
					}, 
					{
						field: 'paysys_title',
						title: 'Название для пользователей',
						class: 'align-top',
						sortable: false,
						template: function(row){
							return '<img src="'+row.paysys_icon+'" style="width:20px;" />&nbsp;&nbsp;'+row.paysys_title;
						}
					}, 
					{
						field: 'paysys_system_name',
						title: 'Системное название',
						class: 'align-top',
						sortable: false,
					},  
					{
						field: 'paysys_type',
						title: 'Тип',
						class: 'align-top',
						template: function(row){
							return row.paysys_type=='in' ? 'Для пополнения' : 'Для вывода';
						}
					},  
					{
						field: 'bal_rate',
						title: 'Курс',
						class: 'align-top',
						sortable: false,
					},  
					{
						field: 'actions',
						title: '',
						width: 30,
						class: 'align-top',
						sortable: false,
						autoHide: false,
						template: function(row){
							return '\
		                        	<a href="javascript:editPaysys('+row.paysys_id+')" class="btn btn-sm btn-clean btn-icon mr-2" title="Логи">\
		                            <span class="svg-icon svg-icon-md">\
		                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
		                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
		                                        <rect x="0" y="0" width="24" height="24"/>\
		                                        <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero"\ transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>\
		                                        <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>\
		                                    </g>\
		                                </svg>\
		                            </span>\
		                        	</a>';
						}
					},
				]
			);

jQuery(document).ready(function() {
	KTDatatable.init();
});

// FUNCTIONS

var modal_id = '#editPaysysModal';

function editPaysys(id) {

	if (id==0)
		{
		$('#type_in_block').hide();
		$('#type_out_block').hide();

		$(modal_id+' [name=paysys_id]').val(0);
		$(modal_id+' [name=paysys_title]').val('Новый способ пополнения/вывода');
		$(modal_id+' [name=paysys_info]').val('Информация для пользователей');
		$(modal_id+' [name=paysys_type]').prop('disabled',false).val(0).selectpicker('refresh');
		$(modal_id+' [name=paysys_name_in]').prop('disabled',false).val(0).selectpicker('refresh');
		$(modal_id+' [name=paysys_name_out]').prop('disabled',false).val(0).selectpicker('refresh');
		$(modal_id+' [name=bal_id]').val(0).selectpicker('refresh');

		$(modal_id+' [name=paysys_merch]').prop('checked',false);

		$(modal_id+' [name=paysys_icon]').val(null);
		$(modal_id+' #paysys_icon_block div.image-input-wrapper').css('background-image','url()');

		$('#deletePaysysBtn').hide();

		var balIcon = new KTImageInput('paysys_icon_block');

		$(modal_id).modal('show');
		}
	else
		{
		$.post( ajaxurl+'=getPaysysData', 'id='+id, function(data){

			if (data.result)
				{
				showHideInOut(data.paysys_type);

				$(modal_id+' [name=paysys_id]').val(data.paysys_id);
				$(modal_id+' [name=paysys_title]').val(data.paysys_title);
				$(modal_id+' [name=paysys_info]').val(data.paysys_info);
				$(modal_id+' [name=paysys_type]').prop('disabled',true).val(data.paysys_type).selectpicker('refresh');
				$(modal_id+' [name=paysys_name_'+data.paysys_type+']').prop('disabled',true).val(data.paysys_name).selectpicker('refresh');
				$(modal_id+' [name=bal_id]').val(data.bal_id).selectpicker('refresh');

				//$(modal_id+' [name=paysys_icon]').val(data.paysys_icon);
				$(modal_id+' #paysys_icon_block div.image-input-wrapper').css('background-image','url('+data.paysys_icon+')');

				if (data.paysys_merch==1) $(modal_id+' [name=paysys_merch]').prop('checked',true);
				else $(modal_id+' [name=paysys_merch]').prop('checked',false);

				$('#deletePaysysBtn').show();

				var balIcon = new KTImageInput('paysys_icon_block');

				$(modal_id).modal('show');
				}
			else
				{
				showAlert('Способ не найден');
				}

			},'jSON');
		}

}

function deletePaysys() {

	if (!confirm('Точно удалить?')) return false;
	if (prompt('Введите `delete` чтобы подтвердить удаление')!='delete') return false;

	$.post( ajaxurl+'=deletePaysys', 'id='+$(modal_id+' [name=paysys_id]').val(), function(data){

		if (data.result) {
			
			$(modal_id).modal('hide');
			$('#kt_datatable').KTDatatable().reload();

		} else {

			showAlert(data.info);

			}

		});
}

function savePaysys() {

	var formData = new FormData($(modal_id).find('form')[0]);

	$.ajax({
            url: ajaxurl+'=savePaysys',
            type: 'POST',
            dataType: 'jSON',
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {

                if (data.result) {

					showAlert('Сохранено');
					$(modal_id).modal('hide');
					$('#kt_datatable').KTDatatable().reload();

				} else {

					showAlert(data.info);
				}
		}

	});

}

function showHideInOut(type) {

	if (type=='in') {

		$('#type_in_block').slideDown();
		$('#type_out_block').slideUp();

	} else if (type=='out') {

		$('#type_out_block').slideDown();
		$('#type_in_block').slideUp();

	} else {

		$('#type_out_block').slideUp();
		$('#type_in_block').slideUp();

	}

}

$(document).ready(function(){

	$(modal_id+' [name=paysys_type]').change(function(){

		var type = $(this).val();		
		showHideInOut(type);

	});

});