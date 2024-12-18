"use strict";

// Class Definition
var KTLogin = function() {

    var _handleSignInForm = function() {
        var validation;

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validation = FormValidation.formValidation(
			KTUtil.getById('admin_login'),
			{
				fields: {
					login: {
						validators: {
							notEmpty: {
								message: 'Введите логин'
							}
						}
					},
					pass: {
						validators: {
							notEmpty: {
								message: 'Введите пароль'
							}
						}
					}
				},
				plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    //defaultSubmit: new FormValidation.plugins.DefaultSubmit(), // Uncomment this line to enable normal button submit after form validation
					bootstrap: new FormValidation.plugins.Bootstrap()
				}
			}
		);

         $('#admin_login input').keypress(function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                // Get input field values
                $('#signin_submit').trigger( "click" );
            }
        });

        $('#signin_submit').on('click', function (e) {
            e.preventDefault();

            validation.validate().then(function(status) {
		        if (status == 'Valid') {

                    $.post(ajaxurl+'=loginAdmin',$('#admin_login').serialize(),function(data){

                        if (data.result) {

                                if (data.adminRedirTo=='') document.location.href = "/"+adminurl+"/";
                                else document.location.href = data.adminRedirTo;

                                //document.location.href = "/"+adminurl+"/";

                            } else {

                                swal.fire({
                                    text: data.info,
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Хорошо",
                                    customClass: {
                                        confirmButton: "btn font-weight-bold btn-light-primary"
                                    }
                                }).then(function() {
                                    KTUtil.scrollTop();
                                });

                            }

                        },'jSON');

				} 
		    });
        });
    }


    // Public Functions
    return {
        // public functions
        init: function() {
            _handleSignInForm();
        }
    };
}();

// Class Initialization
jQuery(document).ready(function() {
    KTLogin.init();
});
