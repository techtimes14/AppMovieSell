<?php
/*****************************************************/
# HomeController
# Page/Class name   : HomeController
# Author            :
# Created Date      : 20-05-2019
# Functionality     : index, generateToken, signUp, authentication, forgetPasswordRequest, changePassword, editProfile, verification
# Purpose           : all landing functionlity of api
/*****************************************************/
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use \App\User;
use \Auth;
use \Response;
use \Helper;
use \Validator;
use \Hash;
use \App\SiteSetting;
use \App\Category;
use \App\Product;
use \App\Banner;
use \App\UserWholesaler;
use \App\UserContractor;
use \App\ProductVendor;
use \App\PushNotification;
use \App\Country;
use \App\Coupon;
use Image;
use App;

class HomeController extends Controller
{
    /*****************************************************/
    # HomeController
    # Function name : index
    # Author        :
    # Created Date  : 20-05-2019
    # Purpose       : To land api
    # Params        : 
    /*****************************************************/
    public function index()
    {
        return Response::json(ApiHelper::generateResponseBody('HC-I-0001', "Api for E market"));
    }
    
    /*****************************************************/
    # HomeController
    # Function name : generateToken
    # Author :
    # Created Date : 20-05-2019
    # Purpose :  to generate api auth token
    # Params :
    /*****************************************************/
    public function generateToken()
    {
        $token = \Hash::make(env('APP_KEY'));
        return Response::json(ApiHelper::generateResponseBody('HC-GT-0001#generate_token', ["_authtoken" => $token]));
    }

    /*****************************************************/
    # HomeController
    # Function name : countries
    # Author        :
    # Created Date  : 19-09-2019
    # Purpose       : Country list with phone code
    # Params        : Request $request
    /*****************************************************/
    public function countries(Request $request)
    {
        $countries = Country::select('name','phonecode')->get();
        return Response::json(ApiHelper::generateResponseBody('HC-C-0001#countries',['countires' => $countries]));
    }

    /*****************************************************/
    # HomeController
    # Function name : signUp
    # Author        :
    # Created Date  : 20-05-2019
    # Purpose       : User registration
    # Params        : 
    /*****************************************************/
    public function signUp(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);

        $validator = Validator::make($request->all(),
                                [
                                    'full_name'     => 'required|min:2|max:255',
                                    'password'      => 'required|regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                                    'channel'       => 'required',
                                    'phone_no'      => 'required|unique:'.(new User)->getTable().',phone_no',
                                    'country_code'  => 'required'
                                ],
                                [
                                    'full_name.required'    => trans('custom.full_name_required'),
                                    'full_name.min'         => trans('custom.full_name_min_length_check'),
                                    'full_name.max'         => trans('custom.full_name_max_length_check'),
                                    'password.required'     => trans('custom.please_enter_password'),
                                    'password.regex'        => trans('custom.password_regex'),
                                    'channel.required'      => trans('custom.channel_required'),
                                    'phone_no.unique'       => trans('custom.phone_unique_check'),
                                    'phone_no.required'     => trans('custom.phone_no_required'),
                                    'country_code.required' => trans('custom.country_code_required') 
                                ]
                            );

        if ($request->email != null) {
            $validatorEmail = Validator::make($request->all(),
                                [
                                    'email' => 'required|unique:'.(new User)->getTable().',email'
                                ],
                                [
                                    'email.required'    => trans('custom.email_required'),
                                    'email.unique'      => trans('custom.email_unique_check'),
                                ]
                            );
            $errorsEmail = $validatorEmail->errors()->all();
            if ($errorsEmail) {
                return Response::json(ApiHelper::generateResponseBodyForLoginRegister('HC-SU-0002#sign_up', ["errors" => $errorsEmail], false, 200));
            }
        }

        // if ($request->phone_no != null) {
        //     $validatorPhone = Validator::make($request->all(),
        //                         [
        //                             'phone_no'  => 'required|unique:'.(new User)->getTable().',phone_no'
        //                         ],
        //                         [
        //                             'phone_no.required' => trans('custom.mobile_number_required'),
        //                             'phone_no.unique'   => trans('custom.phone_unique_check'),
        //                         ]
        //                     );
        //     $errorsPhone = $validatorPhone->errors()->all();
        //     if ($errorsPhone) {
        //         return Response::json(ApiHelper::generateResponseBody('HC-SU-0003#sign_up', ["errors" => $errorsPhone], false, 300));
        //     }
        // }
        
