var KTDatatable;
initKTDatatable(
				'getMerchants',
				[
				{
					field: 'm_title',
					title: 'Название',
					class: 'align-top',
				},
				{
					field: 'm_domain',
					title: 'Домен',
					class: 'align-top',
				},
				{
					field: 'm_num',
					title: 'ID',
					class: 'align-top',
				},
				{
					field: 'm_is_confirm',
					title: 'Проверен',
					class: 'align-top',
					sortable: false,
					template: function(row){
						return '<i class="icon-nm flaticon2-'+(row.m_is_confirm==1 ? 'check-mark text-success' : 'cross text-danger')+'"></i>';
					}
				},
				{
					field: 'm_is_moder',
					title: 'Модерация',
					class: 'align-top',
					sortable: false,
					template: function(row){
						return '<i class="icon-nm flaticon2-'+(row.m_is_moder==1 ? 'check-mark text-success' : 'cross text-danger')+'"></i>';
					}
				},
				{
					field: 'actions',
					title: '',
					class: 'align-top',
					sortable: false,
					template: function(row){
						return '\
	                        	<a href="/'+nowlang+'/office/merchantone/?id='+row.m_num+'" class="btn btn-sm btn-clean btn-icon mr-2" title="Редактировать">\
	                            <span class="svg-icon svg-icon-md">\
	                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
	                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
	                                        <rect x="0" y="0" width="24" height="24"/>\
	                                        <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero"\ transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>\
	                                        <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>\
	                                    </g>\
	                                </svg>\
	                            </span>\
	                        	</a>\
	                        	<a href="/'+nowlang+'/office/merchantstat/?id='+row.m_num+'" class="btn btn-sm btn-clean btn-icon mr-2" title="Статистика">\
	                            <span class="svg-icon svg-icon-md">\
	                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
	                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
									        <rect x="0" y="0" width="24" height="24"/>\
									        <rect fill="#000000" opacity="0.3" x="2" y="3" width="20" height="18" rx="2"/>\
									        <path d="M9.9486833,13.3162278 C9.81256925,13.7245699 9.43043041,14 9,14 L5,14 C4.44771525,14 4,13.5522847 4,13 C4,12.4477153 4.44771525,12 5,12 L8.27924078,12 L10.0513167,6.68377223 C10.367686,5.73466443 11.7274983,5.78688777 11.9701425,6.75746437 L13.8145063,14.1349195 L14.6055728,12.5527864 C14.7749648,12.2140024 15.1212279,12 15.5,12 L19,12 C19.5522847,12 20,12.4477153 20,13 C20,13.5522847 19.5522847,14 19,14 L16.118034,14 L14.3944272,17.4472136 C13.9792313,18.2776054 12.7550291,18.143222 12.5298575,17.2425356 L10.8627389,10.5740611 L9.9486833,13.3162278 Z" fill="#000000" fill-rule="nonzero"/>\
									        <circle fill="#000000" opacity="0.3" cx="19" cy="6" r="1"/>\
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
