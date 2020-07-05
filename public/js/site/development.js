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

    /* Add address Form */
    $("#orderReviewForm").validate({
        ignore: [],
        rules: {
            food_quality: {
                required: true,
            },
            delivery_time: {
                required: true,
            },
            driver_friendliness: {
                required: true,
            },
            short_review: {
                required: true,
            },
        },
        messages: {
            food_quality: {
                required: "Please rate food quality",
            },
            delivery_time: {
                required: "Please rate delivery time",
            },
            driver_friendliness: {
                required: "Please rate driver friendliness",
            },
            short_review: {
                required: "Please leave a short review",
            },
        }, errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    
    /************************************* Cart Start **********************************/
    // Without Product Attributes - Ingredients check and uncheck
    $(".ingredients").on('click', function () {
        var product_id      = $(this).data('proid');       // without encryption
        var productId       = $(this).data('productid');
        var ingredientId    = $(this).data('ingredientid');
        // var ingredientId    = $(this).val();

        var existingIngredientIds = '';
        existingIngredientIds = $('#product_without_attribute_ingredient_ids_'+product_id).val();
        if (existingIngredientIds != '') {
            noProductAttributeIngredientIds = existingIngredientIds.split(',');
        } else {
            noProductAttributeIngredientIds = [];
        }
        var stringExistingCheck = existingIngredientIds.search(ingredientId);
        if (stringExistingCheck == -1) {
            noProductAttributeIngredientIds.push(ingredientId);
            $('#product_without_attribute_ingredient_ids_'+product_id).val(noProductAttributeIngredientIds);
        } else {
            var strArray = '';
            strArray = existingIngredientIds.split(',');
            for (var i = 0; i < strArray.length; i++) {
                if (strArray[i] == ingredientId) {
                    strArray.splice(i, 1);
                    noProductAttributeIngredientIds.splice(i, 1);
                }
            }
            $('#product_without_attribute_ingredient_ids_'+product_id).val('');
            $('#product_without_attribute_ingredient_ids_'+product_id).val(strArray);
        }
        
        if (productId != '') {
            var selectedIngredients = $('#product_without_attribute_ingredient_ids_'+product_id).val();
            // $('#whole-area').show(); //Showing loader
            // $('body').addClass('clicked');
            if (ajax_check) {
                return;
            }            
            ajax_check = true;
            var ingredientsWithProductPriceUrl = websiteLink + '/' + siteLang + '/ingredients-with-product-price';
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: ingredientsWithProductPriceUrl,
                method: 'POST',
                data: {
                    productId: productId,
                    selectedIngredients: selectedIngredients,
                },
                success: function (ingredientResponse) {
                    ajax_check = false;
                    // console.log(ingredientResponse);
                    $('#product_without_attribute_price_'+product_id).val(ingredientResponse);
                    $('#product_without_attribute_ingredient_price_'+product_id).html(ingredientResponse);
                }
            });
        }
        
    });

    // Add to Cart section
    $(".add_to_basket").on('click', function () {
        var productId = $(this).data('productid');
        var prodId = $(this).data('prodid');    // without encryption
        var showIngredients = $(this).data('showingredients');
        var ingredientIds = '';
        var hasAttribute = $(this).data('hasattribute');
        var attributeId = $(this).data('attributeid');
        var specialId = $(this).data('specialid');
        var drinkId = $(this).data('drinkid');

        var pinCode = $('#pincode').val();
        if (pinCode == '') {
            $('#pincode_modal').addClass('tt_modal_show');
            $('body').addClass('tt_modal_open');
        } else {

            if (productId != '' || specialId != '' || drinkId != '') {
                if (showIngredients != '') {
                    ingredientIds = $('#product_without_attribute_ingredient_ids_'+prodId).val();
                }
                $('body').addClass('clicked');
                if (ajax_check) {
                    return;
                }
                ajax_check = true;

                // Add to cart section START
                var addToCartUrl = websiteLink + '/' + siteLang + '/add-to-cart';
                
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: addToCartUrl,
                    method: 'POST',
                    data: {
                        productId: productId,
                        showIngredients: showIngredients,
                        ingredientIds: ingredientIds,
                        hasAttribute: hasAttribute,
                        attributeId: attributeId,
                        specialId: specialId,
                        drinkId: drinkId
                    },
                    success: function (cartResponse) {
                        ajax_check = false;
                        var data = jQuery.parseJSON(cartResponse);
                        // console.log(data);
                        
                        if (showIngredients != '') {
                            $('#product_without_attribute_ingredient_ids_'+prodId).val('');
                            $('#product_without_attribute_price_'+prodId).val(0);
                            $('.ingredients_checkbox_'+prodId).prop("checked", false);
                            $('#product_without_attribute_ingredient_price_'+prodId).html($('#product_previous_price_'+prodId).val());
                        }                        
                        
                        if (data.type == 'success' && data.restaurantAvailability == 1) {
                            var getCartDataUrl = websiteLink + '/' + siteLang + '/get-cart-details';
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                url: getCartDataUrl,
                                method: 'GET',
                                data: {
                                },
                                success: function (cartDetailsResponse) {
                                    $('body').removeClass('clicked');

                                    // Cart add or update message display
                                    $('#cart_success_message').html('');
                                    $('#cart_success_message').removeClass('alert-warning');
                                    $('#cart_success_message').addClass('alert-success');
                                    $('#cart_success_message').html('<strong>'+data.message+'</strong>');
                                    $("#cart_success_message").slideDown(1000).delay(2000);

                                    $('#cartDetails').html(cartDetailsResponse.html);

                                    // Drinks items
                                    if (prodId != '') {
                                        $('#drinks_items').show(300);
                                    }

                                    // Minimum order section
                                    if (cartDetailsResponse.minOrderMessageStatus == 1) {
                                        $('#remaining_amount').html(cartDetailsResponse.remainingToAvoidMinimumOrder);
                                        $('#min_cart_div').show(500);
                                    } else {
                                        $('#min_cart_div').hide();
                                        $('#remaining_amount').html('');
                                    }

                                    setTimeout(function() {
                                        $("#cart_success_message").slideUp(1000).delay(3000);
                                    }, 3000);
                                }
                            });
                        }
                        else if (data.type == 'error' && data.restaurantAvailability == 0) {
                            $('body').removeClass('clicked');
                            swal.fire({
                                title: data.title,
                                text: data.message,
                                icon: data.type,
                                allowOutsideClick: false,
                                confirmButtonColor: '#1279cf',
                                cancelButtonColor: '#333333',
                                showCancelButton: false,
                                confirmButtonText: 'Ok',
                                // closeOnConfirm: false,
                            });
                        }
                        else {
                            $('body').removeClass('clicked');
                            
                            // Cart add or update message display
                            $('#cart_success_message').html('');
                            $('#cart_success_message').removeClass('alert-success');
                            $('#cart_success_message').addClass('alert-warning');
                            $('#cart_success_message').html('<strong>'+data.message+'</strong>');
                            $("#cart_success_message").slideDown(1000).delay(2000);

                            setTimeout(function() {
                                $("#cart_success_message").slideUp(1000).delay(3000);
                            }, 3000);
                        }                    
                    }
                });
                // Add to cart section END

            }
            
        }        
    });
    /************************************* Cart End ************************************/

    // Add address form
    // $('.custom_click').on('click', function() {
    //     if ($(this).val() != 'Ot') {
    //         $('#other_address_type').val('');
    //     }
    // });

    // Delete address
    $('.delete_address').on('click', function () {
        swal.fire({
			title: 'Delete Address',
			text: 'Are you sure you want to delete this Address?',
            icon: 'warning',
            allowOutsideClick: false,
            // confirmButtonClass: "swal_confirm",
            // cancelButtonClass: "swal_cancel",
            confirmButtonColor: '#1279cf',
            cancelButtonColor: '#333333',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Yes',
            // closeOnConfirm: false,
		}).then((result) => {
			if (result.value) {
                var siteLink = $('#site_link').val();
                var siteLang = $('#site_lang').val();
    
                var addressId = $(this).data('addressid');
                var addrId = $(this).data('addrid');
        
                $('body').addClass('clicked');
                if (ajax_check) {
                    return;
                }
                ajax_check = true;
                var deleteAddressUrl = siteLink + '/' + siteLang + '/users/delete-address';
                                
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: deleteAddressUrl,
                    method: 'POST',
                    data: {
                        addressId: addressId,
                    },
                    success: function (deleteResponse) {
                        $('body').removeClass('clicked');
                        ajax_check = false;

                        var deleteAddressResponse = jQuery.parseJSON(deleteResponse);
                        if (deleteAddressResponse.type == 'success') {
                            swal.fire({
                                title: deleteAddressResponse.title,
                                text: deleteAddressResponse.message,
                                icon: deleteAddressResponse.type,
                                allowOutsideClick: false,
                                // confirmButtonClass: "swal_confirm",
                                // cancelButtonClass: "swal_cancel",
                                confirmButtonColor: '#1279cf',
                                showCancelButton: false,
                                confirmButtonText: 'Ok',
                            });
                            $('#address_'+addrId).remove();
                        } else {
                            swal.fire({
                                title: deleteAddressResponse.title,
                                text: deleteAddressResponse.message,
                                icon: deleteAddressResponse.type,
                                allowOutsideClick: false,
                                // confirmButtonClass: "swal_confirm",
                                // cancelButtonClass: "swal_cancel",
                                confirmButtonColor: '#1279cf',
                                showCancelButton: false,
                                confirmButtonText: 'Ok',
                            });
                        }
                    }
                });
            }
		});

    });

    // Change avatar
    $(".update_avatar").on('click', function () {
        var siteLink = $('#site_link').val();
        var siteLang = $('#site_lang').val();
        var avatarId = $(this).data('id');
        
        if (avatarId != '') {
            $('body').addClass('clicked');
            if (ajax_check) {
                return;
            }

            ajax_check = true;
            var avatarUpdateUrl = siteLink + '/' + siteLang + '/users/change-avatar';
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: avatarUpdateUrl,
                method: 'POST',
                data: {
                    avatarId: avatarId,
                },
                success: function (avatarUpdateResponse) {
                    var response = jQuery.parseJSON(avatarUpdateResponse);
                    console.log(response.updatedAvatar);
                    ajax_check = false;
                    $('.avatar_update').css('background-image', 'url(' + response.updatedAvatar + ')');
                    $('body').removeClass('clicked');
                }
            });
        }
        
    });

    // Pin code checking
    $("#pinCodeForm").validate({
        rules: {
            pin_code: {
                required: true,
            },
        },
        messages: {
            pin_code: {
                required: "Please enter pin code",
            },
        },
        submitHandler: function(form) {
            $('body').addClass('clicked');
            if (ajax_check) {
                return;
            }

            ajax_check = true;
            var pinCodeCheckingUrl = websiteLink + '/' + siteLang + '/pin-code-availability';

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: pinCodeCheckingUrl,
                method: 'POST',
                data: {
                    pinCode: $('#pin_code').val(),
                },
                dataType: 'json',
                success: function (pinCodeResponse) {
                    ajax_check = false;
                    $('body').removeClass('clicked');
                    
                    if (pinCodeResponse.type == 'success') {                        
                        $('#pin_code_available_message').html('');
                        $('#pin_code_available_message').removeClass('errorMessage');
                        $('#pin_code_available_message').addClass('successMessage');
                        $('#pin_code_available_message').html(pinCodeResponse.message).show();
                        setTimeout(function(){ window.location.reload(); }, 2000);
                    } else {
                        $('#pin_code_available_message').html('');
                        $('#pin_code_available_message').removeClass('successMessage');
                        $('#pin_code_available_message').addClass('errorMessage');
                        $('#pin_code_available_message').html(pinCodeResponse.message);
                        $('#pin_code_available_message').show();
                        setTimeout(function(){ $('#pin_code_available_message').hide(500); }, 3000);
                    }
                }
            });
        }
    });

    // Checkout Form - Place Order
    $("#placeOrderForm").validate({
        rules: {
            delivery_time: {
                required: true,
            },
            phone_no: {
                required: true,
            },
            addressAlias: {
                required: true,
            },
            checkout_message: {
                required: true,
            },
        },
        messages: {
            delivery_time: {
                required: "Please select delivery time",
            },
            phone_no: {
                required: "Please enter contact number",
            },
            addressAlias: {
                required: "Please select address",
            },
            checkout_message: {
                required: "Please enter message",
            },
        },
        submitHandler: function(form) {
            $('body').addClass('clicked');
            if (ajax_check) {
                return;
            }

            ajax_check = true;
            var siteLink = $('#site_link').val();
            var siteLang = $('#site_lang').val();
            // Checking Available Timings respective to day
            var checkingAvailableSlotUrl = siteLink + '/' + siteLang + '/users/checking-restaurant-slot-availability';

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: checkingAvailableSlotUrl,
                method: 'POST',
                data: {
                    delivery_time: $('#delivery_time').val(),
                },
                success: function (availableSlotResponse) {
                    var finalResponse = jQuery.parseJSON(availableSlotResponse);
                    // console.log(finalResponse.type);

                    if (finalResponse.type == 'error') {
                        ajax_check = false;
                        $('body').removeClass('clicked');
                        swal.fire({
                            title: finalResponse.title,
                            text: finalResponse.message,
                            icon: finalResponse.type,
                            allowOutsideClick: false,
                            confirmButtonColor: '#1279cf',
                            cancelButtonColor: '#333333',
                            showCancelButton: false,
                            confirmButtonText: 'Ok',
                            // closeOnConfirm: false,
                        });
                    } else {
                        // order place
                        form.submit();
                    }
                }
            });
        }
    });

    // close alert section
    $('.close_alert_box').on('click', function() {
        $('.alert-dismissable').hide(1000);
    });


});


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