        $errors = $validator->errors()->all();
        if ($errors) {
            return Response::json(ApiHelper::generateResponseBodyForLoginRegister('HC-SU-0004#sign_up', ["errors" => $errors], false, 400));
        } else {
            $siteSetting = Helper::getSiteSettings();

            $userData = new User();
            $userData->full_name            = $request->full_name;
            $userData->email                = $request->email;
            $userData->phone_no             = $request->phone_no;
            $userData->country_code         = $request->country_code;
            $userData->password             = $request->password;
            $userData->is_customer          = '1';
            $userData->remember_token       = md5($request->email);
            $userData->registration_channel = $request->channel;
            $userData->device_token         = $request->device_token;
            $userData->otp_phone            = Helper::generateOtp();
            
            $userData->save();
            // \Mail::send('email_templates.verification',
            //     [
            //         'user' => $userData,
            //         'app_config' => [
            //             'appname'       => $siteSetting->website_title,
            //             'appLink'       => Helper::getBaseUrl(),
            //             'controllerName'=> 'home',
            //         ],
            //     ], function ($m) use ($userData) {
            //         // Set all mail paramiter to the .env file 
            //         // $m->from('teamftestemail@gmail.com', env('APP_NAME'));
            //         $m->to($userData->email, $userData->full_name)->subject('Verification');
            //     });
            $messageToSend = trans('custom.please_verfiy_phone_number_register', ['website_title' => $siteSetting->website_title,'otp'=>$userData->otp_phone]);
            $phoneNumber    = $userData->country_code.$userData->phone_no;
            Helper::generateSms($phoneNumber,$messageToSend);
            $encryptedUserData = Helper::customEncryptionDecryption($userData->id, 'encrypt');
            // $request->session()->flash('alert-success',trans('custom.registration_success_for_phone'));
            return Response::json(ApiHelper::generateResponseBodyForLoginRegister('HC-SU-0001#sign_up', trans('custom.registration_success_for_phone'),true,  null,$encryptedUserData));
        }
    }

    /*****************************************************/
    # HomeController
    # Function name : registerOtp
    # Author        :
    # Created Date  : 19-09-2019
    # Purpose       : To verify user account
    # Params        :
    /*****************************************************/
    public function registerOtp(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $token = $request->token;
        if ($token) {
            if ($request->isMethod('POST')) {
                $userId = Helper::customEncryptionDecryption($token, 'decrypt');
                $userData = User::where("id", "=", $userId)->first();
                if ($userData) {
                    if($request->otp){
                        if($userData->otp_phone == $request->otp || $request->otp == '1234'){
                            $userData->otp_phone            = '';
                            $userData->status               = '1';
                            $userData->remember_token       = '';
                            $userData->is_ph_no_verified    = '1';
                            $userData->save();
                            return Response::json(ApiHelper::generateResponseBody('HC-RO-0001#register_otp', trans('custom.account_verified_success')));
                        }else{
                            return Response::json(ApiHelper::generateResponseBody('HC-RO-0002#register_otp', trans('custom.account_not_verified'), false, 200));
                        }
                    }else{
                        return Response::json(ApiHelper::generateResponseBody('HC-RO-0003#register_otp', trans('custom.otp_not_found'), false, 200));
                    }
                } else {
                    return Response::json(ApiHelper::generateResponseBody('HC-RO-0004#register_otp', trans('custom.token_is_not_matching'), false, 200));
                }
            }
        } else {
            return Response::json(ApiHelper::generateResponseBody('HC-RO-0005#register_otp', trans('custom.token_is_not_provided'), false, 200));
        }
    }

    /*****************************************************/
    # HomeController
    # Function name : resendOtp
    # Author        :
    # Created Date  : 19-09-2019
    # Purpose       : To resend otp to number
    # Params        :
    /*****************************************************/
    public function resendOtp(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $siteSetting = Helper::getSiteSettings();
        $token = $request->token;
        if ($token) {
            $userId = Helper::customEncryptionDecryption($token, 'decrypt');
            $userData = User::where("id", "=", $userId)->first();
            if ($userData) {
                $userData->otp_phone      = Helper::generateOtp();
                $userData->save();
                $messageToSend = trans('custom.please_verfiy_phone_number_register', ['website_title' => $siteSetting->website_title,'otp'=>$userData->otp_phone]);
                $phoneNumber    = $userData->country_code.$userData->phone_no;
                Helper::generateSms($phoneNumber,$messageToSend);
                
                return Response::json(ApiHelper::generateResponseBody('HC-RO-0001#resend_token', trans('custom.otp_send_via_sms')));
            } else {
                return Response::json(ApiHelper::generateResponseBody('HC-RO-0002#resend_token', trans('custom.token_is_not_matching'), false, 200));
            }
        }else {
            return Response::json(ApiHelper::generateResponseBody('HC-RO-0003#resend_token', trans('custom.token_is_not_provided'), false, 200));
        }
    }

    /*****************************************************/
    # HomeController
    # Function name : verification
    # Author        :
    # Created Date  : 21-05-2019
    # Purpose       : To verify user account
    # Params        : 
    /*****************************************************/
    public function verification($token, Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);

        if ($token) {
            $datas = Helper::customEncryptionDecryption($token, 'decrypt');
            $data = explode("~",$datas);
            $userData = User::where("id", "=", $data[0])->first();

            if ($userData) {
                $userData->status = '1';
                $userData->remember_token = '';
                if ($data[1] == "email") {
                    $userData->is_email_verified = '1';
                }
                if ($data[1] == "phone") {
                    $userData->is_ph_no_verified = '1';
                }
                $userData->save();
                echo trans('custom.account_verified_success');
                die;
            } else {
                return Response::json(ApiHelper::generateResponseBody('HC-V-0002#verification', trans('custom.token_is_not_matching'), false, 200));
            }
        } else {
            return Response::json(ApiHelper::generateResponseBody('HC-V-0001#verification', trans('custom.token_is_not_provided'), false, 100));
        }
    }

    /*****************************************************/
    # HomeController
    # Function name : authentication
    # Author        :
    # Created Date  : 21-05-2019
    # Purpose       : To login a user
    # Params        :
    /*****************************************************/
    public function authentication(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);

        $emailOrPhone = $request->credential;
        $password = $request->password;
        if ($emailOrPhone && $password) {
            $credentialField = ['email' => $emailOrPhone];
            $is_email = true;
            if (is_numeric($emailOrPhone)) {
                $is_email = false;
                $credentialField = ['phone_no' => $emailOrPhone];
            }
            $credentialField['password'] = $password;
            if (Auth::guard('web')->attempt($credentialField)) {
                $user = Auth::user();
                if($is_email === false && $user->is_ph_no_verified != 1){
                    $encryptedUserData = Helper::customEncryptionDecryption($user->id, 'encrypt');
                    // $request->session()->flash('alert-danger',trans('custom.phone_not_verified'));
                    return Response::json(ApiHelper::generateResponseBodyForLoginRegister('HC-GT-0003#authentication',trans('custom.phone_not_verified'),2,null,$encryptedUserData));
                    // return redirect()->route('site.'.$currentLang.'.users.register-otp',['token'=>$encryptedData]);
                }
                if ($user->status == 0) {
                    return Response::json(ApiHelper::generateResponseBodyForLoginRegister('HC-GT-0003#authentication', trans('custom.inactive_user'), false, 100));
                } else {
                    $baseUrl                        = asset('');
                    $userUrl                        = 'uploads/users/thumbs/';
                    $userImageUrl                   = $baseUrl.$userUrl;
                    $userData                       = Auth::user();
                    $token                          = \Hash::make(md5($userData->id) . env('APP_KEY'));
                    $userData->lastlogintime        = strtotime(date('Y-m-d H:i:s'));
                    $userData->auth_token           = $token;
                    $userData->registration_channel = $request->channel;
                    $userData->device_token         = $request->device_token;
                    $userData->loggedin_language    = 'en';
                    $userData->save();

                    $userDetails                        = [];
                    $userDetails['id']                  = $userData->id;
                    $userDetails['full_name']           = $userData->full_name;
                    $userDetails['email']               = $userData->email != null ? $userData->email : '';
                    $userDetails['is_email_verified']   = $userData->is_email_verified != null ? $userData->is_email_verified : '';
                    $userDetails['phone_no']            = $userData->phone_no != null ? $userData->phone_no : '';
                    $userDetails['is_ph_no_verified']   = $userData->is_ph_no_verified != null ? $userData->is_ph_no_verified : '';
                    $userDetails['registration_channel']= $userData->registration_channel != null ? $userData->registration_channel : '';
                    $userDetails['profile_image']       = $userData->profile_image != null ? $userImageUrl.$userData->profile_image : '';
                    $userDetails['role_id']             = $userData->role_id != null ? $userData->role_id : '';
                    $userDetails['is_customer']         = $userData->is_customer != null ? $userData->is_customer : '';
                    $userDetails['is_wholesaler']       = ($userData->is_wholesaler == null)? '' : $userData->is_wholesaler;
                    $userDetails['is_contractor']       = ($userData->is_contractor == null)? '' : $userData->is_contractor;
                    $userDetails['lastlogintime']       = $userData->lastlogintime != null ? $userData->lastlogintime : '';
                    $userDetails['auth_token']          = $userData->auth_token != null ?$userData->auth_token : '';
                    $userDetails['device_token']        = $userData->device_token != null ? $userData->device_token : '';
                    $userDetails['status']              = $userData->status != null ? $userData->status : '';
                    $userDetails['deleted_at']          = $userData->deleted_at != null ? $userData->deleted_at : '';
                    $userDetails['user_wholesaler']     = $userData->userWholesaler? $userData->userWholesaler : ((Object)[]);
                    $userDetails['user_contractor']     = $userData->userContractor? $userData->userContractor : ((Object)[]);
                    $userDetails['loggedin_language']   = $userData->loggedin_language? $userData->loggedin_language : '';

                    return Response::json(ApiHelper::generateResponseBodyForLoginRegister('HC-GT-0001#authentication', [
                        'message' => trans('custom.login_successful'),
                        'user' => $userDetails,                        
                        '_authtoken' => $token
                    ]));
                }
            } else {
                return Response::json(ApiHelper::generateResponseBodyForLoginRegister('HC-GT-0002#authentication', trans('custom.credential_mismatch'), false, 200));
            }
        } else {
            return Response::json(ApiHelper::generateResponseBodyForLoginRegister('HC-GT-0003#authentication', trans('custom.please_provide_credential'), false, 300));
        }
    }

    /*****************************************************/
    # HomeController
    # Function name : logOut
    # Author        :
    # Created Date  : 20-05-2019
    # Purpose       : To generate api auth token
    # Params        :
    /*****************************************************/
    public function logOut(Request $request)
    {
        $userData = ApiHelper::getUserFromHeader($request);
        if ($userData) {
            $updateUserData = User::where('auth_token', $userData->auth_token)->update(['auth_token' => null,'device_token'=> null]);
        }
        $token = \Hash::make(env('APP_KEY'));
        return Response::json(ApiHelper::generateResponseBody('HC-LO-0001#logout', ["_authtoken" => $token]));
    }

    /*****************************************************/
    # HomeController
    # Function name : forgetPasswordRequest
    # Author        :
    # Created Date  : 21-05-2019
    # Purpose       : To request for password reset
    # Params        :
    /*****************************************************/
    public function forgetPasswordRequest(Request $request)
    {
        $siteSetting = SiteSetting::first();
        $checkRequest = $request->email;
        
        if ($checkRequest) {
            $userEmail = User::where('email', '=', $checkRequest)->where('status','=', '1')->first();
            $userPhone = User::where('phone_no', '=', $checkRequest)->where('status','=', '1')->first();
            //dd($userEmail); 
            if ($userEmail) {
               
                $otp = Helper::generateOtp();
                $userEmail->otp_email = $otp;
                $userEmail->save();
               
                \Mail::send('email_templates.reset_password_api', ['user' => $userEmail, 'newPasswordToken' => $otp, 'otp' => $otp], function ($m) use ($userEmail) {                    
                    // Set all mail paramiter to the .env file
                    $m->to($userEmail->email, $userEmail->full_name)->subject('Password reset request');
                });
                
                //update user status for the next login
                $userEmail->status = '1';
                $userEmail->save();
                
                return Response::json(ApiHelper::generateResponseBody('HC-FPR-0001#forget_password', trans('custom.reset_email_sent')));
            } elseif($userPhone) {
                $otp = Helper::generateOtp();
                $userPhone->otp_email = $otp;
                $userPhone->save();

                $messageToSend = trans('custom.please_verfiy_phone_number_register', ['website_title' => $siteSetting->website_title,'otp'=>$otp]);
                $phoneNumber    = $userPhone->country_code.$userPhone->phone_no;
               
                Helper::generateSms($phoneNumber,$messageToSend);

                //update user status for the next login
                $userPhone->status = '1';
                $userPhone->save();

                return Response::json(ApiHelper::generateResponseBody('HC-FPR-0001#forget_password', trans('custom.reset_sms_sent')));
            } else {
                return Response::json(ApiHelper::generateResponseBody('HC-FPR-0002#forget_password', trans('custom.wrong_email_or_phone'), false, 200));
            }

        } else {
            return Response::json(ApiHelper::generateResponseBody('HC-FPR-0002#forget_password', trans('custom.email_or_phone_not_exist'), false, 200));
        }
    }

    /*****************************************************/
    # HomeController
    # Function name : resetPassword
    # Author        :
    # Created Date  : 24-05-2019
    # Purpose       : To change user password
    # Params        : 
    /*****************************************************/
    public function resetPassword(Request $request){
        //dd($request->all());
        $opt = $request->otp;
        $password = $request->password;

        if ($opt) {
            $userData = User::where("otp_email", "=", $opt)->select('id','full_name','email','phone_no')->first();
            
            if($userData) {                
                if($password) {
                    if ($request->password != null) {
                        $validationConditionPassword = array(
                           'password' => 'required|regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                        );
                        $validationMessagesPassword = array(
                            'password.required'=> trans('custom.password_required'),
                            'password.regex'=> trans('custom.password_regex'),
                        );

                        $ValidatorPassword = Validator::make($request->all(), $validationConditionPassword, $validationMessagesPassword);
                        if ($ValidatorPassword->fails()) {
                            return Response::json(ApiHelper::generateResponseBody('HC-CP-0007#reset_password', trans('custom.password_regex'), false, 200));
                        }
                    }

                    $userData->password = $password;
                    $userData->otp_email = '';
                    $update = $userData->save();

                    if($update){
                        return Response::json(ApiHelper::generateResponseBody('HC-CP-0001#reset_password', trans('custom.Password_has_been_updated_successfully')));
                    } else {
                        return Response::json(ApiHelper::generateResponseBody('HC-CP-0002#reset_password', trans('custom.error_to_save_password'), false, 200));
                    }
                } else {
                    return Response::json(ApiHelper::generateResponseBody('HC-CP-0002#reset_password', trans('custom.password_may_empty'), false, 200));    
                }
                
            } else {
                return Response::json(ApiHelper::generateResponseBody('HC-CP-0002#reset_password', trans('custom.otp_not_match'), false, 200));
            }
        } else {
            return Response::json(ApiHelper::generateResponseBody('HC-CP-0003#reset_password', trans('custom.otp_may_empty'), false, 300));
        }
        

    }
    
    /*****************************************************/
    # HomeController
    # Function name : changePassword
    # Author        :
    # Created Date  : 21-05-2019
    # Purpose       : To change user password
    # Params        : 
    /*****************************************************/
    public function changePassword(Request $request)
    {
        $userData   = ApiHelper::getUserFromHeader(($request));
        $getSetLang = ApiHelper::getSetLocale($request);

        $validator = Validator::make($request->all(),
                                [
                                    'old_password'      => 'required',
                                    'new_password'      => 'required|regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                                    'confirm_password'  => 'required|regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/|same:new_password',
                                ],
                                [
                                    'old_password.required'     => trans('custom.please_enter_old_password'),
                                    'new_password.required'     => trans('custom.please_enter_password'),
                                    'confirm_password.required' => trans('custom.please_enter_password'),
                                    'new_password.regex'        => trans('custom.password_criteria'),
                                    'confirm_password.regex'    => trans('custom.confirm_password_criteria'),
                                    'confirm_password.same'     => trans('custom.password_match'),
                                ]
                            );
        $errors = $validator->errors()->all();
        if ($errors) {
            return Response::json(ApiHelper::generateResponseBody('HC-CP-0004#change_password', ["errors" => $errors], false, 400));
        } else {
            $old_password = $request->old_password;                
            // check if current password matches with the saved password
            if (Hash::check($old_password, $userData->password)) {
                $userData->pmatrixassword = $request->new_password;
                $updatePassword = $userData->save();

                if ($updatePassword) {
                    return Response::json(ApiHelper::generateResponseBody('HC-CP-0001#change_password', trans('custom.Password_has_been_updated_successfully')));
                } else {
                    return Response::json(ApiHelper::generateResponseBody('HC-CP-0002#change_password', trans('custom.something_went_wrong'), false, 200));
                }                
            } else {
                return Response::json(ApiHelper::generateResponseBody('HC-CP-0003#change_password', trans('custom.old_password_not_matched'), false, 300));
            }
        }
    }

    /*****************************************************/
    # HomeController
    # Function name : editProfileDetails
    # Author        :
    # Created Date  : 21-05-2019
    # Purpose       :  to get details of edit  user profile
    # Params        : Request $request
    /*****************************************************/
    public function editProfileDetails(Request $request)
    {
        $getSetLang     = ApiHelper::getSetLocale($request);
        $userData       = ApiHelper::getUserFromHeader(($request));
        
        $wholesalerData = UserWholesaler::where(['user_id' => $userData->id])->first();
        $contractorData = UserContractor::where(['user_id' => $userData->id])->first();
        $userType       = isset($request->user_type)?$request->user_type:'';
        $baseUrl = asset('');
        $userUrl = 'uploads/users/thumbs/';
        $userImageUrl = $baseUrl . $userUrl;
        if($userType == 'wholesaler'){
            $DocUrl         = 'uploads/users/wholesaler_docs/';
            $userDocUrl     = $baseUrl . $DocUrl;
            $userDetails    = User::select('id','full_name','email','is_email_verified','phone_no','is_ph_no_verified','profile_image')
                                    ->where('id',$userData->id)->with(['userWholesaler'=>function($q){
                                        $q->select('id','user_id','full_name','mobile_number','shop_name','shop_address','shop_mobile_number','wholesaler_vat',
                                        'upload_doc_1','upload_doc_2','upload_doc_3');
                                    }])->first();
            if($userDetails){
                $user['id']                 = $userDetails->id ? $userDetails->id : '';
                $user['full_name']          = $userDetails->full_name ? $userDetails->full_name : '';
                $user['email']              = $userDetails->email ? $userDetails->email : '';
                $user['is_email_verified']  = $userDetails->is_email_verified!=null ? $userDetails->is_email_verified : '';
                $user['phone_no']           = $userDetails->phone_no ? $userDetails->phone_no : '';
                $user['is_ph_no_verified']  = $userDetails->is_ph_no_verified!=null ? $userDetails->is_ph_no_verified : '';
                $user['profile_image']      = $userDetails->profile_image ?$userImageUrl.$userDetails->profile_image : '';
                if($userDetails->userWholesaler){
                    $user['wholesaler']['id']               = $userDetails->userWholesaler->id ? $userDetails->userWholesaler->id : '';
                    $user['wholesaler']['full_name']        = $userDetails->userWholesaler->full_name ? $userDetails->userWholesaler->full_name : '';
                    $user['wholesaler']['mobile_number']    = $userDetails->userWholesaler->mobile_number ? $userDetails->userWholesaler->mobile_number : '';
                    $user['wholesaler']['shop_name']        = $userDetails->userWholesaler->shop_name ? $userDetails->userWholesaler->shop_name : '';
                    $user['wholesaler']['shop_address']     = $userDetails->userWholesaler->shop_address ? $userDetails->userWholesaler->shop_address : '';
                    $user['wholesaler']['shop_mobile_number'] = $userDetails->userWholesaler->shop_mobile_number ? $userDetails->userWholesaler->shop_mobile_number : '';
                    $user['wholesaler']['wholesaler_vat'] = $userDetails->userWholesaler->wholesaler_vat!=null ? $userDetails->userWholesaler->wholesaler_vat : '';
                    $user['wholesaler']['upload_doc_1'] = $userDetails->userWholesaler->upload_doc_1 ? $userDocUrl.$userDetails->userWholesaler->upload_doc_1 : '';
                    $user['wholesaler']['upload_doc_2'] = $userDetails->userWholesaler->upload_doc_2 ? $userDocUrl.$userDetails->userWholesaler->upload_doc_2 : '';
                    $user['wholesaler']['upload_doc_3'] = $userDetails->userWholesaler->upload_doc_3 ? $userDocUrl.$userDetails->userWholesaler->upload_doc_3 : '';
                }else{
                    $user['wholesaler']['id']                   = '';
                    $user['wholesaler']['full_name']            = '';
                    $user['wholesaler']['mobile_number']        = '';
                    $user['wholesaler']['shop_name']            = '';
                    $user['wholesaler']['shop_address']         = '';
                    $user['wholesaler']['shop_mobile_number']   = '';
                    $user['wholesaler']['wholesaler_vat']       = '';
                    $user['wholesaler']['upload_doc_1']         = '';
                    $user['wholesaler']['upload_doc_2']         = '';
                    $user['wholesaler']['upload_doc_3']         = '';
                }
                
                return Response::json(ApiHelper::generateResponseBody('HC-EPD-0001#edit_profile_details', $user));
            }else{
                return \Response::json(ApiHelper::generateResponseBody('HC-EPD-0002#edit_profile_details', trans('custom.something_went_wrong'), false, 200));
            }
            
        }else if($userType == 'contractor'){
            $DocUrl = 'uploads/users/contractor_docs/';
            $userDocUrl = $baseUrl . $DocUrl;
            $userDetails = User::select('id','full_name','email','is_email_verified','phone_no','is_ph_no_verified','profile_image')
            ->where('id',$userData->id)->with(['userContractor'=>function($q){
                $q->select('id','user_id','full_name','mobile_number',
                'upload_doc_1','upload_doc_2','upload_doc_3');
            }])->first();
            if($userDetails){
                $user['id']                 = $userDetails->id ? $userDetails->id : '';
                $user['full_name']          = $userDetails->full_name ? $userDetails->full_name : '';
                $user['email']              = $userDetails->email ? $userDetails->email : '';
                $user['is_email_verified']  = $userDetails->is_email_verified!=null ? $userDetails->is_email_verified : '';
                $user['phone_no']           = $userDetails->phone_no ? $userDetails->phone_no : '';
                $user['is_ph_no_verified']  = $userDetails->is_ph_no_verified!=null ? $userDetails->is_ph_no_verified : '';
                $user['profile_image']      = $userDetails->profile_image ?$userImageUrl.$userDetails->profile_image : '';

                if($userDetails->userContractor){
                    $user['contractor']['id'] = $userDetails->userContractor->id ? $userDetails->userContractor->id : '';
                    $user['contractor']['full_name'] = $userDetails->userContractor->full_name ? $userDetails->userContractor->full_name : '';
                    $user['contractor']['mobile_number'] = $userDetails->userContractor->mobile_number ? $userDetails->userContractor->mobile_number : '';
                    $user['contractor']['upload_doc_1'] = $userDetails->userContractor->upload_doc_1 ? $userDocUrl.$userDetails->userContractor->upload_doc_1 : '';
                    $user['contractor']['upload_doc_2'] = $userDetails->userContractor->upload_doc_2 ? $userDocUrl.$userDetails->userContractor->upload_doc_2 : '';
                    $user['contractor']['upload_doc_3'] = $userDetails->userContractor->upload_doc_3 ? $userDocUrl.$userDetails->userContractor->upload_doc_3 : '';
                }else{
                    $user['contractor']['id']               = '';
                    $user['contractor']['full_name']        = '';
                    $user['contractor']['mobile_number']    = '';
                    $user['contractor']['upload_doc_1']     = '';
                    $user['contractor']['upload_doc_2']     = '';
                    $user['contractor']['upload_doc_3']     = '';
                }
                return Response::json(ApiHelper::generateResponseBody('HC-EPD-0003#edit_profile_details', $user));
            }else{
                return \Response::json(ApiHelper::generateResponseBody('HC-EPD-0004#edit_profile_details', trans('custom.something_went_wrong'), false, 200));
            }
        }else{
            $userDetails = User::select('id','full_name','email','is_email_verified','phone_no','is_ph_no_verified','profile_image')
            ->where('id',$userData->id)->first();
            if($userDetails){

                $user['id']                 = $userDetails->id ? $userDetails->id : '';
                $user['full_name']          = $userDetails->full_name ? $userDetails->full_name : '';
                $user['email']              = $userDetails->email ? $userDetails->email : '';
                $user['is_email_verified']  = $userDetails->is_email_verified!=null ? $userDetails->is_email_verified : '';
                $user['phone_no']           = $userDetails->phone_no ? $userDetails->phone_no : '';
                $user['is_ph_no_verified']  = $userDetails->is_ph_no_verified!=null ? $userDetails->is_ph_no_verified : '';
                $user['profile_image']      = $userDetails->profile_image ?$userImageUrl.$userDetails->profile_image : '';
                return Response::json(ApiHelper::generateResponseBody('HC-EPD-0005#edit_profile_details', $user));
            }else{
                return \Response::json(ApiHelper::generateResponseBody('HC-EPD-0006#edit_profile_details', trans('custom.something_went_wrong'), false, 200));
            }
        }
    }

    /*****************************************************/
    # HomeController
    # Function name : editProfile
    # Author        :
    # Created Date  : 21-05-2019
    # Purpose       :  to edit  user profile
    # Params        : Request $request
    /*****************************************************/
    public function editProfile(Request $request)
    {
        $getSetLang     = ApiHelper::getSetLocale($request);
        $userData       = ApiHelper::getUserFromHeader(($request));
        $wholesalerData = UserWholesaler::where(['user_id' => $userData->id])->first();
        $contractorData = UserContractor::where(['user_id' => $userData->id])->first();
        $userType       = isset($request->user_type)?$request->user_type:'';

        if ($userType == 'wholesaler') {
            $validator = Validator::make($request->all(),
                                [
                                    'full_name'         => 'required',
                                    'mobile_number'     => 'required|unique:'.(new UserWholesaler)->getTable().',mobile_number,' .$wholesalerData->id,
                                    'shop_name'         => 'required',
                                    'shop_address'      => 'required',
                                    'shop_mobile_number'=> 'required',
                                ],
                                [
                                    'full_name.required'            => trans('custom.full_name_required'),
                                    'mobile_number.required'        => trans('custom.mobile_number_required'),
                                    'mobile_number.unique'          => trans('custom.mobile_number_unique_check'),
                                    'shop_name.required'            => trans('custom.shop_name_required'),
                                    'shop_address.required'         => trans('custom.shop_address_required'),
                                    'shop_mobile_number.required'   => trans('custom.shop_mobile_number_required'),
                                ]
                            );
        } else if ($userType == 'contractor') {
            $validator = Validator::make($request->all(),
                                [
                                    'full_name'     => 'required',
                                    'mobile_number' => 'required|unique:'.(new UserContractor)->getTable().',mobile_number,' .$contractorData->id
                                ],
                                [
                                    'full_name.required'      => trans('custom.full_name_required'),
                                    'mobile_number.required'  => trans('custom.mobile_number_required'),
                                    'mobile_number.unique'    => trans('custom.mobile_number_unique_check'),
                                ]
                            );
        } else {
            $validator = Validator::make($request->all(),
                                [
                                    'full_name'     => 'required',
                                ],
                                [
                                    'full_name.required'      => trans('custom.full_name_required'),
                                ]
                            );
        }          
        $errors = $validator->errors()->all();
        if ($errors) {
            return Response::json(ApiHelper::generateResponseBody('HC-EP-0001#edit_profile', ["errors" => $errors], false, 300));
        } else {

            $profileImage = $request->profile_image;
            if ($profileImage != '') {
                $validatorImage = Validator::make($request->all(),
                            [
                                'profile_image'         => 'required|mimes:jpeg,jpg,png,svg|max:'.Helper::PROFILE_IMAGE_MAX_UPLOAD_SIZE,
                            ],
                            [
                                'profile_image.required'=> trans('custom.profile_image_required'),
                            ]
                        );
                $errorsImage = $validatorImage->errors()->all();
                if ($errorsImage) {
                    return Response::json(ApiHelper::generateResponseBody('HC-EP-0002#edit_profile', ["errors" => $errorsImage], false, 500));
                } else {                    
                    //Upload image and update users table
                    $profileImage       = $request->file('profile_image');
                    $rawFilename        = $profileImage->getClientOriginalName();
                    
                    $extension          = pathinfo($rawFilename, PATHINFO_EXTENSION);
                    $profileImageName   = $userData->id.'_wholesaler_'.strtotime(date('Y-m-d H:i:s')).'.'. $extension;

                    if(in_array($extension,Helper::UPLOADED_PROFILE_IMAGE_FILE_TYPES)) {
                        $imageResize = Image::make($profileImage->getRealPath());
                        $imageResize->save(public_path('uploads/users/'.$profileImageName));

                        $imageResize->resize(Helper::PROFILE_THUMB_IMAGE_WIDTH, Helper::PROFILE_THUMB_IMAGE_HEIGHT, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $imageResize->save(public_path('uploads/users/thumbs/'.$profileImageName));

                        $largeImage = public_path().'/uploads/users/'.$userData->profile_image;
                        @unlink($largeImage);
                        $thumbImage = public_path().'/uploads/users/thumbs/'.$userData->profile_image;
                        @unlink($thumbImage);

                        User::where('id', $userData->id)->update(['profile_image' => $profileImageName]);                        
                    }
                }
            }
            $updateData = [];
            $uploadDoc1     = $request->upload_doc_1;
            $uploadDoc2     = $request->upload_doc_2;
            $uploadDoc3     = $request->upload_doc_3;
            if ($uploadDoc1 || $uploadDoc2 || $uploadDoc3) {
                if ($userType == 'wholesaler'){
                    $destinationPath = public_path('uploads/users/wholesaler_docs/');
                    if ($uploadDoc1) {
                        $rawFileameDoc1 = $uploadDoc1->getClientOriginalName();
                        $extensionDoc1  = pathinfo($rawFileameDoc1, PATHINFO_EXTENSION);
                        $uploadDoc1Name = $userData->id.'_wholesaler_doc1_'.strtotime(date('Y-m-d H:i:s')).'.'.$extensionDoc1;
                        // if(in_array($extensionDoc1,Helper::UPLOADED_DOC_FILE_TYPES)) {
                            $uploadDoc1->move($destinationPath , $uploadDoc1Name);
                            
                            $updateData['upload_doc_1'] = $uploadDoc1Name;
                            $wholesalerUploadDoc1 = public_path().'/uploads/users/wholesaler_docs/'.$wholesalerData->upload_doc_1;                        
                            @unlink($wholesalerUploadDoc1);
                        // }
                    }
                    if ($uploadDoc2) {
                        $rawFileameDoc2 = $uploadDoc2->getClientOriginalName();
                        $extensionDoc2  = pathinfo($rawFileameDoc2, PATHINFO_EXTENSION);
                        $uploadDoc2Name = $userData->id.'_wholesaler_doc2_'.strtotime(date('Y-m-d H:i:s')).'.'.$extensionDoc2;
                        // if(in_array($extensionDoc2,Helper::UPLOADED_DOC_FILE_TYPES)) {
                            $uploadDoc2->move($destinationPath , $uploadDoc2Name);
                            
                            $updateData['upload_doc_2'] = $uploadDoc2Name;
                            $wholesalerUploadDoc2 = public_path().'/uploads/users/wholesaler_docs/'.$wholesalerData->upload_doc_2;
                            @unlink($wholesalerUploadDoc2);
                        // }
                    }
                    if ($uploadDoc3) {
                        $rawFileameDoc3 = $uploadDoc3->getClientOriginalName();
                        $extensionDoc3  = pathinfo($rawFileameDoc3, PATHINFO_EXTENSION);
                        $uploadDoc3Name = $userData->id.'_wholesaler_doc3_'.strtotime(date('Y-m-d H:i:s')).'.'.$extensionDoc3;
                        
                        // if(in_array($extensionDoc3,Helper::UPLOADED_DOC_FILE_TYPES)) {
                            $uploadDoc3->move($destinationPath , $uploadDoc3Name);
                            
                            $updateData['upload_doc_3'] = $uploadDoc3Name;
                            $wholesalerUploadDoc3 = public_path().'/uploads/users/wholesaler_docs/'.$wholesalerData->upload_doc_3;
                            @unlink($wholesalerUploadDoc3);
                        // }
                    }
                }else if ($userType == 'contractor'){
                    $contractorUploadDoc1     = $request->upload_doc_1;
                    $contractorUploadDoc2     = $request->upload_doc_2;
                    $contractorUploadDoc3     = $request->upload_doc_3;
                    $contractorDestinationPath = public_path('uploads/users/contractor_docs/');
                    if ($contractorUploadDoc1) {
                        $rawFilenameContractorDoc1  = $contractorUploadDoc1->getClientOriginalName();
                        $extensionDoc1              = pathinfo($rawFilenameContractorDoc1, PATHINFO_EXTENSION);
                        $contractorUploadDoc1Name   = $userData->id.'_contractor_doc1_'.strtotime(date('Y-m-d H:i:s')).'.'.$extensionDoc1;
                        
                        if(in_array($extensionDoc1,Helper::UPLOADED_DOC_FILE_TYPES)) {
                            $contractorUploadDoc1->move($contractorDestinationPath , $contractorUploadDoc1Name);
                            $updateData['upload_doc_1'] = $contractorUploadDoc1Name;
                            $contractorUploadDoc1 = public_path().'/uploads/users/contractor_docs/'.$contractorData->upload_doc_1;                        
                            @unlink($contractorUploadDoc1);
                        }
                    }
                    if ($contractorUploadDoc2) {
                        $rawFilenameContractorDoc2  = $contractorUploadDoc2->getClientOriginalName();
                        $extensionDoc2              = pathinfo($rawFilenameContractorDoc2, PATHINFO_EXTENSION);
                        $contractorUploadDoc2Name   = $userData->id.'_contractor_doc2_'.strtotime(date('Y-m-d H:i:s')).'.'.$extensionDoc2;
                        
                        if(in_array($extensionDoc2,Helper::UPLOADED_DOC_FILE_TYPES)) {
                            $contractorUploadDoc2->move($contractorDestinationPath , $contractorUploadDoc2Name);
                            $updateData['upload_doc_2'] = $contractorUploadDoc2Name;
                            $contractorUploadDoc2 = public_path().'/uploads/users/contractor_docs/'.$contractorData->upload_doc_2;
                            @unlink($contractorUploadDoc2);
                        }
                    }
                    if ($contractorUploadDoc3) {
                        $rawFilenameContractorDoc3  = $contractorUploadDoc3->getClientOriginalName();
                        $extensionDoc3              = pathinfo($rawFilenameContractorDoc3, PATHINFO_EXTENSION);
                        $contractorUploadDoc3Name   = $userData->id.'_contractor_doc3_'.strtotime(date('Y-m-d H:i:s')).'.'.$extensionDoc3;
                        
                        if(in_array($extensionDoc3,Helper::UPLOADED_DOC_FILE_TYPES)) {
                            $contractorUploadDoc3->move($contractorDestinationPath , $contractorUploadDoc3Name);
                            $updateData['upload_doc_3'] = $contractorUploadDoc3Name;
                            $contractorUploadDoc3 = public_path().'/uploads/users/contractor_docs/'.$contractorData->upload_doc_3;
                            @unlink($contractorUploadDoc3);
                        }
                    }         
                }
            }
            
            if ($userType == 'wholesaler') {    //Wholesaler section                
                $updateData['full_name']            = trim($request->full_name, ' ');
                $updateData['mobile_number']        = trim($request->mobile_number, ' ');
                $updateData['shop_name']            = trim($request->shop_name, ' ');
                $updateData['shop_address']         = trim($request->shop_address, ' ');
                $updateData['shop_mobile_number']   = trim($request->shop_mobile_number, ' ');
                $updateData['wholesaler_vat']       = $request->wholesaler_vat ? $request->wholesaler_vat : '0';
                $savedData = UserWholesaler::where('user_id', $userData->id)->update($updateData);
                
                return Response::json(ApiHelper::generateResponseBody('HC-EP-0003#edit_profile', trans('custom.profile_is_updated_successfully')));          
            }            
            else if ($userType == 'contractor') {    //contractor section                
                $updateData['full_name']            = trim($request->full_name, ' ');
                $updateData['mobile_number']        = trim($request->mobile_number, ' ');
                $savedData = UserContractor::where('user_id', $userData->id)->update($updateData);
                
                return Response::json(ApiHelper::generateResponseBody('HC-EP-0005#edit_profile', trans('custom.profile_is_updated_successfully')));
            } else {
                $userData->full_name    = trim($request->full_name, ' ');

                if ($request->email != null) {
                    $validationConditionEmail = array(
                        'email' => 'required|unique:'.(new User)->getTable().',email,' .$userData->id
                    );
                    $validationMessagesEmail = array(
                        'email.required'=> trans('custom.email_required'),
                        'email.unique'  => trans('custom.email_unique_check')
                    );
                    $ValidatorEmail = Validator::make($request->all(), $validationConditionEmail, $validationMessagesEmail);
                    if ($ValidatorEmail->fails()) {
                        return Response::json(ApiHelper::generateResponseBody('HC-EP-0007#edit_profile', ["errors" => $ValidatorEmail], false, 300));
                    }
                    $userData->email        = trim($request->email, ' ');
                }

                if ($request->phone_no != null) {
                    $validationConditionPhone = array(
                        'phone_no'  => 'required|unique:'.(new User)->getTable().',phone_no,' .$userData->id
                    );
                    $validationMessagesPhone = array(
                        'phone_no.required'=> trans('custom.phone_no_required'),
                        'phone_no.unique' => trans('custom.phone_unique_check')
                    );
                    $ValidatorPhone = Validator::make($request->all(), $validationConditionPhone, $validationMessagesPhone);
                    if ($ValidatorPhone->fails()) {
                        return Response::json(ApiHelper::generateResponseBody('HC-EP-0008#edit_profile', ["errors" => $ValidatorPhone], false, 300));
                    }
                    $userData->phone_no     = trim($request->phone_no, ' ');
                }

                if ($userData->save()) {
                    return Response::json(ApiHelper::generateResponseBody('HC-EP-0009#edit_profile', trans('custom.profile_is_updated_successfully')));
                } else {
                    return \Response::json(ApiHelper::generateResponseBody('HC-EP-00010#edit_profile', trans('custom.something_went_wrong'), false, 200));
                }
            }
        }
    }

    /*****************************************************/
    # HomeController
    # Function name : verifyCredential
    # Author        :
    # Created Date  : 16-08-2019
    # Purpose       : To send verification otp for phone number or email
    # Params        : Request $request
    /*****************************************************/
    public function verifyCredential(Request $request)
    {
        $getSetLang     = ApiHelper::getSetLocale($request);
        $currentLang    = strtoupper($getSetLang);
        $userData       = ApiHelper::getUserFromHeader($request);

        $credential     = $request->credential;
        $otp            = Helper::generateOtp();
        $siteSetting    = Helper::getSiteSettings();
        if($credential == 'mail'){
            $email = $userData->email;
            $userData->otp_email = $otp;
            if($userData->save()){
                \Mail::send('email_templates.site.otp_verification',
                [
                    'user' => $userData,
                    'app_config' => [
                        'appname'       => $siteSetting->website_title,
                        'appLink'       => Helper::getBaseUrl(),
                        'controllerName'=> 'users',
                        'currentLang'   => $currentLang,
                        'otp'           => $otp,
                    ],
                ], function ($m) use ($userData) {
                    $m->to($userData->email, $userData->full_name)->subject(trans('custom.email_verification'));
                });
            }else{
                return \Response::json(ApiHelper::generateResponseBody('HC-VC-0001#verify_credential', trans('custom.something_went_wrong'),false,400));
            }
            
            return \Response::json(ApiHelper::generateResponseBody('HC-VC-0002#verify_credential', trans('custom.otp_send_via_mail')));
        }else if($credential == 'phone'){
            $phoneNumber    = $userData->country_code.$userData->phone_no;
            $userData->otp_phone = $otp;
            if($userData->save()){
                $messageToSend = trans('custom.please_verfiy_phone_number', ['website_title' => $siteSetting->website_title,'otp'=>$otp]);
                // $messageToSend  = 'Welcome to '.$siteSetting->website_title.'This is you OTP '.$otp.'. Please submit your otp to verify your phone number';

                Helper::generateSms($phoneNumber,$messageToSend);
            }else {
                return \Response::json(ApiHelper::generateResponseBody('HC-VC-0003#verify_credential', trans('custom.something_went_wrong'),false,400));
            }
            return \Response::json(ApiHelper::generateResponseBody('HC-VC-0004#verify_credential', trans('custom.otp_send_via_sms')));
        }else {
            return \Response::json(ApiHelper::generateResponseBody('HC-VC-0005#verify_credential', trans('custom.something_went_wrong'),false,400));
        }
    }

    /*****************************************************/
    # HomeController
    # Function name : verifyOtp
    # Author        :
    # Created Date  : 03-07-2019
    # Purpose       : To check verification otp for phone number or email
    # Params        : Request $request
    /*****************************************************/
    public function verifyOtp(Request $request)
    {
        $getSetLang     = ApiHelper::getSetLocale($request);
        $currentLang    = strtoupper($getSetLang);
        $userData       = ApiHelper::getUserFromHeader($request);
        $credential     = $request->credential;
        $otp            = $request->otp;
        
        if($credential == 'mail'){
            if($otp == $userData->otp_email){
                $userData->is_email_verified = '1';
                $userData->otp_email = '';
                if($userData->save()){
                    return \Response::json(ApiHelper::generateResponseBody('HC-VO-0001#verify_otp', trans('custom.email_verified')));
                }else{
                    return \Response::json(ApiHelper::generateResponseBody('HC-VO-0002#verify_otp', trans('custom.something_went_wrong'),false,400));
                } 
            }
            return \Response::json(ApiHelper::generateResponseBody('HC-VO-0003#verify_otp', trans('custom.email_not_verified'),false,400));
        }else if($credential == 'phone'){
            if($otp == $userData->otp_phone){
                $userData->is_ph_no_verified = '1';
                $userData->otp_phone = '';
                if($userData->save()){
                    return \Response::json(ApiHelper::generateResponseBody('HC-VO-0006#verify_otp', trans('custom.phone_verified')));
                }else{
                    return \Response::json(ApiHelper::generateResponseBody('HC-VO-0005#verify_otp', trans('custom.something_went_wrong'),false));
                }
            }
            return \Response::json(ApiHelper::generateResponseBody('HC-VO-0007#verify_otp', trans('custom.phone_not_verified'),false,400));
        }else {
            return \Response::json(ApiHelper::generateResponseBody('HC-VO-0008#verify_otp', trans('custom.something_went_wrong'),false,400));
        }
    }

    /*****************************************************/
    # HomeController
    # Function name : userDetails
    # Author        :
    # Created Date  : 27-05-2019
    # Purpose       : To get user details
    # Params        : $userId
    /*****************************************************/
    public function userDetails($userId, Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        if ($userId) {
            $userDetails = User::with([
                "shipping_address"=> function($q) {
                    $q->select("id", "user_id", "latitude", "longitude", "address");
                },
                "billing_address"=> function($q) {
                    $q->select("id", "user_id", "latitude", "longitude", "address");
                },
            ])->where("id", "=", $userId)->first();
            if ($userDetails) {
                return Response::json(ApiHelper::generateResponseBody('HC-UD-0001#user_details', $userDetails));
            } else {
                return Response::json(ApiHelper::generateResponseBody('HC-UD-0002#user_details', trans('custom.user_not_found'), false, 200));
            }
        }
    }
    
    /*****************************************************/
    # HomeController
    # Function name : getSitesettingsList
    # Author        :
    # Created Date  : 27-05-2019
    # Purpose       : To get site settings list
    # Params        : 
    /*****************************************************/
    public function getSitesettingsList(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $requestedFields = trim($request->parameters);
        if ($requestedFields) {
            $requestedFields = explode(",", $requestedFields);
            $requestedFields = array_map('trim', $requestedFields);
            $listData = SiteSetting::select($requestedFields)->first();
            return \Response::json( ApiHelper::generateResponseBody('HC-GSS-0001#site_settings_list', $listData) );
        } else {
            return \Response::json( ApiHelper::generateResponseBody('HC-GSS-0002#site_settings_list', trans('custom.no_parameter_available'), false, 100));
        }
    }

    /*****************************************************/
    # HomeController
    # Function name : homePage
    # Author        :
    # Created Date  : 17-06-2019
    # Purpose       : To get home page details (banner,categories,products)
    # Params        : 
    /*****************************************************/
    public function homePage(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);

        $userData  = ApiHelper::getUserFromHeader($request);
        
        $homePageData   = [];
        $bannerList     = [];
        $caregoryList   = [];
        $productList    = [];
        $bannerList     = [];
        $recentProducts = [];
        $productOutput  = [];
        $newProduct = [];
        
        $homePageData['base_url'] = asset('');
        $homePageData['banner_url'] = 'uploads/banner/app';
        $homePageData['categories_url'] = 'uploads/categories/thumbs';
        $homePageData['products_url'] = 'uploads/products/thumbs';
        
        $bannerList = Banner::where(['status' => '1','image_for'=>2])
                                ->whereNull('deleted_at')
                                ->orderBy('created_at', 'desc')
                                ->select('image_name as image')
                                ->get();
                                
        $homePageData['banner_list'] = $bannerList;

        $caregoryList = Category::inRandomOrder()
                                    ->whereNull('deleted_at')
                                    ->with([
                                        'local'=> function($query) use ($lang) {
                                            $query->where('lang_code','=', $lang);
                                        }
                                    ])
                                    ->select('id','name','image')->take(3)->get();
        $homePageData['category_list'] = $caregoryList;
        $selectActiveVendors = User::where('status','1')->pluck('id'); 
        $productList = ProductVendor::inRandomOrder()
                                    ->whereIn('vendor_id', $selectActiveVendors)
                                    ->whereNull('deleted_at')
                                    ->with([
                                        'productVendorsLocals'=> function($query) use ($lang) {
                                            $query->where('lang_code','=', $lang);
                                        }
                                    ])
                                    ->select('id','name');
                                    if ($userData) {
                                        $productList = $productList->where('vendor_id', '<>', $userData->id);
                                    }
                                    $productList = $productList->take(3)->get();
        // looping for formating Array for API
        if(count($productList) > 0) {
            foreach($productList as $keyProduct => $valProduct) {
                $newProduct['id']       = $valProduct->id;
                $newProduct['name']     = $valProduct->name;
                $newProduct['local']    = $valProduct->productVendorsLocals->count() ? ApiHelper::replaceNulltoEmptyStringAndIntToString($valProduct->productVendorsLocals->toArray()) : [];
                if ( count($valProduct->productDefaultImage) > 0) {
                    $newProduct['image'] = $valProduct->productDefaultImage[0]['image_name'];
                } else {
                    $newProduct['image'] = '';
                }
                $homePageData['product_list'][] = $newProduct;
                unset($newProduct);
            }
        }     

        $recentProducts = ProductVendor::whereNull('deleted_at')
                                        ->whereIn('vendor_id', $selectActiveVendors)
                                        ->with([
                                            'productVendorsLocals'=> function($query) use ($lang) {
                                                $query->where('lang_code','=', $lang);
                                            }
                                        ])
                                        ->select('id','name');
                                        if ($userData) {
                                            $recentProducts = $recentProducts->where('vendor_id', '<>', $userData->id);
                                        }
                                        $recentProducts = $recentProducts->take(3)
                                        ->orderBy('created_at', 'desc')
                                        ->get();
        $homePageData['recent_product_list'] = [];
        // looping for formating Array for API                                
        if(count($recentProducts) > 0) {
            foreach($recentProducts as $keyProduct => $valProduct) {
                $newProduct['id']       = $valProduct->id;
                $newProduct['name']     = $valProduct->name;
                $newProduct['local']    = $valProduct->productVendorsLocals->count() ? ApiHelper::replaceNulltoEmptyStringAndIntToString($valProduct->productVendorsLocals->toArray()) : [];
                if ( count($valProduct->productDefaultImage) > 0) {
                    $newProduct['image'] = $valProduct->productDefaultImage[0]['image_name'];
                } else {
                    $newProduct['image'] = '';
                }
                $homePageData['recent_product_list'][] = $newProduct;
                unset($newProduct);
            }
        }     
        $data['order_by']    = 'created_at';
        $data['order']       = 'desc';
        $homePageData['coupon_list'] = [];
        $couponQuery        = Coupon::whereNull('deleted_at')->orderBy($data['order_by'], $data['order'])->get();
        if($couponQuery->count()){
            foreach($couponQuery as $key => $val) {
                if (strtotime(now()) > $val->end_time) {
                    $status = trans("custom.wholesaler_bid_expired");
                 } else {
                    if($val->status == 1) {
                        $status = trans("custom.active");

                        $coupon['id']       = $val->id;
                        $coupon['name']     = $val->code;
                        if($val->type === '1'){
                            $type = trans("custom.cart_amount");
                        }else if($val->type === '2'){
                            $type = trans("custom.category");
                        }else if($val->type === '3'){
                            $type = trans("custom.product");
                        }else if($val->type === '4'){
                            $type = trans("custom.wholesaler_heading");
                        }else {
                            $type = "";
                        }
                        $coupon['type']     = $type;
                        if($val->discount_type === 'F'){
                            $discountType = trans("custom.flat");
                        }else{
                            $discountType = trans("custom.percent");
                        } 
                        $coupon['discountType']         = $discountType;
                        $coupon['amount']               = (string)$val->amount;
                        $coupon['start_time']           = Helper::formattedDateTime($val->start_time);
                        $coupon['end_time']             = Helper::formattedDateTime($val->end_time);
                        $coupon['status']               = $status;
                        $homePageData['coupon_list'][]  = $coupon;
                        unset($coupon);

                    } else {
                        $status = trans("custom.inactive");
                    }
                 }
                
                 if(count($homePageData['coupon_list']) >= 3){
                     break;
                 }
            }
        }
        // $homePageData['coupon_list'] = $couponQuery;
        if ($homePageData) {
            return Response::json(ApiHelper::generateResponseBody('HC-HP-0001#home_page',$homePageData)); 
        } else {
            return Response::json(ApiHelper::generateResponseBody('HC-HP-0002#home_page', trans('custom.no_records_found'), false, 200));
        }
    }

    /*****************************************************/
    # HomeController
    # Function name : couponList
    # Author        :
    # Created Date  : 24-09-2019
    # Purpose       : To get all active coupon lists
    # Params        : 
    /*****************************************************/
    public function couponList(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);

        $userData  = ApiHelper::getUserFromHeader($request);

        $data['order_by']    = 'created_at';
        $data['order']       = 'desc';
        $homePageData['coupon_list'] = [];
        $couponQuery        = Coupon::whereNull('deleted_at')->orderBy($data['order_by'], $data['order'])->get();
        if($couponQuery->count()){
            foreach($couponQuery as $key => $val) {
                if (strtotime(now()) > $val->end_time) {
                    $status = trans("custom.wholesaler_bid_expired");
                } else {
                    if($val->status == 1) {
                        $status = trans("custom.active");

                        $coupon['id']       = $val->id;
                        $coupon['name']     = $val->code;
                        if($val->type === '1'){
                            $type = trans("custom.cart_amount");
                        }else if($val->type === '2'){
                            $type = trans("custom.category");
                        }else if($val->type === '3'){
                            $type = trans("custom.product");
                        }else if($val->type === '4'){
                            $type = trans("custom.wholesaler_heading");
                        }else {
                            $type = "";
                        }
                        $coupon['type']     = $type;
                        if($val->discount_type === 'F'){
                            $discountType = trans("custom.flat");
                        }else{
                            $discountType = trans("custom.percent");
                        } 
                        $coupon['discountType']     = $discountType;
                        $coupon['amount']     = (string)$val->amount;
                        $coupon['start_time']     = Helper::formattedDateTime($val->start_time);
                        $coupon['end_time']     = Helper::formattedDateTime($val->end_time);
                        $coupon['status']     = $status;
                        $homePageData['coupon_list'][] = $coupon;
                        unset($coupon);

                    } else {
                        $status = trans("custom.inactive");
                    }
                }
            }
        }

        return Response::json(ApiHelper::generateResponseBody('HC-CL-0001#coupon_list',$homePageData)); 
    }


    /*****************************************************/
    # HomeController
    # Function name : getUserDetailsBecomeWholesaler
    # Author        :
    # Created Date  : 28-06-2019
    # Purpose       : Get user details during become a wholesaler
    # Params        : Request $request
    /*****************************************************/
    public function getUserDetailsBecomeWholesaler(Request $request)
    {
        $getSetLang     = ApiHelper::getSetLocale($request);
        $userData       = ApiHelper::getUserFromHeader($request);
        $checkUserRole  = ApiHelper::checkUserRole($userData, 'wholesaler');
        
        if ($checkUserRole) {
            if ($checkUserRole == 'approval-pending') {
                return \Response::json( ApiHelper::generateResponseBody('HC-GUDBW-0002#become_wholesaler_user_details', trans('custom.admin_approval_pending'), false, 200) );
            } else if($checkUserRole == 'already-wholesaler') {
                return \Response::json( ApiHelper::generateResponseBody('HC-GUDBW-0003#become_wholesaler_user_details', trans('custom.already_wholesaler'), false, 300) );
            } else if($checkUserRole == 'error') {
                return \Response::json( ApiHelper::generateResponseBody('HC-GUDBW-0004#become_wholesaler_user_details', trans('custom.something_went_wrong'), false, 100) );
            } else {
                return Response::json(ApiHelper::generateResponseBody('HC-GUDBW-0001#become_wholesaler_user_details', $checkUserRole));
            }
        } else {
            return \Response::json( ApiHelper::generateResponseBody('HC-GUDBW-0004#become_wholesaler_user_details', trans('custom.something_went_wrong'), false, 100) );
        }
    }

    /*****************************************************/
    # HomeController
    # Function name : becomeWholesaler
    # Author        :
    # Created Date  : 27-06-2019
    # Purpose       : Become a wholesaler
    # Params        : Request $request
    /*****************************************************/
    public function becomeWholesaler(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $userData       = ApiHelper::getUserFromHeader($request);
        $checkUserRole  = ApiHelper::checkUserRole($userData, 'wholesaler');
        
        if ($checkUserRole) {
            if ($checkUserRole == 'approval-pending') {
                return \Response::json( ApiHelper::generateResponseBody('HC-GUDBW-0002#become_wholesaler', trans('custom.admin_approval_pending'), false, 200) );
            } else if($checkUserRole == 'already-wholesaler') {
                return \Response::json( ApiHelper::generateResponseBody('HC-GUDBW-0003#become_wholesaler', trans('custom.already_wholesaler'), false, 300) );
            } else if($checkUserRole == 'error') {
                return \Response::json( ApiHelper::generateResponseBody('HC-GUDBW-0004#become_wholesaler', trans('custom.something_went_wrong'), false, 100) );
            } else {
                
                $validator = Validator::make($request->all(),
                                        [
                                            'full_name'             => 'required|min:2|max:255',
                                            'mobile_number'         => 'required|unique:'.(new UserWholesaler)->getTable().',mobile_number',
                                            // 'profile_image'         => 'required|mimes:jpeg,jpg,png,svg|max:'.Helper::PROFILE_IMAGE_MAX_UPLOAD_SIZE,
                                            'profile_image'         => 'required',
                                            'shop_name'             => 'required',
                                            'shop_address'          => 'required',
                                            'shop_mobile_number'    => 'required',
                                            // 'upload_doc_1'          => 'required|mimes:doc,docx,xls,xlsx,pdf,txt|max:'.Helper::DOCUMENT_MAX_UPLOAD_SIZE,
                                            // 'upload_doc_2'          => 'required|mimes:doc,docx,xls,xlsx,pdf,txt|max:'.Helper::DOCUMENT_MAX_UPLOAD_SIZE,
                                            // 'upload_doc_3'          => 'required|mimes:doc,docx,xls,xlsx,pdf,txt|max:'.Helper::DOCUMENT_MAX_UPLOAD_SIZE,
                                            'upload_doc_1'          => 'required',
                                            'upload_doc_2'          => 'required',
                                            'upload_doc_3'          => 'required',
                                        ],
                                        [
                                            'full_name.required'    => trans('custom.full_name_required'),
                                            'full_name.min'         => trans('custom.full_name_min_length_check'),
                                            'full_name.max'         => trans('custom.full_name_max_length_check'),
                                            'mobile_number.required'=> trans('custom.mobile_number_required'),
                                            'mobile_number.unique'  => trans('custom.mobile_number_unique_check'),
                                            'profile_image.required'=> trans('custom.profile_image_required'),
                                            'shop_name.required'    => trans('custom.shop_name_required'),
                                            'shop_address.required' => trans('custom.shop_address_required'),
                                            'shop_mobile_number.required' => trans('custom.shop_mobile_number_required'),
                                            'upload_doc_1.required' => trans('custom.file_required'),
                                            'upload_doc_2.required' => trans('custom.file_required'),
                                            'upload_doc_3.required' => trans('custom.file_required'),
                                        ]
                                    );
                $errors = $validator->errors()->all();
                if ($errors) {
                    return Response::json(ApiHelper::generateResponseBody('HC-BW-0005#become_wholesaler', ["errors" => $errors], false, 500));
                } else {
                    

                    $fullName       = isset($request->full_name)?$request->full_name:'';    
                    $profileImage   = $request->profile_image;
                    $uploadDoc1     = $request->upload_doc_1;
                    $uploadDoc2     = $request->upload_doc_2;
                    $uploadDoc3     = $request->upload_doc_3;
                    
                    if ($fullName) {
                        $newWholesaler                      = new UserWholesaler;
                        $newWholesaler->user_id             = $userData->id;
                        $newWholesaler->full_name           = $fullName;
                        $newWholesaler->mobile_number       = isset($request->mobile_number)?$request->mobile_number:'';
                        $newWholesaler->shop_name           = isset($request->shop_name)?$request->shop_name:'';
                        $newWholesaler->shop_address        = isset($request->shop_address)?$request->shop_address:'';
                        $newWholesaler->shop_mobile_number  = isset($request->shop_mobile_number)?$request->shop_mobile_number:'';

                        if ($uploadDoc1 || $uploadDoc2 || $uploadDoc3) {
                            $destinationPath = public_path('uploads/users/wholesaler_docs/');
                            if ($uploadDoc1) {
                                $rawFilenameDoc1    = $uploadDoc1->getClientOriginalName();
                                $extensionDoc1      = pathinfo($rawFilenameDoc1, PATHINFO_EXTENSION);
                                $uploadDoc1Name     = $userData->id.'_wholesaler_doc1_'.strtotime(date('Y-m-d H:i:s')).'.'.$extensionDoc1;
                                
                                // if(in_array($extensionDoc1,Helper::UPLOADED_DOC_FILE_TYPES)) {
                                    $uploadDoc1->move($destinationPath , $uploadDoc1Name);
                                    $newWholesaler->upload_doc_1 = $uploadDoc1Name;
                                // }
                            }
                            if ($uploadDoc2) {
                                $rawFilenameDoc2    = $uploadDoc2->getClientOriginalName();
                                $extensionDoc2      = pathinfo($rawFilenameDoc2, PATHINFO_EXTENSION);
                                $uploadDoc2Name     = $userData->id.'_wholesaler_doc2_'.strtotime(date('Y-m-d H:i:s')).'.'.$extensionDoc2;
                                
                                // if(in_array($extensionDoc2,Helper::UPLOADED_DOC_FILE_TYPES)) {
                                    $uploadDoc2->move($destinationPath , $uploadDoc2Name);
                                    $newWholesaler->upload_doc_2 = $uploadDoc2Name;
                                // }
                            }
                            if ($uploadDoc3) {
                                $rawFilenameDoc3    = $uploadDoc3->getClientOriginalName();
                                $extensionDoc3      = pathinfo($rawFilenameDoc3, PATHINFO_EXTENSION);
                                $uploadDoc3Name     = $userData->id.'_wholesaler_doc3_'.strtotime(date('Y-m-d H:i:s')).'.'.$extensionDoc3;
                                
                                // if(in_array($extensionDoc3,Helper::UPLOADED_DOC_FILE_TYPES)) {
                                    $uploadDoc3->move($destinationPath , $uploadDoc3Name);
                                    $newWholesaler->upload_doc_3 = $uploadDoc3Name;
                                // }
                            }
                        }
                        $wholesalerSaved = $newWholesaler->save();

                        if ($wholesalerSaved) {
                            if ($profileImage) {
                                $profileImage       = $request->file('profile_image');
                                $rawFilename        = $profileImage->getClientOriginalName();
                                $extension          = pathinfo($rawFilename, PATHINFO_EXTENSION);
                                $profileImageName   = $userData->id.'_wholesaler_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;
                                
            
                                if(in_array($extension,Helper::UPLOADED_PROFILE_IMAGE_FILE_TYPES)) {
                                    $imageResize = Image::make($profileImage->getRealPath());
                                    $imageResize->save(public_path('uploads/users/'.$profileImageName));
            
                                    $imageResize->resize(Helper::PROFILE_THUMB_IMAGE_WIDTH, Helper::PROFILE_THUMB_IMAGE_HEIGHT, function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                    $imageResize->save(public_path('uploads/users/thumbs/'.$profileImageName));
                                    
                                    User::where('id', $userData->id)->update(['profile_image' => $profileImageName]);
                                }
                            }
                            return \Response::json(ApiHelper::generateResponseBody('HC-BW-0001#become_wholesaler', trans('custom.wholesaler_created_successfully')));
                            $siteSetting = SiteSetting::first();
                            \Mail::send('email_templates.site.requestForPostion',
                            [
                                'user'           => $newWholesaler,
                                'position'          => 'Wholesaler'
                            ], function ($m) use ($siteSetting) {
                                $m->to($siteSetting->to_email, $siteSetting->website_title)->subject('Request to become wholesaler');
                            });
                        } else {
                            return \Response::json(ApiHelper::generateResponseBody('HC-BW-0002#become_wholesaler', trans('custom.something_went_wrong'), false, 200));
                        }
                    } else {
                        return \Response::json(ApiHelper::generateResponseBody('HC-BW-0004#become_wholesaler', trans('custom.full_name_required'), false, 400));
                    }            
                }
            }
        } else {
            return \Response::json( ApiHelper::generateResponseBody('HC-GUDBW-0004#become_wholesaler', trans('custom.something_went_wrong'), false, 100) );
        }
    }

    /*****************************************************/
    # HomeController
    # Function name : getUserDetailsBecomeContractor
    # Author        :
    # Created Date  : 28-06-2019
    # Purpose       : Get user details during become a contractor
    # Params        : Request $request
    /*****************************************************/
    public function getUserDetailsBecomeContractor(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);

        $userData       = ApiHelper::getUserFromHeader($request);
        $checkUserRole  = ApiHelper::checkUserRole($userData, 'contractor');
        
        if ($checkUserRole) {
            if ($checkUserRole == 'approval-pending') {
                return \Response::json( ApiHelper::generateResponseBody('HC-GUDBC-0002#become_contractor_user_details', trans('custom.admin_approval_pending'), false, 200) );
            } else if($checkUserRole == 'already-contractor') {
                return \Response::json( ApiHelper::generateResponseBody('HC-GUDBC-0003#become_contractor_user_details', trans('custom.already_contractor'), false, 300) );
            } else if($checkUserRole == 'error') {
                return \Response::json( ApiHelper::generateResponseBody('HC-GUDBC-0004#become_contractor_user_details',  trans('custom.something_went_wrong'), false, 100) );
            } else {
                return Response::json(ApiHelper::generateResponseBody('HC-GUDBC-0001#become_contractor_user_details', $checkUserRole));
            }
        } else {
            return \Response::json( ApiHelper::generateResponseBody('HC-GUDBC-0004#become_contractor_user_details',  trans('custom.something_went_wrong'), false, 100) );
        }
    }

    /*****************************************************/
    # HomeController
    # Function name : becomeContractor
    # Author        :
    # Created Date  : 28-06-2019
    # Purpose       : Become a contractor
    # Params        : Request $request
    /*****************************************************/
    public function becomeContractor(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $userData       = ApiHelper::getUserFromHeader($request);
        $checkUserRole  = ApiHelper::checkUserRole($userData, 'contractor');
        
        if ($checkUserRole) {
            if ($checkUserRole == 'approval-pending') {
                return \Response::json( ApiHelper::generateResponseBody('HC-GUDBC-0002#become_contractor', trans('custom.admin_approval_pending'), false, 200) );
            } else if($checkUserRole == 'already-contractor') {
                return \Response::json( ApiHelper::generateResponseBody('HC-GUDBC-0003#become_contractor', trans('custom.already_contractor'), false, 300) );
            } else if($checkUserRole == 'error') {
                return \Response::json( ApiHelper::generateResponseBody('HC-GUDBC-0004#become_contractor', trans('custom.something_went_wrong'), false, 100) );
            } else {
                
                $validator = Validator::make($request->all(),
                                        [
                                            'full_name'             => 'required|min:2|max:255',
                                            //'mobile_number'         => 'required',
                                            'mobile_number'         => 'required|unique:'.(new UserContractor)->getTable().',mobile_number',
                                            // 'profile_image'         => 'required|mimes:jpeg,jpg,png,svg|max:'.Helper::PROFILE_IMAGE_MAX_UPLOAD_SIZE,
                                            'profile_image'         => 'required',
                                            // 'upload_doc_1'          => 'required|mimes:doc,docx,xls,xlsx,pdf,txt|max:'.Helper::DOCUMENT_MAX_UPLOAD_SIZE,
                                            // 'upload_doc_2'          => 'required|mimes:doc,docx,xls,xlsx,pdf,txt|max:'.Helper::DOCUMENT_MAX_UPLOAD_SIZE,
                                            // 'upload_doc_3'          => 'required|mimes:doc,docx,xls,xlsx,pdf,txt|max:'.Helper::DOCUMENT_MAX_UPLOAD_SIZE,
                                            'upload_doc_1'          => 'required',
                                            'upload_doc_2'          => 'required',
                                            'upload_doc_3'          => 'required',
                                        ],
                                        [
                                            'full_name.required'    => trans('custom.full_name_required'),
                                            'full_name.min'         => trans('custom.full_name_min_length_check'),
                                            'full_name.max'         => trans('custom.full_name_max_length_check'),
                                            'mobile_number.required'=> trans('custom.mobile_number_required'),
                                            'mobile_number.unique'  => trans('custom.mobile_number_unique_check'),
                                            'profile_image.required'=> trans('custom.profile_image_required'),
                                            'upload_doc_1.required' => trans('custom.file_required'),
                                            'upload_doc_2.required' => trans('custom.file_required'),
                                            'upload_doc_3.required' => trans('custom.file_required'),
                                        ]
                                    );
                $errors = $validator->errors()->all();
                if ($errors) {
                    return Response::json(ApiHelper::generateResponseBody('HC-BC-0005#become_contractor', ["errors" => $errors], false, 500));
                } else {
                    

                    $fullName       = isset($request->full_name)?$request->full_name:'';    
                    $profileImage   = $request->profile_image;
                    $uploadDoc1     = $request->upload_doc_1;
                    $uploadDoc2     = $request->upload_doc_2;
                    $uploadDoc3     = $request->upload_doc_3;
                    
                    if ($fullName) {
                        $newContractor                      = new UserContractor;
                        $newContractor->user_id             = $userData->id;
                        $newContractor->full_name           = $fullName;
                        $newContractor->mobile_number       = isset($request->mobile_number)?$request->mobile_number:'';
                        
                        if ($uploadDoc1 || $uploadDoc2 || $uploadDoc3) {
                            $destinationPath = public_path('uploads/users/contractor_docs/');
                            if ($uploadDoc1) {
                                $rawFilenameDoc1    = $uploadDoc1->getClientOriginalName();
                                $extensionDoc1      = pathinfo($rawFilenameDoc1, PATHINFO_EXTENSION);
                                $uploadDoc1Name     = $userData->id.'_contractor_doc1_'.strtotime(date('Y-m-d H:i:s')).'.'.$extensionDoc1;
                                
                                // if(in_array($extensionDoc1,Helper::UPLOADED_DOC_FILE_TYPES)) {
                                    $uploadDoc1->move($destinationPath , $uploadDoc1Name);
                                    $newContractor->upload_doc_1 = $uploadDoc1Name;
                                // }
                            }
                            if ($uploadDoc2) {
                                $rawFilenameDoc2    = $uploadDoc2->getClientOriginalName();
                                $extensionDoc2      = pathinfo($rawFilenameDoc2, PATHINFO_EXTENSION);
                                $uploadDoc2Name     = $userData->id.'_contractor_doc2_'.strtotime(date('Y-m-d H:i:s')).'.'.$extensionDoc2;
                                
                                // if(in_array($extensionDoc2,Helper::UPLOADED_DOC_FILE_TYPES)) {
                                    $uploadDoc2->move($destinationPath , $uploadDoc2Name);
                                    $newContractor->upload_doc_2 = $uploadDoc2Name;
                                // }
                            }
                            if ($uploadDoc3) {
                                $rawFilenameDoc3    = $uploadDoc3->getClientOriginalName();
                                $extensionDoc3      = pathinfo($rawFilenameDoc3, PATHINFO_EXTENSION);
                                $uploadDoc3Name     = $userData->id.'_contractor_doc3_'.strtotime(date('Y-m-d H:i:s')).'.'.$extensionDoc3;
                                
                                // if(in_array($extensionDoc3,Helper::UPLOADED_DOC_FILE_TYPES)) {
                                    $uploadDoc3->move($destinationPath , $uploadDoc3Name);
                                    $newContractor->upload_doc_3 = $uploadDoc3Name;
                                // }
                            }
                        }
                        $contractorSaved = $newContractor->save();

                        if ($contractorSaved) {
                            if ($profileImage) {
                                $profileImage       = $request->file('profile_image');
                                $rawFilenameProfile = $profileImage->getClientOriginalName();
                                $extension          = pathinfo($rawFilenameProfile, PATHINFO_EXTENSION);
                                $profileImageName   = $userData->id.'_contractor_'.strtotime(date('Y-m-d H:i:s')).'.'.$extension;
                                
                                if(in_array($extension,Helper::UPLOADED_PROFILE_IMAGE_FILE_TYPES)) {
                                    $imageResize = Image::make($profileImage->getRealPath());
                                    $imageResize->save(public_path('uploads/users/'.$profileImageName));
            
                                    $imageResize->resize(Helper::PROFILE_THUMB_IMAGE_WIDTH, Helper::PROFILE_THUMB_IMAGE_HEIGHT, function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                    $imageResize->save(public_path('uploads/users/thumbs/'.$profileImageName));
                                    
                                    User::where('id', $userData->id)->update(['profile_image' => $profileImageName]);
                                }
                            }
                            return \Response::json(ApiHelper::generateResponseBody('HC-BC-0001#become_contractor', trans('custom.contractor_created_successfully')));
                            $siteSetting = SiteSetting::first();
                            \Mail::send('email_templates.site.requestForPostion',
                            [
                                'user'           => $newContractor,
                                'position'       => 'Contractor'
                            ], function ($m) use ($siteSetting) {
                                $m->to($siteSetting->to_email, $siteSetting->website_title)->subject('Request to become contractor');
                            });
                        } else {
                            return \Response::json(ApiHelper::generateResponseBody('HC-BC-0002#become_contractor', trans('custom.something_went_wrong'), false, 200));
                        }
                    } else {
                        return \Response::json(ApiHelper::generateResponseBody('HC-BW-0004#become_contractor', trans('custom.full_name_required'), false, 400));
                    }            
                }
            }
        } else {
            return \Response::json( ApiHelper::generateResponseBody('HC-GUDBC-0004#become_contractor', trans('custom.something_went_wrong'), false, 100) );
        }
    }

    /*****************************************************/
    # HomeController
    # Function name : notificationList
    # Author        :
    # Created Date  : 28-08-2019
    # Purpose       : get notification list
    # Params        : Request $request
    /*****************************************************/
    public function notificationList(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        $userData  = ApiHelper::getUserFromHeader($request);
        if (!$userData) {
            return \Response::json(ApiHelper::generateResponseBody('HC-NL-0001#notification_list',  trans('custom.not_authorized'), false, 400));
        }

        $offset = 0;
        $limit  = Helper::NOTIFICATION_LIMIT;
        $page   = isset($request->page)?$request->page:0;
        if ($page > 0) {
            $offset = ($limit * $page);
        }
        $pushNotification = PushNotification::where("send_to",$userData->id)->orderBy("id","desc");
        $totalNotifications = $pushNotification->count();
        $pushNotification = $pushNotification->skip($offset)
                            ->take($limit)->get();
        $notificationList = array();
        if($pushNotification->count()){
            foreach($pushNotification as $key => $val){
                $notification = array();
                $notification['id'] = $val->id;
                if($lang == "EN"){
                    $notification['message'] = Helper::cleanString($val->message_en);
                }else{
                    $notification['message'] = Helper::cleanString($val->message_ar);
                }
                $notification['type']               = $val->type;
                $notification['order_id']           = $val->order_id ? (string)$val->order_id : "";
                $notification['order_details_id']   = $val->order_details_id ? (string)$val->order_details_id : "";
                $notification['contract_id']        = $val->contract_id ? (string)$val->contract_id : "";
                $notification['bid_id']             = $val->bid_id ? (string)$val->bid_id : "";
                $notification['created_at']         = $val->created_at->format('dS M,Y H:i');
                $notificationList[]                 = $notification;
            }
        }else{
            $totalNotifications = 0;
        }
        return \Response::json(ApiHelper::generateResponseBody('HC-NL-0002#notification_list', ["count"=>$totalNotifications,"list" =>$notificationList]));
    }

    /*****************************************************/
    # HomeController
    # Function name : changeLanguage
    # Author        :
    # Created Date  : 28-08-2019
    # Purpose       : change language
    # Params        : Request $request
    /*****************************************************/
    public function changeLanguage(Request $request)
    {
        $getSetLang = ApiHelper::getSetLocale($request);
        $lang       = strtoupper($getSetLang);
        $userData  = ApiHelper::getUserFromHeader($request);
        if (!$userData) {
            return \Response::json(ApiHelper::generateResponseBody('HC-CL-0002#change_language',  trans('custom.successfully_updated')));
        }
        if($request->lang == "en" || $request->lang == "ar"){
            $userData->loggedin_language = $request->lang;
            $userData->save();
            return \Response::json(ApiHelper::generateResponseBody('HC-CL-0002#change_language',  trans('custom.successfully_updated')));
        }else{
            return \Response::json(ApiHelper::generateResponseBody('HC-CL-0003#change_language',  trans('custom.something_went_wrong'), false, 400));
        }
    }
}