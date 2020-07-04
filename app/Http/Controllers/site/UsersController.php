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
use App;

class UsersController extends Controller
{
    /*****************************************************/
    # Function name : signUp
    # Params        : 
    /*****************************************************/
    public function signUp( Request $request )
    {
        $pageTitle = 'Signup';
        $cmsData = $metaData = Helper::getMetaData();

        if (Auth::guard('web')->check()) {
            return redirect()->route('site.home');
        }
        
        if ($request->isMethod('POST')) {
            $validationCondition = array(
                'full_name'     => 'required|min:2|max:255',
                'email'         => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/|unique:'.(new User)->getTable().',email',
                'user_name'     => 'required|unique:'.(new User)->getTable().',user_name',
                'password'      => 'required|regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                
            ); 
            $validationMessages = array(
                'full_name.required'    => 'PLease enter full name',
                'full_name.min'         => 'Full name should be at least 2 characters',
                'full_name.max'         => 'Full name must not be more than 255 characters',
                'email.required'        => 'Please enter email address',
                'email.regex'           => 'Please enter valid email address',
                'email.unique'          => 'Please enter unique email address',
                'user_name.required'    => 'Please enter username',
                'user_name.unique'      => 'Please enter unique username',
                'password.required'     => 'Please enter password',
                'password.regex'        => 'Min. 8, alphanumeric and special character',
            );

            $Validator = Validator::make($request->all(), $validationCondition,$validationMessages);
            if ($Validator->fails()) {
                return Redirect::back()->withErrors($Validator)->withInput();
            } else {
                $password = $request->password;

                Session::put([
                    'full_name'     => trim($request->full_name, ' '),
                    'email'         => trim($request->email, ' '),
                    'user_name'     => trim($request->user_name, ' '),
                    'password'      => $password,
                ]);

                if (Session::get('full_name') != '' && Session::get('email') != '') {
                    return redirect()->route('site.users.add-payment-method');
                } else {
                    $request->session()->flash('alert-danger', trans('custom.please_try_again'));
                    return redirect()->back();
                }
            }
        }
        
        return view('site.user.sign_up',[
            'pageTitle' => $pageTitle,
            'title'     => $metaData['title'],
            'keyword'   => $metaData['keyword'],
            'description'=>$metaData['description'],
            'cmsData'   => $cmsData,
            ]);
    }
    
