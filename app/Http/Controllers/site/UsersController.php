<?php
/*****************************************************/
# Page/Class name   : UsersController
# Purpose           : all user related functions
/*****************************************************/
namespace App\Http\Controllers\site;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Support\Facades\Session;
use Auth;
use Hash;
use \Validator;
use Helper;
use Image;
use AdminHelper;
use \Response;
Use Redirect;
use App\User;
use App\Cms;
use App\Notification;
use App\DeliveryAddress;
use App\Avatar;
use App\AvatarLocal;
use App\PinCode;
use App;

class UsersController extends Controller
{
    /*****************************************************/
    # Function name : signUp
    # Params        : 
    /*****************************************************/
    public function signUp( Request $request )
    {
        $currentLang = $lang = App::getLocale();
        $cmsData = $metaData = Helper::getMetaData();

        if (Auth::guard('web')->check()) {
            return redirect()->route('site.'.$currentLang.'.home');
        }
        
        if ($request->isMethod('POST')) {
            $currentDateTime    = date('Y-m-d H:i:s');

            $siteSetting        = Helper::getSiteSettings();
            $validationCondition = array(
                'first_name'    => 'required|min:2|max:255',
                'last_name'     => 'required|min:2|max:255',
                'email'         => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/|unique:'.(new User)->getTable().',email',
                'password'      => 'required|regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                'agree'         => 'required',
                
            ); 
            $validationMessages = array(
                'first_name.required'   => trans('custom.please_enter_first_name'),
                'first_name.min'        => trans('custom.first_name_min_length_check'),
                'first_name.max'        => trans('custom.first_name_max_length_check'),
                'last_name.required'    => trans('custom.please_enter_last_name'),
                'last_name.min'         => trans('custom.last_name_min_length_check'),
                'last_name.max'         => trans('custom.last_name_max_length_check'),
                'email.required'        => trans('custom.please_enter_email'),
                'email.regex'           => trans('custom.please_enter_valid_email'),
                'email.unique'          => trans('custom.please_enter_unique_email'),
                'password.required'     => trans('custom.please_enter_password'),
                'password.regex'        => trans('custom.password_regex'),
                'agree.required'        => trans('custom.please_agree'),
            );

            $Validator = Validator::make($request->all(), $validationCondition,$validationMessages);
            if ($Validator->fails()) {
                return Redirect::back()->withErrors($Validator)->withInput();
            } else {
                $siteSetting = Helper::getSiteSettings();                
                $password = $request->password;
                
                $newUser = new User;
                $newUser->first_name            = trim($request->first_name, ' ');
                $newUser->last_name             = trim($request->last_name, ' ');
                $newUser->full_name             = $newUser->first_name.' '.$newUser->last_name;
                $newUser->email                 = trim($request->email, ' ');
                $newUser->password              = $request->password;
                $newUser->status                = '1';
                $newUser->agree                 = $request->agree;
                $saveUser = $newUser->save();
                if ($saveUser) {
                    // Mail to customer
                    \Mail::send('email_templates.site.registration',
                    [
                        'user' => $newUser,
                        'password'  => $password,
                        'siteSetting'   => $siteSetting,
                        'app_config'    => [
                            'appname'       => $siteSetting->website_title,
                            'appLink'       => Helper::getBaseUrl(),
                            'controllerName'=> 'users',
                            'currentLang'=> $currentLang,
                        ],
                    ], function ($m) use ($newUser) {
                        $m->to($newUser->email, $newUser->full_name)->subject('Thank You - Cordon Bleu X');
                    });

                    // Mail to admin
                    \Mail::send('email_templates.site.registration_details_to_admin',
                    [
                        'user' => $newUser,
                        'siteSetting'   => $siteSetting,
                        'app_config'    => [
                            'appname'       => $siteSetting->website_title,
                            'appLink'       => Helper::getBaseUrl(),
                            'controllerName'=> 'users',
                            'currentLang'=> $currentLang,
                        ],
                    ], function ($m) use ($siteSetting) {
                        $m->to($siteSetting->to_email, $siteSetting->website_title)->subject('New Registration - Cordon Bleu X');
                    });

                    $request->session()->flash('alert-success',trans('custom.registration_success_for_email'));
                    return redirect()->back();
                } else {
                    $request->session()->flash('alert-danger', trans('custom.please_try_again'));
                    return redirect()->back();
                }
            }
        }
        
        return view('site.user.sign_up',[
            'title'     => $metaData['title'],
            'keyword'   => $metaData['keyword'],
            'description'=>$metaData['description'],
            'cmsData'   => $cmsData
            ]);
    }

