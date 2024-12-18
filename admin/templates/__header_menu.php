<?

$head_menu_structure = 
	[
	'index' => 
		[
		'title'	=>'Главная',
		'rights'=>CAN_MODER
		],
	'news' => 
		[
		'title'	=>'Новости',
		'rights'=>CAN_MODER
		],
	'accounts' => 
		[
		'title'	=>'Аккаунты', 
		'rights'=>CAN_ADMIN, 
		'elems' => 
			[
			'users'	=> 
				[
				'title'	=>'Пользователи',
				'rights'=>CAN_ADMIN
				],
			'admins' => 
				[
				'title'	=>'Админы/Модераторы',
				'rights'=>CAN_SUPER
				],
			'logs'	=> 
				[
				'title'	=>'Логи',
				'rights'=>CAN_ADMIN
				],
			]
		],
	'finances' => 
		[
		'title'	=>'Финансы', 
		'rights'=>CAN_MODER, 
		'elems' => 
			[
			'payments' => 
				[
				'title'	=>'Все платежи',
				'rights'=>CAN_MODER
				],
			'sep1' => 
				[
				'title' => 'separator',
				'rights' => CAN_MODER,
				],
			'baltypes' => 
				[
				'title'	=>'Типы балансов',
				'rights'=>CAN_SUPER
				],
			'bals' => 
				[
				'title'	=>'Балансы',
				'rights'=>CAN_SUPER
				],
			'changes' => 
				[
				'title'	=>'Обмены/Остатки',
				'rights'=>CAN_SUPER
				],
			'sep4' => 
				[
				'title' => 'separator',
				'rights' => CAN_MODER,
				],
			'vouchers' => 
				[
				'title'	=>'Ваучеры',
				'rights'=>CAN_SUPER
				],
			'sep2' => 
				[
				'title' => 'separator',
				'rights' => CAN_MODER,
				],
			'paysys' => 
				[
				'title'	=>'Платежные способы',
				'rights'=>CAN_SUPER
				],
			'sep3' => 
				[
				'title' => 'separator',
				'rights' => CAN_MODER,
				],
			'paystat' => 
				[
				'title'	=>'Все транзакции',
				'rights'=>CAN_SUPER
				]
			]
		],
	'invest' => 
		[
		'title'	=>'Инвестиции', 
		'rights'=>CAN_ADMIN, 
		'elems' => 
			[
			'investplans' => 
				[
				'title'	=>'Инвестиционные планы',
				'rights'=>CAN_ADMIN
				],
			'usersinvest' => 
				[
				'title'	=>'Депозиты пользователей',
				'rights'=>CAN_ADMIN
				],
			]
		],
	'merch' => 
		[
		'title'	=>'Магазины', 
		'rights'=>CAN_MODER, 
		'elems' => 
			[
			'merchants' => 
				[
				'title'	=>'Все магазины',
				'rights'=>CAN_MODER
				],
			'orders' => 
				[
				'title'	=>'Заказы',
				'rights'=>CAN_MODER
				],
			'callbacks' => 
				[
				'title'	=>'Логи обработчика',
				'rights'=>CAN_MODER
				],
			]
		],
	'settings' => 
		[
		'title'	=>'Настройки', 
		'rights'=>CAN_SUPER, 
		'elems' => 
			[
			'refs' => 
				[
				'title'	=>'Реферальные планы',
				'rights'=>CAN_SUPER
				],
			'settings' => 
				[
				'title'	=>'Общие настройки / Безопасность',
				'rights'=>CAN_SUPER
				],
			'langs' => 
				[
				'title'	=>'Языки сайта',
				'rights'=>CAN_SUPER
				],
			]
		],
	];

?>

<!--begin::Header-->
<div id="kt_header" class="header header-fixed">
	<!--begin::Header Wrapper-->
	<div class="header-wrapper rounded-top-xl d-flex flex-grow-1 align-items-center">
		<!--begin::Container-->
		<div class="container-fluid d-flex align-items-center justify-content-end justify-content-lg-between flex-wrap">
			<!--begin::Menu Wrapper-->
			<div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
				<!--begin::Menu-->
				<div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
					<!--begin::Nav-->
					<ul class="menu-nav">
					<? 
					foreach ($head_menu_structure as $k=>$v) { 
					if (!($v['rights'] & $var_admin_rights)) continue;

					if (isset($v['elems']))
						{
						$menu_here_class = (isset($v['elems'][$var_get_page])) ? 'menu-item-here' : '';
						$menu_href = 'javascript:;';
						$menu_toggle = 'menu-toggle';
						}
					else
						{
						$menu_here_class = ($var_get_page==$k) ? 'menu-item-here' : '';
						$menu_href = '/'.$var_adm_path.'/?page='.$k;
						$menu_toggle = '';
						}
					?>
						<li class="menu-item menu-item-open menu-item-submenu menu-item-rel menu-item-open <?=$menu_here_class;?>" data-menu-toggle="click" aria-haspopup="true">
							<a href="<?=$menu_href;?>" class="menu-link <?=$menu_toggle;?>">
								<span class="menu-text"><?=$v['title'];?></span>
								<i class="menu-arrow"></i>
							</a>
							<? if (isset($v['elems'])) { ?>
							<div class="menu-submenu menu-submenu-classic menu-submenu-left">
								<ul class="menu-subnav">
								<? 
								foreach ($v['elems'] as $k2=>$v2) { 
								if (!($v2['rights'] & $var_admin_rights)) continue;

								if ($v2['title']=='separator') {
								?>

									<li><hr></li>

								<?
								} else {
								?>
									<li class="menu-item <? echo (($k2==$var_get_page) ? 'menu-item-active' : ''); ?>" aria-haspopup="true">
										<a href="/<?=$var_adm_path;?>/?page=<?=$k2;?>" class="menu-link">
											<span class="menu-text"><?=$v2['title'];?></span>
											<span class="menu-desc"></span>
										</a>
									</li>
								<? } } ?>
								</ul>
							</div>
							<? } ?>
						</li>
					<? } ?>
					</ul>
					<!--end::Nav-->
				</div>
				<!--end::Menu-->
			</div>
			<!--end::Menu Wrapper-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Header Wrapper-->
</div>
<!--end::Header-->