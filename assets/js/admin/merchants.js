var KTDatatable;
initKTDatatable(
				'getMerchants',
				[
					{
						field: 'user_line',
						title: 'Пользователь',
						class: 'align-top',
						sortable: false,
						template: function(row){
							return '<a href="/'+adminurl+'/?page=users&search='+row.user_email+'">'+row.user_email+' ('+row.user_id+')</a>';
						}
					},
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
						title: 'Уникальный ID',
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
							if (row.m_is_moder==2) return '<i class="icon-nm flaticon2-exclamation text-warning"></i>';
							else return '<i class="icon-nm flaticon2-'+(row.m_is_moder==1 ? 'check-mark text-success' : 'cross text-danger')+'"></i>';
						}
					},
					{
						field: 'actions',
						title: '',
						class: 'align-top',
						width: 30,
						sortable: false,
						template: function(row){
							return '\
		                        	<a href="'+adminurl+'/?page=merchantone&id='+row.m_num+'" class="btn btn-sm btn-clean btn-icon mr-2" title="Логи">\
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
