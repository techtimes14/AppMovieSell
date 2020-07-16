$.validator.addMethod("valid_email", function(value, element) {
    if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
        return true;
    } else {
        return false;
    }
}, "Please enter valid email address");

//Phone number eg. (+91)9876543210
$.validator.addMethod("valid_number", function(value, element) {    
    if (/^(?:[+]9)?[0-9]+$/.test(value)) {
        return true;
    } else {
        return false;
    }

}, "Please enter a valid phone number");

//Phone number eg. +919876543210
$.validator.addMethod("valid_site_number", function(value, element) {
    if (/^(?:[+]9)?[0-9]+$/.test(value)) {
        
        if($("#phone_no").val().charAt(0) == '0') {
            return false;
        }
        if($("#phone_no").val().substring(0, 3) == '966') {
            return false;
        }
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

//Integer and decimal
$.validator.addMethod("valid_amount", function(value, element) {
    if (/^[1-9]\d*(\.\d+)?$/.test(value)) {
        return true;
    } else {
        return false;
    }
});

//Pack value 
$.validator.addMethod("pack_value", function(value, element) {
    //if (/^(?=.*[0-9])[- +()0-9]+$/.test(value)) {
    if (/^(?:[+]9)?[0-9]+$/.test(value)) {
        return true;
    } else {
        return false;
    }
}, 'Please enter valid pack value');

//Pack value create bid 
$.validator.addMethod("pack_value_create_bid", function(value, element) {
    
    if (/^(?:[+]9)?[0-9]+$/.test(value)) {
        return true;
    } else {
        $("#error_bids").html('');
        return false;
    }
}, 'Please enter valid pack value');

// quantity value check for create bid
$.validator.addMethod("quantity_create_bid", function(value, element) {
    
    if (/^(?:[+]9)?[0-9]+$/.test(value)) {
        return true;
    } else {
        $("#error_bids").html('');
        return false;
    }
}, 'Please enter valid quantity');

// check value of minimum amount for create bid
$.validator.addMethod("minimum_amount_create_bid", function(value, element) {
    
    if (/^(?:[+]9)?[0-9]+$/.test(value)) {
        return true;
    } else {
        $("#error_bids").html('');
        return false;
    }
}, 'Please enter valid minimum amount');


//mrp
$.validator.addMethod("mrp", function(value, element) {
   
    if (/^[1-9]\d*(\.\d+)?$/.test(value)) {
        return true;
    } else {
        return false;
    }
}, 'Please enter valid amount');

//selling_price
$.validator.addMethod("selling_price", function(value, element) {
    //if (/^(?=.*[0-9])[- +()0-9]+$/.test(value)) {
    if (/^[1-9]\d*(\.\d+)?$/.test(value)) {
        return true;
    } else {
        return false;
    }
}, 'Please enter valid amount');

//End date should be greater than Start date
$.validator.addMethod("greaterThan", function(value, element, params) {
    if (!/Invalid|NaN/.test(new Date(value))) {
        return new Date(value) > new Date($(params).val());
    }
    return isNaN(value) && isNaN($(params).val()) || (Number(value) > Number($(params).val()));
}, 'Must be greater than start date');

//End date should be greater than Start date for create bid
$.validator.addMethod("greaterThanED_create_bid", function(value, element, params) {
    $("#error_bids").html('');
    if (!/Invalid|NaN/.test(new Date(value))) {
        return new Date(value) > new Date($(params).val());
    }
    return isNaN(value) && isNaN($(params).val()) || (Number(value) > Number($(params).val()));
}, 'Must be greater than start date');

// Month validation on card
$.validator.addMethod("valid_month", function(value, element) {
    if (/^01|02|03|04|05|06|07|08|09|10|11|12$/.test(value)) {
        return true;
    } else {
        return false;
    }
}, 'Please enter valid month');

// Valid 4 digit number
$.validator.addMethod("valid_four_digit_number", function(value, element) {
    if (/^[0-9]{4,4}$/.test(value)) {
        return true;
    } else {
        return false;
    }
}, 'Please enter valid year');

// Valid 3 digit number
$.validator.addMethod("valid_three_digit_number", function(value, element) {
    if (/^[0-9]{3,3}$/.test(value)) {
        return true;
    } else {
        return false;
    }
}, 'Please enter valid cvv');

$(document).ready(function() {
    var websiteLink = $('#website_link').val();
    var siteLang = $('#website_lang').val();
    var ajax_check = false;

    $('#website_language').on('change', function() {
        var langValue = $(this).val();
        
        //var setLangUrl = websiteLink + '/' + langValue;
        // Code Edit ----------------------
        var websiteUrl = window.location.href;
        var splitAll = websiteUrl.split('/');
        var keyVal = '';
       
        splitAll.forEach(function(item, index){
            if(item == 'en' || item == 'de') {
                keyVal = index;
            }
        });
        splitAll[keyVal]= langValue;
        
        var setLangUrl  =  splitAll.join('/');
        // Code Edit ----------------------
        
        window.location = setLangUrl;        
    });

    $('.dashboard_website_language').on('click', function() {
        var langValue = $(this).data('lang');
        
        //var setLangUrl = websiteLink + '/' + langValue;
        // Code Edit ----------------------
        var websiteUrl = window.location.href;
        var splitAll = websiteUrl.split('/');
        var keyVal = '';
       
        splitAll.forEach(function(item, index){
            if(item == 'en' || item == 'de') {
                keyVal = index;
            }
        });
        splitAll[keyVal]= langValue;
        
        var setLangUrl  =  splitAll.join('/');
        // Code Edit ----------------------
        
        window.location = setLangUrl;        
    });

    /* Sign up Form */
    $("#signUpForm").validate({
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
            user_name: {
                required: true,
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
        },
        messages: {
           full_name: {
                required: "Please enter full name",
                minlength: "Full Name should be at least 2 characters",
                maxlength: "Full Name must not be more than 255 characters"
            },
            email: {
                required: "Please enter email address",
                valid_email: "Please enter valid email address",
            },
            user_name: {
                required: "Please enter username",
            },
            password: {
                required: "Please enter password",
                valid_password: "Min. 8, alphanumeric and special character"
            },
            confirm_password: {
                required: "Please enter confirm password",
                valid_password: "Min. 8, alphanumeric and special character",
                equalTo: "Password should be same as password",
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Payment method Form */
    $('#paymentMethodForm .card_validation').on('keyup',function(){
		cardFormValidate();
	});
    $("#paymentMethodForm").validate({
        rules: {
            name_on_card: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            card_number: {
                required: true,
            },
            expiry_month: {
                required: true,
                valid_month: true
            },            
            expiry_year: {
                required: true,
                valid_four_digit_number: true,
            },
            cvv: {
                required: true,
                valid_three_digit_number: true,
            },
        },
        messages: {
           name_on_card: {
                required: "Please enter name on card",
                minlength: "Name should be at least 2 characters",
                maxlength: "Name must not be more than 255 characters"
            },
            card_number: {
                required: "Please enter card number",
            },
            expiry_month: {
                required: "Please enter expiry month",
                valid_month: "Please enter valid expiry month",
            },            
            expiry_year: {
                required: "Please enter expiry year",
                valid_four_digit_number: "Please enter valid expiry year",
            },
            cvv: {
                required: "Please enter cvv",
                valid_three_digit_number: "Please enter valid cvv",
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* User Login Form */
    $("#loginForm").validate({
        rules: {
            email: {
                required: true,
                valid_email: true,
            },
            password: {
                required: true,
            }
        },
        messages: {
            email: {
                required: "Please enter email address",
                valid_email: "Please enter valid email address"
            },
            password: {
                required: "Please enter password",
            }
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Affiliated sign up Form */
    $("#affiliatedSignUpForm").validate({
        rules: {
            email: {
                required: true,
                valid_email: true
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
        },
        messages: {
            email: {
                required: "Please enter email address",
                valid_email: "Please enter valid email address",
            },
            password: {
                required: "Please enter password",
                valid_password: "Min. 8, alphanumeric and special character"
            },
            confirm_password: {
                required: "Please enter confirm password",
                valid_password: "Min. 8, alphanumeric and special character",
                equalTo: "Password should be same as password",
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Affiliated Payment method Form */
    $('#affiliatedPaymentForm .card_validation').on('keyup',function(){
		affiliatedCardFormValidate();
	});
    $("#affiliatedPaymentForm").validate({
        rules: {
            first_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            last_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            postal_code: {
                required: true,
            },
            name_on_card: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            card_number: {
                required: true,
            },
            expiry_month: {
                required: true,
                valid_month: true
            },            
            expiry_year: {
                required: true,
                valid_four_digit_number: true,
            },
            cvv: {
                required: true,
                valid_three_digit_number: true,
            },
        },
        messages: {
            first_name: {
                required: "Please enter first name",
                minlength: "First name should be at least 2 characters",
                maxlength: "First name must not be more than 255 characters"
            },
            last_name: {
                required: "Please enter last name",
                minlength: "Last name should be at least 2 characters",
                maxlength: "Last name must not be more than 255 characters"
            },
            postal_code: {
                required: "Please enter postal code",
            },
            name_on_card: {
                required: "Please enter name on card",
                minlength: "Name should be at least 2 characters",
                maxlength: "Name must not be more than 255 characters"
            },
            card_number: {
                required: "Please enter card number",
            },
            expiry_month: {
                required: "Please enter expiry month",
                valid_month: "Please enter valid expiry month",
            },            
            expiry_year: {
                required: "Please enter expiry year",
                valid_four_digit_number: "Please enter valid expiry year",
            },
            cvv: {
                required: "Please enter cvv",
                valid_three_digit_number: "Please enter valid cvv",
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* change password Form */
    $("#forgetPasswordForm").validate({
        rules: {
            email: {
                required: true,
                valid_email: true
            }
        },
        messages: {
            email: {
                required: "Please enter email"
            }
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* reset password Form */
    $("#resetPasswordForm").validate({
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
                required: "Please enter password",
                valid_password: "Min. 8, alphanumeric and special character"
            },
            confirm_password: {
                required: "Please enter confirm password",
                valid_password: "Min. 8, alphanumeric and special character",
                equalTo: "Confirm password should be same as new password",
            }
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Edit profile Form */
    $("#editProfile").validate({
        rules: {
            first_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            last_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            user_name: {
                required: true,
            },
            email: {
                required: true,
                valid_email: true
            },
            author_bio: {
                required: true
            },
            billing_first_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            billing_last_name: {
                required: true,
                minlength: 2,
                maxlength: 255
            },
            billing_email: {
                required: true,
                valid_email: true
            },
            billing_country: {
                required: true,
            },
            billing_address_line_1: {
                required: true,
            },
            billing_address_line_2: {
                required: true,
            },
            billing_city: {
                required: true,
            },
        },
        messages: {
            first_name: {
                required: "Please enter first name",
                minlength: "First name should be at least 2 characters",
                maxlength: "First name must not be more than 255 characters"
            },
            last_name: {
                required: "Please enter last name",
                minlength: "Last name should be at least 2 characters",
                maxlength: "Last name must not be more than 255 characters"
            },
            user_name: {
                required: "Please enter username",
            },
            email: {
                required: "Please enter email address",
                valid_email: "Please enter valid email address",
            },
            author_bio: {
                required: "Please enter brief about yourself",
            },
            billing_first_name: {
                required: "Please enter first name",
                minlength: "First name should be at least 2 characters",
                maxlength: "First name must not be more than 255 characters"
            },
            billing_last_name: {
                required: "Please enter last name",
                minlength: "Last name should be at least 2 characters",
                maxlength: "Last name must not be more than 255 characters"
            },
            billing_email: {
                required: "Please enter email address",
                valid_email: "Please enter valid email address",
            },
            billing_country: {
                required: "Please select country",
            },
            billing_address_line_1: {
                required: "Please enter address line one",
            },
            billing_address_line_2: {
                required: "Please enter address line two",
            },
            billing_city: {
                required: "Please enter city",
            },
            billing_postal_code: {
                required: "Please enter zip/postal code",
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* change user password Form */
    $("#changeUserPasswordForm").validate({
        rules: {
            current_password: {
                required: true,
                valid_password: true,
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
                required: "Please enter password",
                valid_password: "Min. 8, alphanumeric and special character"
            },
            password: {
                required: "Please enter new password",
                valid_password: "Min. 8, alphanumeric and special character"
            },
            confirm_password: {
                required: "Please enter confirm password",
                valid_password: "Min. 8, alphanumeric and special character",
                equalTo: "Password should be same as new password",
            }
        }, errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    
    /* Add address Form */
    $("#addAddressForm").validate({
        rules: {
            company: {
                required: true,
            },
            street: {
                required: true,
            },
            floor: {
                required: true,
            },
            door_code: {
                required: true,
            },
            post_code: {
                required: true,
            },
            city: {
                required: true,
            },
            addressAlias: {
                required: true,
            },
            city: {
                required: true,
            },
            customAlias: {
                required: true,
            },            
        },
        messages: {
            company: {
                required: "Please enter company or c/o",
            },
            street: {
                required: "Please enter street and number",
            },
            floor: {
                required: "Please enter floor",
            },
            door_code: {
                required: "Please enter door code",
            },
            post_code: {
                required: "Please enter post code",
            },
            city: {
                required: "Please enter city",
            },
            addressAlias: {
                required: "Please select address type",
            },
            customAlias: {
                required: "Please enter your own alias",
            },            
        }, errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Edit address Form */
    $("#editAddressForm").validate({
        rules: {
            company: {
                required: true,
            },
            street: {
                required: true,
            },
            floor: {
                required: true,
            },
            door_code: {
                required: true,
            },
            post_code: {
                required: true,
            },
            city: {
                required: true,
            },
            addressAlias: {
                required: true,
            },
            city: {
                required: true,
            },
            customAlias: {
                required: true,
            },            
        },
        messages: {
            company: {
                required: "Please enter company or c/o",
            },
            street: {
                required: "Please enter street and number",
            },
            floor: {
                required: "Please enter floor",
            },
            door_code: {
                required: "Please enter door code",
            },
            post_code: {
                required: "Please enter post code",
            },
            city: {
                required: "Please enter city",
            },
            addressAlias: {
                required: "Please select address type",
            },
            customAlias: {
                required: "Please enter your own alias",
            },            
        }, errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });


    // Pagination click
    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault(); 
        var page        = $(this).attr('href').split('page=')[1];
        var lookingFor  = $('#looking_for').val();
        var category    = $('#category_id').val();
        var perPage     = $('#per_page').val();
        var price       = $('#price').val();

        getMoreProducts(page, lookingFor, category, price, perPage);
    });
    // Per page change
    $(document).on('change', '#per_page', function(event) {
        event.preventDefault(); 
        var page        = 1;
        var lookingFor  = $('#looking_for').val();
        var category    = $('#category_id').val();
        var price       = $('#price').val();
        var perPage     = $(this).val();       

        getMoreProducts(page, lookingFor, category, price, perPage);
    });
    // Sort by price change
    $(document).on('change', '#price', function(event) {
        event.preventDefault(); 
        var page        = 1;
        var lookingFor  = $('#looking_for').val();
        var category    = $('#category_id').val();
        var perPage     = $('#per_page').val();
        var price       = $(this).val();

        getMoreProducts(page, lookingFor, category, price, perPage);
    });
    // Search By
    $(document).on('click', '#searchBtn', function(event) {
        event.preventDefault(); 
        var page        = 1;
        var lookingFor  = $('#looking_for').val();
        var category    = $('#category_id').val();
        var price       = $('#price').val();
        var perPage     = $('#per_page').val();

        getMoreProducts(page, lookingFor, category, price, perPage);
    });
    // On Enter key presss for search box
    $('#looking_for').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            var page        = 1;
            var lookingFor  = $('#looking_for').val();
            var category    = $('#category_id').val();
            var price       = $('#price').val();
            var perPage     = $('#per_page').val();

            getMoreProducts(page, lookingFor, category, price, perPage);
        }
    });
    // Category click
    $(document).on('click', '.category', function(event) {
        event.preventDefault(); 
        var page        = 1;
        var lookingFor  = $('#looking_for').val();
        var category    = $(this).data('catid');
        var categoryName= $(this).data('catname');        
        var price       = $('#price').val();
        var perPage     = $('#per_page').val();

        $('#category_id').val(category);
        $('#category_name').val(categoryName);
        $('#selected_category').html('');
        $('.category').removeClass('active');
        if (category != '') {
            $(this).addClass('active');
            $('#selected_category').html(': '+categoryName);
        }

        getMoreProducts(page, lookingFor, category, price, perPage);
    });


    // close alert section
    $('.close_alert_box').on('click', function() {
        $('.alert-dismissable').hide(1000);
    });

});

function getMoreProducts(page, lookingFor, category, price, perPage) {
    $('#loading').show();

    var websiteLink = $('#website_link').val();
    var updatedUrl          = websiteLink + '/market-place';
    var getMoreProductsUrl  = websiteLink + '/market-place-products';

    if (page != 1) {
        updatedUrl          = updatedUrl + '?page='+page+'&lookingFor='+lookingFor+'&category='+category+'&price='+price+'&perPage='+perPage;
        getMoreProductsUrl  = getMoreProductsUrl + '?page='+page+'&lookingFor='+lookingFor+'&category='+category+'&price='+price+'&perPage='+perPage;
    } else {
        updatedUrl          = updatedUrl + '?lookingFor='+lookingFor+'&category='+category+'&price='+price+'&perPage='+perPage;
        getMoreProductsUrl  = getMoreProductsUrl + '?page='+page+'&lookingFor='+lookingFor+'&category='+category+'&price='+price+'&perPage='+perPage;
    }
    window.history.pushState({path: updatedUrl},'',updatedUrl);
    // console.log(updatedUrl);
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: getMoreProductsUrl,
        success:function(data) {
            $('#products_div').html(data);
            $('#loading').hide();
        }
    });
}

function sweetalertMessageRender(target, message, type, confirm = false) {
    let options = {
        title: '',
        text: message,
        icon: type,
        type: type,
        confirmButtonColor: '#144B8B',
        cancelButtonColor: '#02C402',
    };
    if (confirm) {
        options['showCancelButton'] = true;
        options['cancelButtonText'] = 'Cancel';
        options['confirmButtonText'] = 'Yes';
    }
    return Swal.fire(options).then((result) => {
        if (confirm == true && result.value) {
            window.location.href = target.getAttribute('data-href'); 
        } else {
            return (false);
        }
    });
}

function cardFormValidate(){
	var cardValid = 0;
	  
	//card number validation
	$('#card_number').validateCreditCard(function(result) {
        // console.log(result);
		var cardType = (result.card_type == null)?'':result.card_type.name;
		if (cardType == 'Visa') {
			var backPosition = result.valid?'10px -158px, 454px -82px':'10px -158px, 454px -55px';
		} else if(cardType == 'visa_electron') {
			var backPosition = result.valid?'10px -200px, 454px -82px':'10px -200px, 454px -55px';
		} else if(cardType == 'MasterCard') {
			var backPosition = result.valid?'10px -242px, 454px -82px':'10px -242px, 454px -55px';
		} else if(cardType == 'Maestro') {
			var backPosition = result.valid?'10px -284px, 454px -82px':'10px -284px, 454px -55px';
		} else if(cardType == 'Discover') {
			var backPosition = result.valid?'10px -326px, 454px -82px':'10px -326px, 454px -55px';
		} else if(cardType == 'Amex') {
			var backPosition = result.valid?'10px -116px, 454px -82px':'10px -116px, 454px -55px';
		} else {
			var backPosition = result.valid?'10px -116px, 454px -82px':'10px -116px, 454px -55px';
		}
		$('#card_number').css("background-position", backPosition);
		if (result.valid) {
			$("#card_type").val(cardType);
			$("#card_number").removeClass('required');
			cardValid = 1;
		}else{
			$("#card_type").val('');
			$("#card_number").addClass('required');
			cardValid = 0;
		}
	});
	  
	//card details validation
	var cardName = $("#name_on_card").val();
	var expMonth = $("#expiry_month").val();
	var expYear = $("#expiry_year").val();
	var cvv = $("#cvv").val();
	var regName = /^[a-z ,.'-]+$/i;
	var regMonth = /^01|02|03|04|05|06|07|08|09|10|11|12$/;
	// var regYear = /^2017|2018|2019|2020|2021|2022|2023|2024|2025|2026|2027|2028|2029|2030|2031$/;
    var regYear = /^[0-9]{4,4}$/;
	var regCVV = /^[0-9]{3,3}$/;

    if (!regName.test(cardName)) {
		$("#name_on_card").focus();
		return false;
	} else if (cardValid == 0) {
		$("#card_number").focus();
		return false;
	} else if (!regMonth.test(expMonth)) {
		$("#expiry_month").focus();
		return false;
	} else if (!regYear.test(expYear)) {
		$("#expiry_year").focus();
		return false;
	} else if (!regCVV.test(cvv)) {
		$("#cvv").focus();
		return false;
	} else {
		return true;
	}
}

function affiliatedCardFormValidate(){
	var cardValid = 0;
	  
	//card number validation
	$('#card_number').validateCreditCard(function(result) {
        // console.log(result);
		var cardType = (result.card_type == null)?'':result.card_type.name;
		if (cardType == 'Visa') {
			var backPosition = result.valid?'10px -158px, 415px -82px':'10px -158px, 415px -55px';
		} else if(cardType == 'visa_electron') {
			var backPosition = result.valid?'10px -200px, 415px -82px':'10px -200px, 415px -55px';
		} else if(cardType == 'MasterCard') {
			var backPosition = result.valid?'10px -242px, 415px -82px':'10px -242px, 415px -55px';
		} else if(cardType == 'Maestro') {
			var backPosition = result.valid?'10px -284px, 415px -82px':'10px -284px, 415px -55px';
		} else if(cardType == 'Discover') {
			var backPosition = result.valid?'10px -326px, 415px -82px':'10px -326px, 415px -55px';
		} else if(cardType == 'Amex') {
			var backPosition = result.valid?'10px -116px, 415px -82px':'10px -116px, 415px -55px';
		} else {
			var backPosition = result.valid?'10px -116px, 415px -82px':'10px -116px, 415px -55px';
		}
		$('#card_number').css("background-position", backPosition);
		if (result.valid) {
			$("#card_type").val(cardType);
			$("#card_number").removeClass('required');
			cardValid = 1;
		}else{
			$("#card_type").val('');
			$("#card_number").addClass('required');
			cardValid = 0;
		}
	});
	  
	//card details validation
	var cardName = $("#name_on_card").val();
	var expMonth = $("#expiry_month").val();
	var expYear = $("#expiry_year").val();
	var cvv = $("#cvv").val();
	var regName = /^[a-z ,.'-]+$/i;
	var regMonth = /^01|02|03|04|05|06|07|08|09|10|11|12$/;
	// var regYear = /^2017|2018|2019|2020|2021|2022|2023|2024|2025|2026|2027|2028|2029|2030|2031$/;
    var regYear = /^[0-9]{4,4}$/;
	var regCVV = /^[0-9]{3,3}$/;

    if (!regName.test(cardName)) {
		$("#name_on_card").focus();
		return false;
	} else if (cardValid == 0) {
		$("#card_number").focus();
		return false;
	} else if (!regMonth.test(expMonth)) {
		$("#expiry_month").focus();
		return false;
	} else if (!regYear.test(expYear)) {
		$("#expiry_year").focus();
		return false;
	} else if (!regCVV.test(cvv)) {
		$("#cvv").focus();
		return false;
	} else {
		return true;
	}
}