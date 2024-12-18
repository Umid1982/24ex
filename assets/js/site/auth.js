$(document).ready(function(){

	$('#kt_login_signin_form').submit(function(){

		$.post(ajaxurl+'=goLogin',$('#kt_login_signin_form').serialize(),function(data){

			if (data.result) {

					if (data.redirTo=='') document.location.href = "/" + nowlang + "/office/";
					else document.location.href = data.redirTo;

				} else {

					showAlert(data.info);

				}

			},'jSON');

		return false;

	});


	$('#kt_login_signup_form').submit(function(){

		$.post(ajaxurl+'=goReg',$('#kt_login_signup_form').serialize(),function(data){

				//alert(data.info);
				if (data.result)
					{
					let par = $('#kt_login_signup_form');
					let email = $(par).find('input[name=email]').val();
					let pass = $(par).find('input[name=pass1]').val();
					$(par).find('input[type=text],input[type=password]').val('');

					par = $('#kt_login_signin_form');
					$(par).find('input[name=email]').val(email);
					$(par).find('input[name=pass]').val(pass);

					$('#kt_login').removeClass('login-signup-on').addClass('login-signin-on');
					}

				showAlert(data.info);
				

			},'jSON');

		return false;

	});


	$('#kt_login_forgot_form').submit(function(){

		$.post(ajaxurl+'=goRestore',$('#kt_login_forgot_form').serialize(),function(data){

				showAlert(data.info);

			},'jSON');

		return false;

	});


	$('#kt_login_forgot-restore_form').submit(function(){

		$.post(ajaxurl+'=goRestoreConfirm',$('#kt_login_forgot-restore_form').serialize(),function(data){

				if (data.result) {

						document.location.href = "/" + nowlang + "/office/";

					} else {

						showAlert(data.info);
						
					}

			},'jSON');

		return false;

	});


});
