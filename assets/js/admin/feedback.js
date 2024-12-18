'use strict';
// Class definition

var KTDatatable;
initKTDatatable(
				'getFeedbacks',
				[
				{
					field: 'sup_msg_id',
					title: 'ID',
					class: 'align-top',
					width: 30
				}, 
				{
					field: 'sup_msg_email',
					title: 'Email',
					class: 'align-top',
					width: 200
				}, 
				{
					field: 'sup_msg_user_line',
					title: 'Пользователь',
					class: 'align-top',
					sortable: false,
					width: 200
				},  
				{
					field: 'sup_msg_title',
					title: 'Тема',
					class: 'align-top',
				},   
				{
					field: 'sup_msg_dt',
					title: 'Дата',
					class: 'align-top',
					template: function(row){
						return row.sup_msg_dt_line;
					}
				},   
				{
					field: 'sup_msg_ans_dt',
					title: 'Отвечен',
					class: 'align-top',
					template: function(row){
						return row.sup_msg_ans_line;
					}
				},
				{
					field: 'actions',
					title: '',
					class: 'align-top',
					autoHide: false,
					template: function(row){
						return '\
	                        	<a href="javascript:openFeedback('+row.sup_msg_id+');" class="btn btn-sm btn-clean btn-icon mr-2" title="Открыть">\
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

// SLK FUNCS

var modal_id = '#feedbackModal';

function openFeedback(id) {

	if (id==0) return false;

	$.post( ajaxurl+'=getFeedbackData', 'id='+id, function(data){

		if (data.result)
			{
			$(modal_id+' [name=msg_id]').val(data.msg_id);
			$(modal_id+' [name=msg_ans_title]').val('Re: '+data.msg_title);

			$(modal_id+' #msg_title').html(data.msg_title);
			$(modal_id+' #msg_text').html(data.msg_text);
			$(modal_id+' #msg_from').html(data.msg_from);
			$(modal_id+' #msg_user_line').html(data.msg_user_line);
			$(modal_id+' #msg_dt_line').html(data.msg_dt_line);
			$(modal_id+' #msg_ans_line').html(data.msg_ans_line);

			if (data.isAns)
				{
				$('#answer_form').hide();
				$('#ready_ans').html('<b>Ответ</b><p>'+data.msg_ans_text+'</p>').show();
				}
			else
				{
				$('#answer_form').show();
				$('#ready_ans').html('').hide();

				}

			$(modal_id).modal('show');
			}


		},'jSON');

}

function feedbackMarkAnswer() {

	if (!confirm('Точно?')) return false;

	var id = parseInt($(modal_id+' [name=msg_id]').val());

	$.post(ajaxurl+'=markAnswer', 'id='+id, function(data){

		if (data.result) {
			$(modal_id).modal('hide');
			$('#kt_datatable').KTDatatable().reload();
		} else {
			showAlert(data.info);
		}

	},'jSON');

}

function feedbackSendAnswer() {

	if (!confirm('Точно?')) return false;

	$.post(ajaxurl+'=sendAnswer', $('#answer_form').serialize(), function(data){

		if (data.result) {
			showAlert('Сообщение отправлено!');
			$('#kt_datatable').KTDatatable().reload();
		} else {
			showAlert(data.info);
		}

	},'jSON');

}

jQuery(document).ready(function() {
	KTDatatable.init();

	let s = $('#kt_datatable_search_query').val();
	if (s!='') $('#kt_datatable_search_query').trigger($.Event("keyup", { keyCode: 13 }));

});