    /*****************************************************/
    # Function name : addPaymentMethod
    # Params        : 
    /*****************************************************/
    public function addPaymentMethod( Request $request )
    {
        $pageTitle = 'Add Payment Method';
        $cmsData = $metaData = Helper::getMetaData();

        if (Auth::guard('web')->check()) {
            return redirect()->route('site.home');
        }

        if (Session::get('full_name') == '' && Session::get('email') == '') {
            return redirect()->route('site.users.sign-up');
        }

        if ($request->isMethod('POST')) {
            $siteSetting        = Helper::getSiteSettings();
            $validationCondition = array(
                'name_on_card'  => 'required|min:2|max:255',
                'card_number'   => 'required',
                'expiry_month'  => 'required|regex:/^[0-9]{2,2}$/',
                'expiry_year'   => 'required|regex:/^[0-9]{4,4}$/',
                'cvv'           => 'required|regex:/^[0-9]{3,3}$/',
            ); 
            $validationMessages = array(
                'name_on_card.required' => 'PLease enter name',
                'name_on_card.min'      => 'Name should be at least 2 characters',
                'name_on_card.max'      => 'Name must not be more than 255 characters',
                'card_number.required'  => 'Please enter card number',
                'expiry_month.required' => 'Please enter expiry month',
                'expiry_month.regex'    => 'Please enter valid expiry month',
                'expiry_year.required'  => 'Please enter expiry year',
                'expiry_year.regex'     => 'Please enter valid expiry year',
                'cvv.required'          => 'Please enter cvv',
                'cvv.regex'             => 'Please enter valid cvv',
            );

            $Validator = Validator::make($request->all(), $validationCondition,$validationMessages);
            if ($Validator->fails()) {
                return Redirect::back()->withErrors($Validator)->withInput();
            } else {
                $currentMonthYear  = date('Y').'-'.date('m');
                $cardMonthYear     = $request->expiry_year.'-'.$request->expiry_month;
                
                if (strtotime($cardMonthYear) < strtotime($currentMonthYear)) {
                    $request->session()->flash('alert-danger', 'Please enter valid expiry month & year');
                    return redirect()->back();
                } else {
                    $name = explode(' ', Session::get('full_name'));
                    
                    $newUser = new User;
                    $newUser->first_name            = isset($name[0]) ? $name[0] : '';
                    $newUser->last_name             = isset($name[1]) ? $name[1] : '';
                    $newUser->full_name             = Session::get('full_name');
                    $newUser->email                 = Session::get('email');
                    $newUser->user_name             = Session::get('user_name');
                    $newUser->password              = Session::get('password');
                    $newUser->name_on_card          = $request->name_on_card;
                    $newUser->expiry_month          = Helper::customEncryptionDecryption($request->card_number);
                    $newUser->expiry_year           = Helper::customEncryptionDecryption($request->expiry_month);
                    $newUser->expiry_year           = Helper::customEncryptionDecryption($request->expiry_year);
                    $newUser->cvv                   = Helper::customEncryptionDecryption($request->cvv);                    
                    $newUser->status                = '1';
                    $saveUser = $newUser->save();

                    if ($saveUser) {
                        // Mail to customer
                        \Mail::send('email_templates.site.registration',
                        [
                            'user'          => $newUser,
                            'password'      => Session::get('password'),
                            'siteSetting'   => $siteSetting,
                            'app_config'    => [
                                'appname'       => $siteSetting->website_title,
                                'appLink'       => Helper::getBaseUrl(),
                                'controllerName'=> 'users',
                            ],
                        ], function ($m) use ($newUser, $siteSetting) {
                            $m->to($newUser->email, $newUser->full_name)->subject('Thank you - '.$siteSetting->website_title);
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
                            ],
                        ], function ($m) use ($siteSetting) {
                            $m->to($siteSetting->to_email, $siteSetting->website_title)->subject('New Registration - '.$siteSetting->website_title);
                        });

                        Session::put([
                            'full_name'     => '',
                            'email'         => '',
                            'user_name'     => '',
                            'password'      => '',
                        ]);
    
                        $request->session()->flash('alert-success', 'Thank you for registering with us');
                        return redirect()->route('site.users.login');
                    } else {
                        $request->session()->flash('alert-danger', trans('custom.please_try_again'));
                        return redirect()->route('site.users.sign-up');
                    }
                }
            }
        }
        