    /*****************************************************/
    # Function name : login
    # Params        : 
    /*****************************************************/
    public function login( Request $request )
    {
        $currentLang = $lang = App::getLocale();
        $cmsData = $metaData = Helper::getMetaData();

        if (Auth::guard('web')->check()) {
            if (Auth::user()->login_language != null) {
                $currentLang = Auth::user()->login_language;
            }
            return redirect()->route('site.'.$currentLang.'.home');
        }

        if ($request->isMethod('POST')) {
            $validationCondition = array(
                'email'     => 'required',
                'password'  => 'required'
            );
            $validationMessages = array(
                'email.required'    => trans('custom.please_enter_email'),
                'password.required' => trans('custom.please_enter_password')
            );

            $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);
            if ($Validator->fails()) {
                return Redirect::back()->withErrors($Validator)->withInput();
            } else {
                if ($request->email && $request->password) {
                    if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password, 'status' => '1'])) {
                        $user = Auth::user();
                        if ($user->status == 0) {
                            $request->session()->flash('alert-danger',trans('custom.inactive_user'));
                            Auth::guard('web')->logout();
                            return redirect()->route('site.'.$currentLang.'.users.login');
                        } else if ($user->role_id) {
                            $request->session()->flash('alert-danger',trans('custom.not_authorized'));
                            Auth::guard('web')->logout();
                            return redirect()->route('site.'.$currentLang.'.users.login');
                        } else {
                            $userData                = Auth::user();
                            $userData->lastlogintime = strtotime(date('Y-m-d H:i:s'));
                            $userData->save();

                            // Update session cart details with previous saved cart start //
                            if (Session::get('cartSessionId') != '') {
                                $this->mergeCartItemDetails();
                            }
                            // Update session cart details with previous saved cart end //

                            // Redirect to login language
                            if (Auth::user()->login_language != null) {
                                $currentLang = Auth::user()->login_language;
                            }

                            // Checkout status & redirect
                            if (Session::get('redirectTo') != '') {
                                if (Session::get('redirectTo') == 'checkout_page') {   // redirect to checkout page
                                    Session::put('redirectTo','');
                                    return redirect()->route('site.'.$currentLang.'.users.checkout');
                                } else {
                                    return redirect()->route('site.'.$currentLang.'.home');
                                } 
                            }                           

                            return redirect()->route('site.'.$currentLang.'.home');
                        }
                    } else {
                        $request->session()->flash('alert-danger',trans('custom.credential_mismatch'));
                        return redirect()->route('site.'.$currentLang.'.users.login');
                    }
                } else {
                    $request->session()->flash('alert-danger',trans('custom.please_provide_credential'));
                    return redirect()->route('site.'.$currentLang.'.users.login');
                }
            }
        }
        
        return view('site.user.login',[
            'title'     => $metaData['title'],
            'keyword'   => $metaData['keyword'],
            'description'=>$metaData['description'],
            'cmsData'   => $cmsData
            ]);
    }

    /*****************************************************/
    # Function name : forgotPassword
    # Params        : Request $request
    /*****************************************************/
    public function forgotPassword( Request $request )
    {
        $currentLang = $lang = App::getLocale();
        if (Auth::guard('web')->check()) {
            return redirect()->route('site.'.$currentLang.'.home');
        }
        $cmsData = $metaData = Helper::getMetaData();

        if ($request->isMethod('POST')) {
            $siteSetting = Helper::getSiteSettings();
            
            $validationCondition = array(
                'email'    => 'required'
            );
            $validationMessages = array(
                'email.required'   => trans('custom.please_enter_email')
            );

            $Validator = Validator::make($request->all(), $validationCondition,$validationMessages);
            if ($Validator->fails()) {
                return Redirect::back()->withErrors($Validator)->withInput();
            } else {
                $email   = $request->email;
                if ($email) {
                    $siteSetting = Helper::getSiteSettings();

                    $user = User::where('email', $email)->first();
                    if ($user) {
                        if($user->role_id != null){
                            $request->session()->flash('alert-danger',trans('custom.admin_user'));
                            return Redirect::back()->withErrors($Validator)->withInput();
                        }
                        if ($user->status == 0) {
                            $request->session()->flash('alert-danger',trans('custom.inactive_user'));
                            return redirect()->back();
                        } else {
                            $user->remember_token = md5($email);
                            $saveUser = $user->save();

                            if ($saveUser) {            
                                \Mail::send('email_templates.site.change_password_link',
                                [
                                    'user' => $user,
                                    'siteSetting'   => $siteSetting,
                                    'app_config'    => [
                                        'appname'       => $siteSetting->website_title,
                                        'appLink'       => Helper::getBaseUrl(),
                                        'controllerName'=> 'users',
                                        'currentLang'=> $currentLang,
                                    ],
                                ], function ($m) use ($user) {
                                    $m->to($user->email, $user->full_name)->subject('Change Password Link - Cordon Bleu X');
                                });
                                $request->session()->flash('alert-success',trans('custom.change_password_success_for_email'));
                                return redirect()->route('site.'.$currentLang.'.users.forgot-password');
                            } else {
                                $request->session()->flash('alert-danger', trans('custom.please_try_again'));
                                return redirect()->back();
                            }
                        }
                    } else {
                        $request->session()->flash('alert-danger',trans('custom.email_not_found'));
                    }
                } else {
                    $request->session()->flash('alert-danger',trans('custom.please_provide_emil'));
                }
            }
        }
        return view('site.user.forgot_password',[
            'title'     => $metaData['title'],
            'keyword'   => $metaData['keyword'],
            'description'=>$metaData['description'],
            'cmsData'   => $cmsData
        ]);
    }

    /*****************************************************/
    # Function name : resetPassword
    # Params        : $token, Request $request
    /*****************************************************/
    public function resetPassword($token, Request $request)
    {
        $currentLang = $lang = App::getLocale();
        if (Auth::guard('web')->check()) {
            return redirect()->route('site.'.$currentLang.'.home');
        }
        if ($token == '') {
            return redirect()->route('site.'.$currentLang.'.users.forgot-password');
        }
        $cmsData = $metaData = Helper::getMetaData();

        if ($request->isMethod('POST')) {
            $validationCondition = array(
                'password' => 'required|regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                'confirm_password' => 'required|regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/|same:password',
            );
            $validationMessages = array(
                'password.required' => trans('custom.please_enter_password'),
                'password.regex' => trans('custom.password_regex'),
                'confirm_password.required' => trans('custom.confirm_password'),
                'confirm_password.regex' => trans('custom.password_regex'),
                'confirm_password.same' => trans('custom.confirm_password_password'),
            );
            $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);
            if ($Validator->fails()) {
                return Redirect::back()->withErrors($Validator)->withInput();
            } else {
                $userData = User::where('remember_token', $token)->first();
                if ($userData != '') {
                    if ($userData->status == 0) {
                        $request->session()->flash('alert-danger',trans('custom.inactive_user'));
                        return redirect()->back();
                    } else {
                        $password   = $request->password;
                        $id         = $userData->id;
                        
                        if ($password && $id) {                         
                            $userData->remember_token = '';
                            $userData->password = $password;
                            $userData->save();

                            $request->session()->flash('alert-success',trans('custom.password_changed_sucess'));
                            return redirect()->route('site.'.$currentLang.'.users.login');
                        } else {
                            $request->session()->flash('alert-danger',trans('custom.please_try_again'));
                        }
                    }
                } else {
                    $request->session()->flash('alert-danger',trans('custom.reset_already_done'));
                }
                return redirect()->back();
            }
        }
        return view('site.user.reset_password',[
            'title'     => $metaData['title'],
            'keyword'   => $metaData['keyword'],
            'description'=>$metaData['description'],
            'cmsData'   => $cmsData,
            'token'     => $token,
        ]);
        
    }

    /*****************************************************/
    # Function name : personalDetails
    # Params        : Request $request
    /*****************************************************/
    public function personalDetails(Request $request)
    {
        $currentLang = $lang = App::getLocale();
        $cmsData = $metaData = Helper::getMetaData();

        try
        {
            $userDetail = Auth::guard('web')->user();
            $avatarList = Avatar::where(['status' => '1'])
									->whereNull('deleted_at')
									->with([
                                        'local'=> function($query) use ($currentLang) {
                                            $query->where('lang_code','=', $currentLang);
                                        }
                                    ])
                                    ->orderBy('sort', 'asc')
                                    ->get();
            $data['avatarList'] = $avatarList;
            $data['userDetail'] = $userDetail;

            if ($request->isMethod('POST')) {
                // dd($request);
                $validationCondition = array(
                    'nickname'      => 'required',
                    'title'         => 'required',                    
                    'first_name'    => 'required|min:2|max:255',
                    'last_name'     => 'required|min:2|max:255',
                    'login_language'=> 'required',
                    'email'         => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/|unique:'.(new User)->getTable().',email,'.Auth::user()->id,
                    'phone_no'      => 'required',
                    'dob'           =>  'required|date_format:d/m/Y',
                );
                $validationMessages = array(
                    'nickname.required'     => trans('custom.please_enter_nick_name'),
                    'title.required'        => trans('custom.please_select_title'),
                    'first_name.required'   => trans('custom.please_enter_first_name'),
                    'first_name.min'        => trans('custom.first_name_min_length_check'),
                    'first_name.max'        => trans('custom.first_name_max_length_check'),
                    'last_name.required'    => trans('custom.please_enter_last_name'),
                    'last_name.min'         => trans('custom.last_name_min_length_check'),
                    'last_name.max'         => trans('custom.last_name_max_length_check'),
                    'login_language.required'=> trans('custom.please_select_language'),
                    'email.required'        => trans('custom.please_enter_email'),
                    'email.regex'           => trans('custom.please_enter_valid_email'),
                    'email.unique'          => trans('custom.please_enter_unique_email'),
                    'phone_no.required'     => trans('custom.please_enter_phone'),
                    'dob.required'          => trans('custom.please_select_dob'),
                    'dob.date_format'       => trans('custom.please_select_dob_format'),
                );
                $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->route('site.'.\App::getLocale().'.users.personal-details')->withErrors($Validator)->withInput();
                } else {
                    $updateUserData['nickname']     = $request->nickname;
                    $updateUserData['title']        = $request->title;
                    $updateUserData['first_name']   = $request->first_name;
                    $updateUserData['last_name']    = $request->last_name;
                    $updateUserData['full_name']    = ucwords($request->first_name).' '.ucwords($request->last_name);
                    $updateUserData['login_language']= $request->login_language;
                    $updateUserData['email']        = $request->email;
                    $updateUserData['phone_no']     = $request->phone_no;
                    $updateUserData['dob']          = date('Y-m-d',strtotime(str_replace('/','-',$request->dob)));
                    
                    $saveUserData = User::where('id', $userDetail->id)->update($updateUserData);
                    if ($saveUserData) {
                        Auth::guard('web')->loginUsingId($userDetail->id);
                        $request->session()->flash('alert-success', trans('custom.success_profile_update'));
                        return redirect()->back();
                    } else {
                        $request->session()->flash('alert-danger', trans('custom.please_try_again'));
                        return redirect()->back();
                    }
                }
            }
        } catch (Exception $e) {
            return redirect()->route('site.users.personal-details')->with('error', $e->getMessage());
        }

        return view('site.user.personal_details',[
            'title'         => $cmsData['title'],
            'keyword'       => $cmsData['keyword'],
            'description'   =>$cmsData['description'],
            'cmsData'       => $cmsData,
            'userDetail'    => $userDetail,
            'avatarList'    => $avatarList,
        ]);
    }

    /*****************************************************/
    # Function name : changeUserPassword
    # Params        : Request $request
    /*****************************************************/
    public function changeUserPassword(Request $request)
    {
        $currentLang = $lang = App::getLocale();
        $cmsData = $metaData = Helper::getMetaData();

        try
        {
            if ($request->isMethod('POST')) {
                $validationCondition = array(
                    'current_password' => 'required|min:8',
                    'password' => 'required|regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                    'confirm_password' => 'required|regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/|same:password',
                );
                $validationMessages = array(
                    'current_password.required' => trans('custom.current_password'),
                    'password.required'         => trans('custom.passwords'),
                    'password.regex'            => trans('custom.password_regex'),
                    'confirm_password.required' => trans('custom.confirm_password'),
                    'confirm_password.regex'    => trans('custom.password_regex'),
                    'confirm_password.same'     => trans('custom.confirm_password_password'),
                );
                $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->route('site.'.\App::getLocale().'.users.change-user-password')->withErrors($Validator);
                } else {
                    $userDetail = Auth::guard('web')->user();
                    $user_id = Auth::guard('web')->user()->id;
                    $hashed_password = $userDetail->password;

                    // check if current password matches with the saved password
                    if (Hash::check($request->current_password, $hashed_password)) {
                        $userDetail->password = $request->password;
                        $updatePassword = $userDetail->save();

                        if ($updatePassword) {
                            $request->session()->flash('alert-success', trans('custom.password_update_success'));
                            return redirect()->route('site.'.\App::getLocale().'.users.change-user-password');
                        } else {
                            $request->session()->flash('alert-danger', trans('custom.please_try_again'));
                            return redirect()->back();
                        }
                    } else {
                        $request->session()->flash('alert-danger', trans('custom.old_password_not_match'));
                        return redirect()->back();
                    }
                }
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return view('site.user.change_user_password',[
            'title'     => $metaData['title'],
            'keyword'   => $metaData['keyword'],
            'description'=>$metaData['description'],
        ]);
    }

    /*****************************************************/
    # Function name : notifications
    # Params        : Request $request
    /*****************************************************/
    public function notifications(Request $request)
    {
        $currentLang = $lang = App::getLocale();
        $cmsData = $metaData = Helper::getMetaData();

        try
        {
            $notificationDetails = Notification::where('user_id', Auth::user()->id)->first();

            if ($request->isMethod('POST')) {

                if ($notificationDetails == null) {
                    $newNotification = new Notification;
                    $newNotification->user_id           = Auth::user()->id;
                    $newNotification->order_update      = isset($request->order_update) ? '1' : '0';
                    $newNotification->rate_your_meal    = isset($request->rate_your_meal) ? '1' : '0';
                    $newNotification->sms               = isset($request->sms) ? '1' : '0';
                    $save = $newNotification->save();
                    if ($save) {
                        $request->session()->flash('alert-success', trans('custom.notification_update_success'));
                        return redirect()->back();
                    } else {
                        $request->session()->flash('alert-danger', trans('custom.please_try_again'));
                        return redirect()->back();
                    }
                } else {
                    $notificationDetails->order_update      = isset($request->order_update) ? '1' : '0';
                    $notificationDetails->rate_your_meal    = isset($request->rate_your_meal) ? '1' : '0';
                    $notificationDetails->sms               = isset($request->sms) ? '1' : '0';
                    $update = $notificationDetails->save();
                    if ($update) {
                        $request->session()->flash('alert-success', trans('custom.notification_update_success'));
                        return redirect()->back();
                    } else {
                        $request->session()->flash('alert-danger', trans('custom.please_try_again'));
                        return redirect()->back();
                    }
                }                
            }            
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return view('site.user.notification',[
            'title'                 => $metaData['title'],
            'keyword'               => $metaData['keyword'],
            'description'           =>$metaData['description'],
            'notificationDetails'   => $notificationDetails
        ]);
    }
    
    /*****************************************************/
    # Function name : deliveryAddress
    # Params        : Request $request
    /*****************************************************/
    public function deliveryAddress(Request $request)
    {
        $currentLang = $lang = App::getLocale();
        $cmsData = $metaData = Helper::getMetaData();

        try
        {
            $deliveryAddresses = DeliveryAddress::where('user_id', Auth::user()->id)->get();
            
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return view('site.user.delivery_address',[
            'title'             => $metaData['title'],
            'keyword'           => $metaData['keyword'],
            'description'       =>$metaData['description'],
            'deliveryAddresses' => $deliveryAddresses
        ]);
    }    
    
    /*****************************************************/
    # Function name : addAddress
    # Params        : Request $request
    /*****************************************************/
    public function addAddress(Request $request)
    {
        $currentLang = $lang = App::getLocale();
        $cmsData = $metaData = Helper::getMetaData();

        try
        {
            if ($request->isMethod('POST')) {
                $validationCondition = array(
                    'company'   => 'required',
                    'street'    => 'required',
                    'floor'     => 'required',
                    'door_code' => 'required',
                    'post_code' => 'required',
                    'city'  => 'required',
                );
                $validationMessages = array(
                    'company.required'      => trans('custom.please_enter_company'),
                    'street.required'       => trans('custom.please_enter_street'),
                    'floor.required'        => trans('custom.please_enter_floor'),
                    'door_code.required'    => trans('custom.please_enter_door_code'),
                    'post_code.required'    => trans('custom.please_enter_post_code'),
                    'city.required'         => trans('custom.please_enter_city'),
                );
                $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->route('site.'.\App::getLocale().'.users.add-address')->withErrors($Validator)->withInput();
                } else {
                    $existPinCode = PinCode::where(['code' => $request->post_code, 'status' => '1'])->count();
                    if ($existPinCode > 0) {
                        $newAddress = new DeliveryAddress;
                        $newAddress->user_id = Auth::user()->id;
                        $newAddress->company = $request->company;
                        $newAddress->street = $request->street;
                        $newAddress->floor = $request->floor;
                        $newAddress->door_code = $request->door_code;
                        $newAddress->post_code = $request->post_code;
                        $newAddress->city = $request->city;
                        $newAddress->alias_type = $request->addressAlias;
                        if ($request->addressAlias == 'Ot') {
                            $newAddress->own_alias = isset($request->customAlias) ? $request->customAlias : null;
                        } else {
                            $newAddress->own_alias = null;
                        }
                        $save = $newAddress->save();
                        if ($save) {
                            $request->session()->flash('alert-success', trans('custom.address_add_success'));
                            return redirect()->back();
                        } else {
                            $request->session()->flash('alert-danger', trans('custom.please_try_again'));
                            return redirect()->back()->withInput();
                        }
                    } else {
                        $request->session()->flash('alert-danger', trans('custom.error_unavailability'));
                        return redirect()->back()->withInput();
                    }
                }
            }            
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return view('site.user.add_address',[
            'title'             => $metaData['title'],
            'keyword'           => $metaData['keyword'],
            'description'       =>$metaData['description'],
        ]);
    }

    /*****************************************************/
    # Function name : editAddress
    # Params        : Request $request, $id
    /*****************************************************/
    public function editAddress(Request $request, $id)
    {
        $currentLang = $lang = App::getLocale();
        $cmsData = $metaData = Helper::getMetaData();
        
        try
        {
            $decrypted = Helper::customEncryptionDecryption($id, 'decrypt');
            $addressDetails = DeliveryAddress::where(['id' => $decrypted, 'user_id' => Auth::user()->id])->first();
            if ($request->isMethod('POST')) {
                $validationCondition = array(
                    'company'   => 'required',
                    'street'    => 'required',
                    'floor'     => 'required',
                    'door_code' => 'required',
                    'post_code' => 'required',
                    'city'  => 'required',
                );
                $validationMessages = array(
                    'company.required'      => trans('custom.please_enter_company'),
                    'street.required'       => trans('custom.please_enter_street'),
                    'floor.required'        => trans('custom.please_enter_floor'),
                    'door_code.required'    => trans('custom.please_enter_door_code'),
                    'post_code.required'    => trans('custom.please_enter_post_code'),
                    'city.required'         => trans('custom.please_enter_city'),
                );
                $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->route('site.'.\App::getLocale().'.users.add-address')->withErrors($Validator);
                } else {
                    $existPinCode = PinCode::where(['code' => $request->post_code, 'status' => '1'])->count();
                    if ($existPinCode > 0) {
                        $addressDetails = DeliveryAddress::where(['id' => Helper::customEncryptionDecryption($request->address_id, 'decrypt'), 'user_id' => Auth::user()->id])->first();
                        $addressDetails->company    = $request->company;
                        $addressDetails->street     = $request->street;
                        $addressDetails->floor      = $request->floor;
                        $addressDetails->door_code  = $request->door_code;
                        $addressDetails->post_code  = $request->post_code;
                        $addressDetails->city       = $request->city;
                        $addressDetails->alias_type = $request->addressAlias;
                        if ($request->addressAlias == 'Ot') {
                            $addressDetails->own_alias = isset($request->customAlias) ? $request->customAlias : null;
                        } else {
                            $addressDetails->own_alias = null;
                        }
                        $update = $addressDetails->save();
                        if ($update) {
                            $request->session()->flash('alert-success', trans('custom.address_update_success'));
                            return redirect()->back();
                        } else {
                            $request->session()->flash('alert-danger', trans('custom.please_try_again'));
                            return redirect()->back();
                        }
                    } else {
                        $request->session()->flash('alert-danger', trans('custom.error_unavailability'));
                        return redirect()->back();
                    }
                }
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return view('site.user.edit_address',[
            'title'             => $metaData['title'],
            'keyword'           => $metaData['keyword'],
            'description'       => $metaData['description'],
            'id'                => $id,
            'addressDetails'    => $addressDetails,
        ]);
    }

    /*****************************************************/
    # Function name : deleteAddress
    # Params        : Request $request
    /*****************************************************/
    public function deleteAddress(Request $request)
    {
        $title      = trans('custom.error');
        $message    = trans('custom.please_try_again');
        $type       = 'error';
        
        if ($request->isMethod('POST')) {
            $addressId = Helper::customEncryptionDecryption($request->addressId, 'decrypt');

            $getData = DeliveryAddress::where(['id' => $addressId, 'user_id' => Auth::user()->id])->first();
            if ($getData != null) {
                $getData->delete();

                $title      = trans('custom.success');
                $message    = trans('custom.address_delete_successful');
                $type       = 'success';
            }

            return json_encode([
                'title'     => $title,
                'message'   => $message,
                'type'      => $type,
            ]);
        }
    }
    
    /*****************************************************/
    # Function name : changeAvatar
    # Params        : Request $request
    /*****************************************************/
    public function changeAvatar(Request $request)
    {
        $title      = trans('custom.error');
        $message    = trans('custom.please_try_again');
        $type       = 'error';
        $image      = '';
        
        if ($request->isMethod('POST')) {
            $avatarId = $request->avatarId;

            User::where('id', Auth::user()->id)->update(['avatar_id' => $avatarId]);
            
            $getData = User::where(['id' => Auth::user()->id])->first();
            $updatedAvatar = asset('uploads/avatar/thumbs/').'/'.$getData->avatarDetails->image;

            return json_encode([
                'updatedAvatar' => $updatedAvatar,
            ]);
        }
    }
    
    /*****************************************************/
    # Function name : checkoutAddAddress
    # Params        : Request $request
    /*****************************************************/
    public function checkoutAddAddress(Request $request)
    {
        $currentLang = $lang = App::getLocale();
        $cmsData = $metaData = Helper::getMetaData();

        try
        {
            if ($request->isMethod('POST')) {
                $validationCondition = array(
                    'company'   => 'required',
                    'street'    => 'required',
                    'floor'     => 'required',
                    'door_code' => 'required',
                    'post_code' => 'required',
                    'city'  => 'required',
                );
                $validationMessages = array(
                    'company.required'      => trans('custom.please_enter_company'),
                    'street.required'       => trans('custom.please_enter_street'),
                    'floor.required'        => trans('custom.please_enter_floor'),
                    'door_code.required'    => trans('custom.please_enter_door_code'),
                    'post_code.required'    => trans('custom.please_enter_post_code'),
                    'city.required'         => trans('custom.please_enter_city'),
                );
                $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->route('site.'.\App::getLocale().'.users.add-address')->withErrors($Validator)->withInput();
                } else {
                    $existPinCode = PinCode::where(['code' => $request->post_code, 'status' => '1'])->count();
                    if ($existPinCode > 0) {
                        $newAddress = new DeliveryAddress;
                        $newAddress->user_id = Auth::user()->id;
                        $newAddress->company = $request->company;
                        $newAddress->street = $request->street;
                        $newAddress->floor = $request->floor;
                        $newAddress->door_code = $request->door_code;
                        $newAddress->post_code = $request->post_code;
                        $newAddress->city = $request->city;
                        $newAddress->alias_type = $request->addressAlias;
                        if ($request->addressAlias == 'Ot') {
                            $newAddress->own_alias = isset($request->customAlias) ? $request->customAlias : null;
                        } else {
                            $newAddress->own_alias = null;
                        }
                        $save = $newAddress->save();
                        if ($save) {
                            $request->session()->flash('alert-success', trans('custom.address_add_success'));
                            return redirect()->route('site.'.$currentLang.'.users.checkout');
                        } else {
                            $request->session()->flash('alert-danger', trans('custom.please_try_again'));
                            return redirect()->back()->withInput();
                        }
                    } else {
                        $request->session()->flash('alert-danger', trans('custom.error_unavailability'));
                        return redirect()->back()->withInput();
                    }
                }
            }    
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return view('site.user.checkout_add_address',[
            'title'             => $metaData['title'],
            'keyword'           => $metaData['keyword'],
            'description'       =>$metaData['description'],
        ]);
    }
    
    /*****************************************************/
    # Function name : logout
    # Params        : 
    /*****************************************************/
    public function logout()
    {
        $currentLang = App::getLocale();
        if (Auth::guard('web')->logout()) {
            Session::put('redirectTo', '');
            return redirect()->route('site.'.$currentLang.'.users.login');
        } else {
            return redirect()->route('site.'.$currentLang.'.home');
        }
    }

}
