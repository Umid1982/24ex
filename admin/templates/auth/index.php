<!--begin::Main-->
<div class="d-flex flex-column flex-root">
	<!--begin::Login-->
	<div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
		<!--begin::Aside-->
		<div class="login-aside d-flex flex-column flex-row-auto" style="background-color: #F2C98A;">
			<!--begin::Aside Top-->
			<div class="d-flex flex-column-auto flex-column pt-lg-40 pt-15">
				<!--begin::Aside header-->
				<a href="#" class="text-center mb-10" onClick="return false;">
					<img src="assets/media/logos/logo-letter-1.png" class="max-h-70px" alt="" />
				</a>
				<!--end::Aside header-->
			</div>
			<!--end::Aside Top-->
			<!--begin::Aside Bottom-->
			<div class="aside-img d-flex flex-row-fluid bgi-no-repeat bgi-position-y-bottom bgi-position-x-center" style="background-image: url(assets/media/svg/illustrations/login-visual-1.svg)"></div>
			<!--end::Aside Bottom-->
		</div>
		<!--begin::Aside-->
		<!--begin::Content-->
		<div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
			<!--begin::Content body-->
			<div class="d-flex flex-column-fluid flex-center">
				<!--begin::Signin-->
				<div class="login-form login-signin">
					<!--begin::Form-->
					<form class="form" novalidate="novalidate" id="admin_login" method="POST" action="?page=index/auth">
						<!--begin::Title-->
						<div class="pb-13 pt-lg-0 pt-5">
							<h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Добрый день</h3>
						</div>
						<!--begin::Title-->
						<!--begin::Form group-->
						<div class="form-group">
							<label class="font-size-h6 font-weight-bolder text-dark">Логин</label>
							<input class="form-control form-control-solid h-auto py-6 px-6 rounded-lg" type="text" name="login" autocomplete="off" />
						</div>
						<!--end::Form group-->
						<!--begin::Form group-->
						<div class="form-group">
							<div class="d-flex justify-content-between mt-n5">
								<label class="font-size-h6 font-weight-bolder text-dark pt-5">Пароль</label>
							</div>
							<input class="form-control form-control-solid h-auto py-6 px-6 rounded-lg" type="password" name="pass" autocomplete="off" />
						</div>
						<!--end::Form group-->
						<!--begin::Action-->
						<div class="pb-lg-0 pb-5">
							<button type="submit" id="signin_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">Войти</button>
						</div>
						<!--end::Action-->
					</form>
					<!--end::Form-->
				</div>
				<!--end::Signin-->
			</div>
			<!--end::Content body-->
		</div>
		<!--end::Content-->
	</div>
	<!--end::Login-->
</div>
<!--end::Main-->