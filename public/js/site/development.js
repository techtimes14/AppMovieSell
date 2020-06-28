$.validator.addMethod("valid_email", function(value, element) {
    if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
        return true;
    } else {
        return false;
    }
}, "Please enter a valid email");

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

// Password required
$.validator.addMethod("password_required", function(value, element) {
    // if (/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/.test(value)) {
    //     return true;
    // } else {
    //     return false;
    // }
    // console.log(value, element);
});

// Confirn password same as new password
$.validator.addMethod("valid_confirm_password", function(value, element) {
    if ($("#password").val() == $("#confirm_password").val()) {
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

// Valid 
$.validator.addMethod("valid_save_folder", function(value, element) {
    if ($('input[name=save]:checked').val() == 'Save in folder') {
        if ($('input[name=save_folder]').is(":checked")) {
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
});


$(document).ready(function() {
    var websitesiteUrl  = $('#website_link').val();
    var ajaxCheck       = false;

    /* User Registration Form */
    $("#registrationForm").validate({
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
            email_register: {
                valid_email: function() {
                    if ($("#email_register").val() != '') {
                        return true;
                    }
                }
            },
            password_register: {
                required: true,
                valid_password: true,
            },
            agree: {
                required: true
            },
        },
        messages: {
           first_name: {
                required: "Please enter first name",
                minlength: "First Name should be atleast 2 characters",
                maxlength: "First Name must not be more than 255 characters"
            },
            last_name: {
                required: "Please enter last name",
                minlength: "Last Name should be atleast 2 characters",
                maxlength: "Last Name must not be more than 255 characters"
            },
            email_register: {
                required: "Please enter email",
            },
            password_register: {
                required: "Please enter password",
                valid_password: "Minimum 8 characters, alphanumeric and special character"
            },
            agree: {
                required: "Please select terms and conditions",
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            var registrationUrl = websitesiteUrl + '/users/register';
            $('#whole-area').show();
            if (ajaxCheck) {
                return;
            }
            ajaxCheck = true;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: registrationUrl,
                method: 'POST',
                data: {
                    'first_name': $('#first_name').val(),
                    'last_name': $('#last_name').val(),
                    'email_register': $('#email_register').val(),
                    'password_register': $('#password_register').val(),
                    'agree': $('#agree').val(),
                },
                dataType: 'json',
                success: function (response) {
                    // console.log(response);
                    ajaxCheck = false;
                    $('#whole-area').hide(); //Showing loader
                    $('#registration_message').html(response.msg)                    
                    if(response.has_error == 0) {
                        $("#registrationForm")[0].reset();
                        $('#registration_message').addClass("successMessage");
                    } else{
                        $('#registration_message').addClass("errorMessage");
                    }
                    $('#registration_message').show();
                    setTimeout(function() {
                        $('#registration_message').html('');
                        $('#registration_message').hide();
                        $('#registration_message').removeClass("successMessage");
                        $('#registration_message').removeClass("errorMessage");
                    }, 3000);
                }
            });
        }
    });

    /* User Login Form */
    $("#loginForm").validate({
        rules: {
            email: {
                required: true,
                valid_email: true
            },
            password: {
                required: true,
            }
        },
        messages: {
            email: {
                required: "Please enter email"
            },
            password: {
                required: "Please enter password",
            }
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            var loginUrl = websitesiteUrl + '/users/login';
            $('#whole-area').show();
            if (ajaxCheck) {
                return;
            }
            ajaxCheck = true;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: loginUrl,
                method: 'POST',
                data: {
                    'email': $('#email').val(),
                    'password': $('#password').val(),
                },
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    ajaxCheck = false;
                    $('#whole-area').hide(); //Showing loader
                    $('#login_message').html(response.msg)                    
                    if(response.has_error == 0) {
                        $('#login_message').addClass("successMessage");
                        var editProfileUrl = websitesiteUrl + '/users/edit-profile';
                        var homeUrl = websitesiteUrl;
                        
                        if(response.temp_password == 0) {
                            window.location.href = homeUrl;
                        } else {
                            window.location.href = editProfileUrl;
                        }                        
                    } else{
                        $('#login_message').addClass("errorMessage");
                    }
                    $('#login_message').show();
                    setTimeout(function() {
                        $('#login_message').html('');
                        $('#login_message').hide();
                        $('#login_message').removeClass("successMessage");
                        $('#login_message').removeClass("errorMessage");
                    }, 3000);
                }
            });
        }
    });

    /* Forgot password Form */
    $("#forgotPasswordForm").validate({
        rules: {
            forgot_email: {
                required: true,
                valid_email: true
            },
        },
        messages: {
            forgot_email: {
                required: "Please enter email"
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            var forgotPasswordUrl = websitesiteUrl + '/users/forgot-password';
            $('#whole-area').show();
            if (ajaxCheck) {
                return;
            }
            ajaxCheck = true;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: forgotPasswordUrl,
                method: 'POST',
                data: {
                    'forgot_email': $('#forgot_email').val(),
                },
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    ajaxCheck = false;
                    $('#whole-area').hide(); //Showing loader
                    $('#forgot_password_message').html(response.msg)                    
                    if(response.has_error == 0) {
                        $('#forgot_password_message').addClass("successMessage");
                    } else{
                        $('#forgot_password_message').addClass("errorMessage");
                    }
                    $('#forgot_password_message').show();
                    setTimeout(function() {
                        $('#forgot_password_message').html('');
                        $('#forgot_password_message').hide();
                        $('#forgot_password_message').removeClass("successMessage");
                        $('#forgot_password_message').removeClass("errorMessage");

                        if(response.has_error == 0) {
                            $('#forgot_email').val('');
                            $('#forgot_pw').modal('hide');
                            $('#loginlink').trigger('click');
                        }
                    }, 3000);
                }
            });
        }
    });

    /* Edit Profile form */
    $("#editProfileFrom").validate({
        rules: {
            first_name: {
                required: true,
                minlength: 2,
                maxlength: 255,
            },
            last_name: {
                required: true,
                minlength: 2,
                maxlength: 255,
            },
            phone_no: {
                required: true,
            },
            gender: {
                required: true,
            },
            email: {
                required: true,
                valid_email: true
            },
            current_password: {
                valid_password: function() {
                    if ($("#current_password").val() != '') {
                        return true;
                    }
                }
            },
            password: {
                valid_password: function() {
                    if ($("#current_password").val() != '') {
                        return true;
                    }
                },
            },
            confirm_password: {
                valid_password: function() {
                    if ($("#current_password").val() != '') {
                        return true;
                    }
                    if ($("#password").val() != $("#confirm_password").val()) {
                        return true;
                    }
                },
                valid_confirm_password: function() {
                    if ($("#current_password").val() != '') {
                        return true;
                    }
                },
            },
        },
        messages: {
            first_name: {
                required: "Please enter first name",
                minlength: "First name should be atleast 2 characters",
                maxlength: "First name must not be more than 255 characters",
            },
            last_name: {
                required: "Please enter last name",
                minlength: "Last name should be atleast 2 characters",
                maxlength: "Last name must not be more than 255 characters",
            },
            phone_no: {
                required: "Please enter phone number",
            },
            email: {
                required: "Please select gender",
            },
            email: {
                required: "Please enter email",
            },
            current_password: {
                valid_password: "Password should be minimum 8 characters, alphanumeric and special character",
            },
            password: {
                password_required: "Please enter new password",
                valid_password: "Password should be minimum 8 characters, alphanumeric and special character",
                equalTo: "Password should be same as new password",
            },
            confirm_password: {
                valid_password: "Password should be minimum 8 characters, alphanumeric and special character",
                valid_confirm_password: "Confirm password should be same as new password",
            },
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('type') == 'radio') {
                error.insertAfter($('.user_gender'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    /* Open & Close video details in pop-up */
    $(".openVideoPopUp").on('click', function() {
        var videoId = $(this).data('vid');
        $('#before_original_contents').show();
        $('#original_contents').hide();

        var videoDetailsUrl     = websitesiteUrl + '/get-video-details/'+videoId;
        var isVideoFavourite    = websitesiteUrl + '/video-details-favourite-checking/'+videoId;

        $('#whole-area').show();
        if (ajaxCheck) {
            return;
        }
        ajaxCheck = true;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: videoDetailsUrl,
            method: 'GET',
            data: {},
            dataType: 'json',
            success: function (response) {
                // console.log(response);                
                ajaxCheck = false;

                // favourite section start
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: isVideoFavourite,
                    method: 'GET',
                    data: {},
                    dataType: 'json',
                    success: function (responseFavourite) {
                        $('.popupfaviconholder').attr('id', 'popup_fav_icon_holder_'+videoId);
                        $('.popupfaviconholder').attr("data-videoid",videoId);

                        $('.popupfavicon').attr('id', 'popup_fav_icon_'+videoId);

                        if (responseFavourite != 0) {
                            $('.popupfavicon').removeClass('popupfav');
                            $('.popupfavicon').addClass('popupfavourited');

                            $('.popupfaviconholder').removeClass('addToFavouiteDetailsPopup');
                            $('.popupfaviconholder').addClass('removeFromFavouiteDetailsPopup');
                        } else {
                            $('.popupfavicon').removeClass('popupfavourited');
                            $('.popupfavicon').addClass('popupfav');

                            $('.popupfaviconholder').removeClass('removeFromFavouiteDetailsPopup');
                            $('.popupfaviconholder').addClass('addToFavouiteDetailsPopup');
                        }                        
                    }
                });
                // favourite section end


                $('#whole-area').hide(); //Showing loader
                
                var embededUrl = 'https://www.youtube.com/embed/'+response.video_id+'?autoplay=1&rel=0&wmode=Opaque&enablejsapi=1';

                var fullIframe = '<iframe id="youtube_video_frame" width="640" height="480" src="'+embededUrl+'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

                $('.ytube_vdo').html(fullIframe);
                $('#youtube_video_title').html(response.title);
                $('#youtube_video_tags').html(response.video_tags);

                $('#before_original_contents').hide();
                $('#original_contents').show();

                $('#browseby_ifrmae').modal('show');
            }
        });
    });
    $('.close_video_popup').on('click', function () {
        $('.ytube_vdo').html('');
    });

    
    /* Create Board Form */
    $("#createBoardForm").validate({
        rules: {
            board_name: {
                required: true,
                minlength: 3,
                maxlength: 20
            },
        },
        messages: {
            board_name: {
                required: "Please enter board name",
                minlength: "Board Name should be atleast 3 characters",
                maxlength: "Board Name must not be more than 20 characters"
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            var createBoardUrl = websitesiteUrl + '/users/create-board';
            $('#whole-area').show();
            if (ajaxCheck) {
                return;
            }
            ajaxCheck = true;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: createBoardUrl,
                method: 'POST',
                data: {
                    'board_name': $('#board_name').val(),
                },
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    ajaxCheck = false;
                    $('#whole-area').hide(); //Showing loader
                    $('#create_board_message').html(response.msg)
                    if(response.has_error == 0) {
                        $('#create_board_message').addClass("successMessage");
                        $('#board_name').val('');
                    } else{
                        $('#create_board_message').addClass("errorMessage");
                    }
                    $('#create_board_message').show();
                    setTimeout(function() {
                        $('#create_board_message').html('');
                        $('#create_board_message').hide();
                        $('#create_board_message').removeClass("successMessage");
                        $('#create_board_message').removeClass("errorMessage");
                        if(response.has_error == 0) {
                            window.location.reload();
                        }
                    }, 3000);
                }
            });
        }
    });
    $('.close_create_board').on('click', function () {
        $('#board_name').val('');
        $('#board_name-error').html('&nbsp;');
        $('#create_board_message').html('');
        window.location.reload();
    });
    

    // Folder double click for Favourite Board page
    $(".folder_double_click").dblclick(function() {
        var board_id = $(this).data('boardid');
        if (board_id != '') {
            window.location = websitesiteUrl + '/users/favourite-board/' + board_id;
        }
    });

    // Add to favourite click effect
    $(document).on('click', '.addToFavouite, .addToFavouiteDetailsPopup', function() {    
        var videoId = $(this).data('videoid');
        if (videoId != '') {
            $('#fav_video_id').val(videoId);
            $('#add_to_favouite').modal('show');
        }
    });
    $('.main_save').on('click', function() {
        $('#fav_video').val('');
        if ($(this).val() != 'Save in folder') {
            $('.child_save').prop('checked',false);
            $('#fav_video').val($(this).val());
        } else {
            if ($('input[name=save_folder]').is(":checked")) {
                $('#fav_video').val($('input[name=save_folder]:checked').val());
            } else {
                $('#fav_video').val('');
            }
        }    
    });
    $('.child_save').on('click', function() {
        $('#save').prop('checked',false);
        $('#save_in_folder').prop('checked',true);
        $('#fav_video').val($(this).val());
    });
    /* Make favourite Form */
    $("#makeFavouriteForm").validate({
        rules: {
            save: {
                required: true,
            },
            save_folder: {
                valid_save_folder: function() {
                    if ($('input[name=save]').is(":checked")) {
                        return true;
                    }
                },
            },
        },
        messages: {
            save: {
                required: "Please select type"
            },
            save_folder: {
                valid_save_folder: "Please select board",
            },
        },
        errorPlacement: function(error, element) {
            if ($(element).attr('type') == 'radio') {
                error.insertAfter($(element).parents('div.add_to_fav'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            var makeFavouriteUrl = websitesiteUrl + '/users/make-favourite';
            $('#whole-area').show();
            if (ajaxCheck) {
                return;
            }
            ajaxCheck = true;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: makeFavouriteUrl,
                method: 'POST',
                data: {
                    'fav_video_id': $('#fav_video_id').val(),
                    'board': $('#fav_video').val(),
                },
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    ajaxCheck = false;
                    $('#whole-area').hide(); //Showing loader
                    $('#make_favourite_message').html(response.msg)
                    if(response.has_error == 0) {
                        $('#make_favourite_message').addClass("successMessage");
                        // $('#fav_video').val('');

                        // styling respective positions & functionalities
                        var vid_id = $('#fav_video_id').val();
                        $('#video_div_'+vid_id).addClass('favourite');  // adding green luv sign                        
                        $('#addToFavouite_'+vid_id+'.addToFavouite').unbind('click');
                        $('#addToFavouite_'+vid_id).removeClass('addToFavouite');   // removing addToFavourite class
                        $('#addToFavouite_'+vid_id).addClass('removeFromFavouite');   // adding removeFromFavourite class

                        // styling respective positions & functionalities from details video popup
                        $('#popup_fav_icon_'+vid_id).removeClass('popupfav');  // remove white luv sign from popup
                        $('#popup_fav_icon_'+vid_id).addClass('popupfavourited');  // remove green luv sign to popup
                        $('#popup_fav_icon_holder_'+vid_id).removeClass('addToFavouiteDetailsPopup');
                        $('#popup_fav_icon_holder_'+vid_id+'.addToFavouiteDetailsPopup').unbind('click');
                        $('#popup_fav_icon_holder_'+vid_id).addClass('removeFromFavouiteDetailsPopup');                        
                        
                    } else{
                        $('#make_favourite_message').addClass("errorMessage");
                    }
                    $('#make_favourite_message').show();
                    setTimeout(function() {
                        $('#fav_video_id').val('');
                        $('#fav_video').val('');
                        $('#make_favourite_message').html('');
                        $('.main_save').prop('checked', false);
                        $('.child_save').prop('checked', false);
                        $('#make_favourite_message').hide();
                        $('#make_favourite_message').removeClass("successMessage");
                        $('#make_favourite_message').removeClass("errorMessage");
                        $('#add_to_favouite').modal('hide');
                    }, 2000);
                }
            });
        }
    });
    $('.close_add_to_favourite').on('click', function() {
        $('#fav_video_id').val('');
        $('#fav_video').val('');
        $('#make_favourite_message').html('');
        $('.main_save').prop('checked', false);
        $('.child_save').prop('checked', false);
    });

    // Remove from favouite
    $(document).on('click', '.removeFromFavouite, .removeFromFavouiteDetailsPopup', function() {
        var vid = $(this).data('videoid');
        // Page will reload or not checking
        var removeVideoBox = 'no';
        if (typeof $(".removeFromFavouite").data('removevideobox') !== 'undefined') {
            removeVideoBox = $('.removeFromFavouite').data('removevideobox');
        }
        
        swal.fire({
			title: 'Remove from favourite',
			text: 'Are you sure to remove from board?',
            icon: 'warning',
            allowOutsideClick: false,
            // confirmButtonClass: "swal_confirm",
            // cancelButtonClass: "swal_cancel",
            confirmButtonColor: '#d6f55b',
            cancelButtonColor: '#141516',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Yes',
            // closeOnConfirm: false,
		}).then((result) => {
			if (result.value) {
				var removeFavouriteUrl = websitesiteUrl + '/users/remove-favourite';
                $('#whole-area').show();
                if (ajaxCheck) {
                    return;
                }
                ajaxCheck = true;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: removeFavouriteUrl,
                    method: 'POST',
                    data: {
                        'video_id': vid,
                    },
                    dataType: 'json',
                    success: function (response) {
                        console.log(response);
                        ajaxCheck = false;
                        $('#whole-area').hide(); //Showing loader
                        
                        if(response.has_error == 0) {
                            // styling respective positions & functionalities
                            $('#video_div_'+vid).removeClass('favourite');  // remove green luv sign
                            $('#addToFavouite_'+vid+'.removeFromFavouite').unbind('click');
                            $('#addToFavouite_'+vid).removeClass('removeFromFavouite');    // removing removeFromFavouite class
                            $('#addToFavouite_'+vid).addClass('addToFavouite');   // adding addToFavourite class

                            // styling respective positions & functionalities from details video popup
                            $('#popup_fav_icon_'+vid).removeClass('popupfavourited');  // remove green luv sign from popup
                            $('#popup_fav_icon_'+vid).addClass('popupfav');  // remove white luv sign from popup
                            $('#popup_fav_icon_holder_'+vid).removeClass('removeFromFavouiteDetailsPopup');
                            $('#popup_fav_icon_holder_'+vid+'.removeFromFavouiteDetailsPopup').unbind('click');
                            $('#popup_fav_icon_holder_'+vid).addClass('addToFavouiteDetailsPopup');

                            if (removeVideoBox == 'no') {
                                swal.fire({
                                    title: "Removed!",
                                    text: "Video has been removed from board successfully",
                                    icon: "success",
                                    showCancelButton: false,
                                    // confirmButtonClass: "swal_cancel",
                                    confirmButtonColor: '#d6f55b',
                                    confirmButtonText: "Ok",
                                    cancelButtonText: "",
                                    // closeOnConfirm: true,
                                    // closeOnCancel: false
                                });
                            } else {
                                swal.fire({
                                    title: "Removed!",
                                    text: "Video has been removed from board successfully",
                                    icon: "success",
                                    showCancelButton: false,
                                    // confirmButtonClass: "swal_cancel",
                                    confirmButtonColor: '#d6f55b',
                                    confirmButtonText: "Ok",
                                    cancelButtonText: "",
                                    // closeOnConfirm: true,
                                    // closeOnCancel: false
                                }).then((result) => {
                                    window.location.reload();
                                });
                            }
                        } else{
                            swal.fire({
                                title: "Error!",
                                text: "Something went wrong, please try again later",
                                icon: "error",
                                showCancelButton: false,
                                // confirmButtonClass: "swal_cancel",
                                confirmButtonColor: '#d6f55b',
                                confirmButtonText: "Ok",
                                cancelButtonText: "",
                                closeOnConfirm: true,
                                closeOnCancel: false
                            });
                        }
                    }
                });
			}			
		});
    });

    // Login to make favourite
    $(document).on('click', '.loginTomakeFavourite', function () {
        $('#loginlink').trigger('click');
    });
    $(document).on('click', '.userNotLoggedin', function () {
        $('#loginlink').trigger('click');
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

    /* forget password Form */
    $("#changePasswordForm").validate({
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
                valid_password: "Password should be minimum 8 characters, alphanumeric and special character"
            },
            confirm_password: {
                required: "Please enter confirm password",
                valid_password: "Password should be minimum 8 characters, alphanumeric and special character",
                equalTo: "Password should be same as new password",
            }
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    
    /* change user password Form */
    $("#change_user_password_form").validate({
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
                valid_password: "Password should be minimum 8 characters, alphanumeric and special character"
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
        }, errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

});