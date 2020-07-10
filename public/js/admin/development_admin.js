var site_url = $("#website_admin_link").val();
var ajax_check = false;

$.validator.addMethod("valid_email", function(value, element) {
    if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
        return true;
    } else {
        return false;
    }
}, "Please enter a valid email");

//Phone number eg. (+91)9876543210
$.validator.addMethod("valid_number", function(value, element) {
    if (/^(?=.*[0-9])[- +()0-9]+$/.test(value)) {
        return true;
    } else {
        return false;
    }
}, "Please enter a valid phone number");

//minimum 8 digit,small+capital letter,number,specialcharacter
$.validator.addMethod("valid_password", function(value, element) {
    if (/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/.test(value)) {
        return true;
    } else {
        return false;
    }
});

//Alphabet or number
$.validator.addMethod("valid_coupon_code", function(value, element) {
    if (/^[a-zA-Z0-9]+$/.test(value)) {
        return true;
    } else {
        return false;
    }
});

//Positive number
$.validator.addMethod("valid_positive_number", function(value, element) {
    if (/^[0-9]+$/.test(value)) {
        return true;
    } else {
        return false;
    }
});

//Integer and decimal
$.validator.addMethod("valid_amount", function(value, element) {
    if (/^[1-9]\d*(\.\d+)?$/.test(value)) {
        return true;
    } else {
        return false;
    }
});

//Youtube url checking
$.validator.addMethod("valid_youtube_url", function(value, element) {
    if (/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/.test(value)) {
        return true;
    } else {
        return false;
    }
});


