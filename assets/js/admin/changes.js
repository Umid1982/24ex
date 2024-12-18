'use strict';
// Class definition

var KTDatatable;
initKTDatatable(
				'getBals',
				[
				{
					field: 'bal_id',
					title: 'ID',
					class: 'align-top',
					width: 30,
				}, 
				{
					field: 'bal_title',
					title: 'Название',
					class: 'align-top',
					template: function(row){
						return '<img src="'+row.bal_icon+'" style="width:20px;" /> ['+row.bal_name+'] '+row.bal_title;
					}
				},
				{
					field: 'ch_value',
					title: 'Остаток',
					class: 'align-top',
					sorting: false,
					template: function(row){
						if (!row.has_ch) return ' - ';
						return row.ch_data.ch_value;
					}
				},
				{
					field: 'ch_in_status',
					title: 'Обмен<br>в ЛК',
					class: 'align-top',
					sorting: false,
					template: function(row){
						if (!row.has_ch) return ' - ';
						return '<i class="icon-nm flaticon2-'+(row.ch_data.ch_in_status==1 ? 'check-mark text-success' : 'cross text-danger')+'"></i>';
					}
				},
				{
					field: 'ch_out_status',
					title: 'Обмен<br>на сайте',
					class: 'align-top',
					sorting: false,
					template: function(row){
						if (!row.has_ch) return ' - ';
						return '<i class="icon-nm flaticon2-'+(row.ch_data.ch_out_status==1 ? 'check-mark text-success' : 'cross text-danger')+'"></i>';
					}
				},
				{
					field: 'actions',
					title: '',
					class: 'align-top',
					autoHide: false,
					width: 30,
					template: function(row){
						return '\
	                        	<a href="'+adminurl+'/?page=changeone&bal_id='+row.bal_id+'" class="btn btn-sm btn-clean btn-icon mr-2" title="Логи">\
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