        return view('site.user.add_payment_method',[
            'pageTitle' => $pageTitle,
            'title'     => $metaData['title'],
            'keyword'   => $metaData['keyword'],
            'description'=>$metaData['description'],
            'cmsData'   => $cmsData
            ]);
    }

    /*****************************************************/
    # Function name : affiliatedSignUp
    # Params        : 
    /*****************************************************/
    public function affiliatedSignUp( Request $request )
    {
        $pageTitle = 'Affilated User Sign Up';
        $cmsData = $metaData = Helper::getMetaData();

        if (Auth::guard('web')->check()) {
            return redirect()->route('site.home');
        }
        
        if ($request->isMethod('POST')) {
            $validationCondition = array(
                'email'     => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/|unique:'.(new User)->getTable().',email',
                'password'  => 'required|regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                
            ); 
            $validationMessages = array(
                'email.required'        => 'Please enter email address',
                'email.regex'           => 'Please enter valid email address',
                'email.unique'          => 'Please enter unique email address',
                'password.required'     => 'Please enter password',
                'password.regex'        => 'Min. 8, alphanumeric and special character',
            );

            $Validator = Validator::make($request->all(), $validationCondition,$validationMessages);
            if ($Validator->fails()) {
                return Redirect::back()->withErrors($Validator)->withInput();
            } else {
                $password = $request->password;

                Session::put([
                    'affiliated_email'      => trim($request->email, ' '),
                    'affiliated_password'   => $password,
                ]);

                if (Session::get('affiliated_email') != '' && Session::get('affiliated_password') != '') {
                    return redirect()->route('site.users.affiliated-payment');
                } else {
                    $request->session()->flash('alert-danger', trans('custom.please_try_again'));
                    return redirect()->back();
                }
            }
        }
        
        return view('site.user.affiliated_sign_up',[
            'pageTitle' => $pageTitle,
            'title'     => $metaData['title'],
            'keyword'   => $metaData['keyword'],
            'description'=>$metaData['description'],
            'cmsData'   => $cmsData,
            ]);
    }

    /*****************************************************/
    # Function name : affiliatedPayment
    # Params        : 
    /*****************************************************/
    public function affiliatedPayment( Request $request )
    {
        $pageTitle = 'Affilated Payment';
        $cmsData = $metaData = Helper::getMetaData();

        if (Auth::guard('web')->check()) {
            return redirect()->route('site.home');
        }

        if (Session::get('affiliated_email') == '' && Session::get('affiliated_password') == '') {
            return redirect()->route('site.users.affiliated-sign-up');
        }        
        
        if ($request->isMethod('POST')) {
            $siteSetting        = Helper::getSiteSettings();
            $validationCondition = array(
                'first_name'    => 'required|min:2|max:255',
                'last_name'     => 'required|min:2|max:255',
                'postal_code'   => 'required',
                'name_on_card'  => 'required|min:2|max:255',
                'card_number'   => 'required',
                'expiry_month'  => 'required|regex:/^[0-9]{2,2}$/',
                'expiry_year'   => 'required|regex:/^[0-9]{4,4}$/',
                'cvv'           => 'required|regex:/^[0-9]{3,3}$/',
            ); 
            $validationMessages = array(
                'first_name.required'   => 'Please enter first name',
                'first_name.min'        => 'First name should be at least 2 characters',
                'first_name.max'        => 'First name must not be more than 255 characters',
                'last_name.required'    => 'Please enter last name',
                'last_name.min'         => 'Last name should be at least 2 characters',
                'last_name.max'         => 'Last name must not be more than 255 characters',
                'postal_code.required'  => 'Please enter postal code',
                'name_on_card.required' => 'Please enter name on card',
                'name_on_card.min'      => 'Name should be at least 2 characters',
                'name_on_card.max'      => 'Name must not be more than 255 characters',
                'card_number.required'  => 'Please enter card number',
                'expiry_month.required' => 'Please enter expiry month',
                'expiry_month.regex'    => 'Please enter valid expiry month',
                'expiry_year.required'  => 'Please enter expiry year',
                'expiry_year.regex'     => 'Please enter valid expiry year',
                'cvv.required'          => 'Please enter cvv',
                'cvv.regex'             => 'Please enter valid cvv',
            );

            $Validator = Validator::make($request->all(), $validationCondition,$validationMessages);
            if ($Validator->fails()) {
                return Redirect::back()->withErrors($Validator)->withInput();
            } else {
                $currentMonthYear  = date('Y').'-'.date('m');
                $cardMonthYear     = $request->expiry_year.'-'.$request->expiry_month;
                
                if (strtotime($cardMonthYear) < strtotime($currentMonthYear)) {
                    $request->session()->flash('alert-danger', 'Please enter valid expiry month & year');
                    return redirect()->back();
                } else {
                    $newUser = new User;
                    $newUser->first_name    = isset($request->first_name) ? $request->first_name : '';
                    $newUser->last_name     = isset($request->last_name) ? $request->last_name : '';
                    $newUser->full_name     = $newUser->first_name.' '.$newUser->last_name;
                    $newUser->postal_code   = trim($request->postal_code, ' ');
                    $newUser->email         = Session::get('affiliated_email');
                    $newUser->password      = Session::get('affiliated_password');
                    $newUser->name_on_card  = $request->name_on_card;
                    $newUser->expiry_month  = Helper::customEncryptionDecryption($request->card_number);
                    $newUser->expiry_year   = Helper::customEncryptionDecryption($request->expiry_month);
                    $newUser->expiry_year   = Helper::customEncryptionDecryption($request->expiry_year);
                    $newUser->cvv           = Helper::customEncryptionDecryption($request->cvv);                    
                    $newUser->user_type     = 'AU';
                    $newUser->status        = '1';
                    $saveUser = $newUser->save();

                    if ($saveUser) {
                        // Mail to customer
                        \Mail::send('email_templates.site.registration',
                        [
                            'user'          => $newUser,
                            'password'      => Session::get('affiliated_password'),
                            'siteSetting'   => $siteSetting,
                            'app_config'    => [
                                'appname'       => $siteSetting->website_title,
                                'appLink'       => Helper::getBaseUrl(),
                                'controllerName'=> 'users',
                            ],
                        ], function ($m) use ($newUser, $siteSetting) {
                            $m->to($newUser->email, $newUser->full_name)->subject('Thank you - '.$siteSetting->website_title);
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
                            ],
                        ], function ($m) use ($siteSetting) {
                            $m->to($siteSetting->to_email, $siteSetting->website_title)->subject('New Registration - '.$siteSetting->website_title);
                        });

                        Session::put([
                            'affiliated_email'      => '',
                            'affiliated_password'   => '',
                        ]);
    
                        $request->session()->flash('alert-success', 'Thank you for registering with us');
                        return redirect()->route('site.users.login');
                    } else {
                        $request->session()->flash('alert-danger', trans('custom.please_try_again'));
                        return redirect()->route('site.users.affiliated-sign-up');
                    }
                }
            }
        }
        
        return view('site.user.affiliated_payment',[
            'pageTitle' => $pageTitle,
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
        $pageTitle = 'Login';
        $cmsData = $metaData = Helper::getMetaData();

        if (Auth::guard('web')->check()) {
            return redirect()->route('site.home');
        }

        if ($request->isMethod('POST')) {
            $validationCondition = array(
                'email'         => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',
                'password'      => 'required'
            );
            $validationMessages = array(
                'email.required'        => 'Please enter email address',
                'email.regex'           => 'Please enter valid email address',
                'password.required'     => 'Please enter password',
            );

            $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);
            if ($Validator->fails()) {
                return Redirect::back()->withErrors($Validator)->withInput();
            } else {
                if ($request->email && $request->password) {
                    if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password, 'status' => '1'])) {
                        $user = Auth::user();
                        if ($user->status == 0) {
                            $request->session()->flash('alert-danger', 'User is inactive');
                            Auth::guard('web')->logout();
                            return redirect()->route('site.users.login');
                        } else if ($user->role_id) {
                            $request->session()->flash('alert-danger', 'You are not authorized');
                            Auth::guard('web')->logout();
                            return redirect()->route('site.users.login');
                        } else {
                            $userData                = Auth::user();
                            $userData->lastlogintime = strtotime(date('Y-m-d H:i:s'));
                            $userData->save();

                            return redirect()->route('site.home');
                        }
                    } else {
                        $request->session()->flash('alert-danger', "Your email or password doesn't match");
                        return redirect()->route('site.users.login');
                    }
                } else {
                    $request->session()->flash('alert-danger', 'Please provide you username and password');
                    return redirect()->route('site.users.login');
                }
            }
        }
        
        return view('site.user.login',[
            'pageTitle' => $pageTitle,
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
        $pageTitle = 'Forgot Password';
        $cmsData = $metaData = Helper::getMetaData();

        if (Auth::guard('web')->check()) {
            return redirect()->route('site.home');
        }
        $cmsData = $metaData = Helper::getMetaData();

        if ($request->isMethod('POST')) {
            $siteSetting = Helper::getSiteSettings();
            
            $validationCondition = array(
                'email'    => 'required'
            );
            $validationMessages = array(
                'email.required'   => 'Please enter email address',
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
                            $request->session()->flash('alert-danger', 'Admin User');
                            return Redirect::back()->withErrors($Validator)->withInput();
                        }
                        if ($user->status == 0) {
                            $request->session()->flash('alert-danger', 'Your account is inactive');
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
                                    ],
                                ], function ($m) use ($user, $siteSetting) {
                                    $m->to($user->email, $user->full_name)->subject('Change Password Link - '.$siteSetting->website_title);
                                });
                                $request->session()->flash('alert-success', 'Please check your email for reset password link');
                                return redirect()->route('site.users.forgot-password');
                            } else {
                                $request->session()->flash('alert-danger', trans('custom.please_try_again'));
                                return redirect()->back();
                            }
                        }
                    } else {
                        $request->session()->flash('alert-danger', 'This email is not registered with us');
                        return redirect()->back();
                    }
                } else {
                    $request->session()->flash('alert-danger', 'Please provide email');
                    return redirect()->back();
                }
            }
        }
        return view('site.user.forgot_password',[
            'title'     => $metaData['title'],
            'keyword'   => $metaData['keyword'],
            'description'=>$metaData['description'],
            'cmsData'   => $cmsData,
            'pageTitle' => $pageTitle,
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
        if (Auth::guard('web')->logout()) {
            return redirect()->route('site.users.login');
        } else {
            return redirect()->route('site.home');
        }
    }

}