$(document).ready(function() {
    /* Admin Login Form */
    $("#adminLoginForm").validate({
        rules: {
            email: {
                required: true,
                valid_email: true
            },
            password: {
                required: true
            }
        }
    });

    /* Admin Profile Update */
    $("#updateAdminProfile").validate({
        rules: {
            full_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            phone_no: {
                required: true,
            },
        },
        messages: {
            full_name: {
                required: "Please enter full name",
                minlength: "Full name should be atleast 2 characters",
                maxlength: "Full name must not be more than 255 characters"
            },
            phone_no: {
                required: "Please enter phone number",
            },
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Admin Password Update */
    $("#updateAdminPassword").validate({
        rules: {
            current_password: {
                required: true,
                minlength: 8
            },
            password: {
                required: true,
                valid_password: true,
            },
            confirm_password: {
                required: true,
                valid_password: true,
                equalTo: "#password"
            }
        },
        messages: {
            current_password: {
                required: "Please enter current password",
                minlength: "Password should be atleast 8 characters"
            },
            password: {
                required: "Please enter new password",
                valid_password: "Password should be minimum 8 characters, alphanumeric and special character"
            },
            confirm_password: {
                required: "Please enter confirm password",
                valid_password: "Password should be minimum 8 characters, alphanumeric and special character",
                equalTo: "Password should be same as new password",
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* User Profile Add*/
    $("#addUserProfile").validate({
        rules: {
            full_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            email: {
                required: true,
                required: '#phone_no:blank',
                valid_email: function() {
                    if ($("#email").val() != '') {
                        return true;
                    }
                }
            },
            phone_no: {
                required: true,
                required: '#email:blank',
                valid_number: function() {
                    if ($("#phone_no").val() != '') {
                        return true;
                    }
                }
            },
            password: {
                required: true,
                valid_password: true,
            },
            confirm_password: {
                required: true,
                valid_password: true,
                equalTo: "#password"
            },
            user_type: {
                required: true
            },
            back_role_id: {
                required: true
            },
            "front_role_id[]": {
                required: true
            }
        },
        messages: {
            full_name: {
                required: "Please enter name",
                minlength: "Name should be atleast 2 characters",
                maxlength: "Name must not be more than 255 characters"
            },

            email: {
                required: "Please enter email",
            },
            phone_no: {
                required: "Please enter phone number",
            },
            password: {
                required: "Please enter new password",
                valid_password: "Password should be minimum 8 characters, alphanumeric and special character"
            },
            confirm_password: {
                required: "Please enter confirm password",
                valid_password: "Password should be minimum 8 characters, alphanumeric and special character",
                equalTo: "Password should be same as new password",
            },
            back_role_id: {
                required: "Please select any role"
            },
            "front_role_id[]": {
                required: "Please select any role"
            }
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('type') == 'checkbox') {
                error.insertAfter($(element).parents('div.form-control'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* User Profile Add*/
    $("#addAdminUserProfile").validate({
        rules: {
            full_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            email: {
                required: true,
                valid_email: true
            },
            phone_no: {
                required: true,
                valid_number: true
            },
            password: {
                required: true,
                valid_password: true,
            },
        },
        messages: {
            full_name: {
                required: "Please enter name",
                minlength: "Name should be atleast 2 characters",
                maxlength: "Name must not be more than 255 characters"
            },
            email: {
                required: "Please enter email",
            },
            phone_no: {
                required: "Please enter phone number",
            },
            password: {
                required: "Please enter new password",
                valid_password: "Password should be minimum 8 characters, alphanumeric and special character"
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* User Profile Update*/
    $("#updateAdminUserProfile").validate({
        rules: {
            full_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            email: {
                required: true,
                valid_email: true
            },
            phone_no: {
                required: true,
                valid_number: true
            },
            password: {
                valid_password: function() {
                    if ($("#password").val() != '') {
                        return true;
                    }
                }
            },
        },
        messages: {
            full_name: {
                required: "Please enter full name",
                minlength: "Full name should be atleast 2 characters",
                maxlength: "Full name must not be more than 255 characters"
            },
            last_name: {
                required: "Please enter last name",
                minlength: "Last name should be atleast 2 characters",
                maxlength: "Last name must not be more than 255 characters"
            },
            email: {
                required: "Please enter email",
            },
            phone_no: {
                required: "Please enter phone number",
            },
            password: {
                required: "Please enter new password",
                valid_password: "Password should be minimum 8 characters, alphanumeric and special character"
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* User Password Update */
    $("#updateUserPassword").validate({
        rules: {
            password: {
                required: true,
                valid_password: true,
            },
            confirm_password: {
                required: true,
                valid_password: true,
                equalTo: "#password"
            }
        },
        messages: {
            password: {
                required: "Please enter new password",
                valid_password: "Password should be minimum 8 characters, alphanumeric and special character"
            },
            confirm_password: {
                required: "Please enter confirm password",
                valid_password: "Password should be minimum 8 characters, alphanumeric and special character",
                equalTo: "Password should be same as new password",
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

   

    /* CATEGORY start */
    $("#addCategoryForm").validate({
        rules: {
            title: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            image: {
                required: true,
            },
            allow_format: {
                required: true,
            },
        },
        messages: {
            title: {
                required: "Please enter title",
                minlength: "Title should be atleast 2 characters",
                maxlength: "Title must not be more than 255 characters"
            },
            image: {
                required: "Please enter image",
            },
            allow_format: {
                required: "Please enter allow format"
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    $("#editCategoryForm").validate({
        rules: {
            title: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            image: {
                required: true,
            },
            allow_format: {
                required: true,
            },
        },
        messages: {
            title: {
                required: "Please enter title",
                minlength: "Title should be atleast 2 characters",
                maxlength: "Title must not be more than 255 characters"
            },
            image: {
                required: "Please enter image",
            },
            allow_format: {
                required: 'Please enter allow format'
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    /* CATEGORY end */

    /* TAG start */
    $("#addTagForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
        },
        messages: {
            name: {
                required: "Please enter name",
                minlength: "Name should be atleast 2 characters",
                maxlength: "Name must not be more than 255 characters"
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    $("#editTagForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
        },
        messages: {
            name: {
                required: "Please enter name",
                minlength: "Name should be atleast 2 characters",
                maxlength: "Name must not be more than 255 characters"
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    /* TAG end */

     /* Product start */
     $("#addProductForm").validate({
        ignore: [],
        debug: false,
        rules: {
            title: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            description: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            Price: {
                required: true,
            },
            category_id: {
                required: true,
            },
        },
        messages: {
            title: {
                required: "Please enter title",
                minlength: "Title should be atleast 2 characters",
                maxlength: "Title must not be more than 255 characters"
            },
            description: {
                required: "Please enter description",
                minlength: "Description should be atleast 2 characters",
                maxlength: "Description must not be more than 255 characters"
            },
            Price: {
                required: "Please enter price",
            },
            category_id: {
                required: "Please enter category",
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    $("#editProductForm").validate({
        ignore: [],
        debug: false,
        rules: {
            title: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            description: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            Price: {
                required: true,
            },
            category_id: {
                required: true,
            },
        },
        messages: {
            title: {
                required: "Please enter title",
                minlength: "Title should be atleast 2 characters",
                maxlength: "Title must not be more than 255 characters"
            },
            description: {
                required: "Please enter description",
                minlength: "Description should be atleast 2 characters",
                maxlength: "Description must not be more than 255 characters"
            },
            Price: {
                required: "Please enter price",
            },
            category_id: {
                required: "Please enter category",
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    /* Product end */

     /* Banner start */
     $("#addBannerForm").validate({
        ignore: [],
        debug: false,
        rules: {
            title: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            image: {
                required: true,
                
            },
        },
        messages: {
            title: {
                required: "Please enter title",
                minlength: "Title should be atleast 2 characters",
                maxlength: "Title must not be more than 255 characters"
            },
            image: {
                required: "Please enter image",
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    $("#updateBannerForm").validate({
        ignore: [],
        debug: false,
        rules: {
            title: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
        },
        messages: {
            title: {
                required: "Please enter title",
                minlength: "Title should be atleast 2 characters",
                maxlength: "Title must not be more than 255 characters"
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    /* Banner end */

    /* Service start */
    $("#addServiceForm").validate({
        ignore: [],
        debug: false,
        rules: {
            title: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            description: {
                required: true,
                minlength: 2
            },
            image: {
                required: true,
            },
        },
        messages: {
            title: {
                required: "Please enter title",
                minlength: "Title should be atleast 2 characters",
                maxlength: "Title must not be more than 255 characters"
            },
            description: {
                required: "Please enter description",
                minlength: "Description should be atleast 2 characters"
            },
            image: {
                required: "Please enter image",
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    $("#editServiceForm").validate({
        ignore: [],
        debug: false,
        rules: {
            title: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            description: {
                required: true,
                minlength: 2
            },
        },
        messages: {
            title: {
                required: "Please enter title",
                minlength: "Title should be atleast 2 characters",
                maxlength: "Title must not be more than 255 characters"
            },
            description: {
                required: "Please enter description",
                minlength: "Description should be atleast 2 characters"
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    /* Service end */

    
    /* Site Settings */
    $("#updateSiteSettingsForm").validate({
        rules: {
            from_email: {
                required: true,
                valid_email: true
            },
            to_email: {
                required: true,
                valid_email: true
            },
            order_email: {
                required: true,
                valid_email: true
            },
            website_title: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            website_link: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            vat_amount: {
                required: true,
                valid_amount: true
            }
        },
        messages: {
            from_email: {
                required: "Please enter from email",
            },
            to_email: {
                required: "Please enter to email",
            },
            order_email: {
                required: "Please enter order email",
            },
            website_title: {
                required: "Please enter website title",
                minlength: "Website title should be atleast 2 characters",
                maxlength: "Website title must not be more than 255 characters"
            },
            website_link: {
                required: "Please enter website link",
                minlength: "Website link should be atleast 2 characters",
                maxlength: "Website link must not be more than 255 characters"
            },
            vat_amount: {
                required: "Please enter vat amount",
                valid_amount: "Please enter valid amount"
            }
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('id') == 'vat_amount') {
                error.insertAfter($(element).parents('div#vatamount'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Edit Cms*/
    $("#updateCmsForm").validate({
        ignore: [],
        debug: false,
        rules: {
            name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            description: {
                required: function() {
                    CKEDITOR.instances.description.updateElement();
                },
                minlength: 10
            },
            // description2: {
            //     required: function() {
            //         CKEDITOR.instances.description2.updateElement();
            //     },
            //     minlength: 10
            // },
            meta_title: {
                required: true,
            },
            meta_keyword: {
                required: true,
            },
            meta_description: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Please enter page name",
                minlength: "Page name should be atleast 2 characters",
                maxlength: "Page name must not be more than 255 characters"
            },            
            description: {
                required: "Please enter description"
            },
            // description2: {
            //     required: "Please enter description 2"
            // },
            meta_title: {
                required: "Please enter meta title"
            },
            meta_keyword: {
                required: "Please enter meta keyword"
            },
            meta_description: {
                required: "Please enter meta description"
            },
        },
        submitHandler: function(form) {
            form.submit();
        }
    });    

    /* Role Form */
    $("#role-manage-form").validate({
        rules: {
            role_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            }
        },
        messages: {
            role_name: {
                required: "Please enter role name",
                minlength: "Page name should be atleast 2 characters",
                maxlength: "Page name must not be more than 255 characters"
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });


    /*Date range used in Admin user listing (filter) section*/
    //Restriction on key & right click
    $('#registered_date').keydown(function(e) {
        var keyCode = e.which;
        if ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || keyCode === 8 || keyCode === 122 || keyCode === 32 || keyCode == 46) {
            e.preventDefault();
        }
    });
    $('#registered_date').daterangepicker({
        autoUpdateInput: false,
        timePicker: false,
        timePicker24Hour: true,
        timePickerIncrement: 1,
        startDate: moment().startOf('hour'),
        //endDate: moment().startOf('hour').add(24, 'hour'),
        locale: {
            format: 'YYYY-MM-DD'
        }
    }, function(start_date, end_date) {
        $(this.element[0]).val(start_date.format('YYYY-MM-DD') + ' - ' + end_date.format('YYYY-MM-DD'));
    });

    $('#purchase_date').daterangepicker({
        autoUpdateInput: false,
        timePicker: false,
        timePicker24Hour: true,
        timePickerIncrement: 1,
        startDate: moment().startOf('hour'),
        //endDate: moment().startOf('hour').add(24, 'hour'),
        locale: {
            format: 'YYYY-MM-DD'
        }
    }, function(start_date, end_date) {
        $(this.element[0]).val(start_date.format('YYYY-MM-DD') + ' - ' + end_date.format('YYYY-MM-DD'));
    });

    $('#contract_duration').daterangepicker({
        autoUpdateInput: false,
        timePicker: false,
        timePicker24Hour: true,
        timePickerIncrement: 1,
        startDate: moment().startOf('hour'),
        //endDate: moment().startOf('hour').add(24, 'hour'),
        locale: {
            format: 'YYYY-MM-DD'
        }
    }, function(start_date, end_date) {
        $(this.element[0]).val(start_date.format('YYYY-MM-DD') + ' - ' + end_date.format('YYYY-MM-DD'));
    });

    /*Date range used in Coupon listing (filter) section*/
    //Restriction on key & right click
    $('.date_restriction').keydown(function(e) {
        var keyCode = e.which;
        if ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || keyCode === 8 || keyCode === 122 || keyCode === 32 || keyCode == 46) {
            e.preventDefault();
        }
    });
    $('.date_restriction').daterangepicker({
        autoUpdateInput: false,
        timePicker: true,
        timePicker24Hour: true,
        timePickerIncrement: 1,
        startDate: moment().startOf('hour'),
        //endDate: moment().startOf('hour').add(24, 'hour'),
        locale: {
            format: 'YYYY-MM-DD HH:mm'
        }
    }, function(start_date, end_date) {
        $(this.element[0]).val(start_date.format('YYYY-MM-DD HH:mm') + ' - ' + end_date.format('YYYY-MM-DD HH:mm'));
    });

    $("#settlement_status").select2();



    // Sweet alert section







});


function sweetalertMessageRender(target, message, type, confirm = false) {

    let options = {
        icon: type,
        title: '',
        text: message,
        
    };
    if (confirm) {
        options['showCancelButton'] = true;
        options['confirmButtonText'] = 'Yes';
    }

    return Swal.fire(options)
    .then((result) => {
        if (confirm == true && result.value) {
            window.location.href = target.getAttribute('data-href'); 
        } else {
            return (false);
        }
    });

}

$("#purchase_date").keypress(function(event) {event.preventDefault();});
$("#contract_duration").keypress(function(event) {event.preventDefault();});

$(".view_shipment_details").click(function () {
	var shipmentid = $(this).data('shipmentid');	
	if (ajax_check) {
		return;
	}

	ajax_check = true;
	var viewShipmentDetailsUrl = site_url + '/securepanel/contract/view-shipment-details';
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		url: viewShipmentDetailsUrl,
		method: 'post',
		data: {
			shipmentid: shipmentid
		},
		success: function (response) {
			ajax_check = false;
			$('#shipment_detail_view').html(response);
			$('#shipmentModal').modal('show');
		}
	});
});