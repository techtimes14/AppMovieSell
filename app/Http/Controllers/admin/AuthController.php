<?php
/*****************************************************/
# AuthController
# Page/Class name   : AuthController
# Author            :
# Created Date      : 20-05-2019
# Functionality     : Admin Login, Logout
# Purpose           : Admin Login Management
/*****************************************************/

namespace App\Http\Controllers\admin;

use App;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Helper;
use Illuminate\Http\Request;
use Redirect;
use Validator;
use View;

class AuthController extends Controller
{
    /*****************************************************/
    # AuthController
    # Function name : login
    # Author        :
    # Created Date  : 20-05-2019
    # Purpose       : Check whether a user is logged in and
    #                 then redirect the user to either Login
    #                 Panel or Dashboard
    # Params        : Request $request
    /*****************************************************/
    public function login(Request $request)
    {

        $data['page_title'] = 'Login';
        $data['panel_title'] = 'Login';
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        } else {
            try // Try block of try-catch exception starts
            {
                if ($request->isMethod('POST')) {
                    $validationCondition = array(
                        'email' => 'required|email',
                        'password' => 'required',
                    );
                    $Validator = Validator::make($request->all(), $validationCondition);

                    if ($Validator->fails()) {
                        // If validation error occurs, load the error listing
                        return redirect()->route('admin.login')->withErrors($Validator);
                    } else {
                        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password, 'status' => '1'])) {
                            if (Auth::guard('admin')->user()->checkRolePermission == null) {
                                Auth::guard('admin')->logout();
                                $request->session()->flash('alert-danger', 'Permission denied');
                                return redirect()->back();
                            } else {
                                $user  = \Auth::guard('admin')->user();
                                $user->lastlogintime = strtotime(date('Y-m-d H:i:s'));
                                $user->save();
                                return redirect()->route('admin.dashboard');
                            }
                        } else {
                            $request->session()->flash('alert-danger', 'Invalid credentials or user inactive');
                            return redirect()->back()->with($request->only('email'));
                        }
                    }
                }

                // If admin is not logged in, show the login form //
                return view('admin.auth.login', $data);
            } catch (Exception $e) // catch block of the try-catch exception
            {
                $request->session()->flash('alert-danger', 'Invalid Credentials!');
                return redirect()->back();
                //return Redirect::Route('admin.login')->with('error', $e->getMessage());
            }
        }
    }

    /*****************************************************/
    # AuthController
    # Function name : logout
    # Author        :
    # Created Date  : 21-05-2019
    # Purpose       : Logout from Admin Panel
    # Params        : Request $request
    /*****************************************************/
    public function logout()
    {
        if (Auth::guard('admin')->logout()) {
            return redirect()->route('admin.login'); // if logout is successful, proceed to login page
        } else {
            return redirect()->route('admin.dashboard'); // if logout fails, redirect tyo dashboard
        }
    }

    /*****************************************************/
    # AuthController
    # Function name : forgetPassword
    # Author        :
    # Created Date  : 31-05-2019
    # Purpose       : forget password, send new password
    # Params        : Request $request
    /*****************************************************/
    public function forgetPassword(Request $request) {
        $data['page_title'] = 'Forget Password';
        $data['panel_title'] = 'Forget Password';
        if ($request->isMethod('POST')) {
            $validationCondition = array(
                'email' => 'required|email'
            );
            $Validator = Validator::make($request->all(), $validationCondition);

            if ($Validator->fails()) {
                // If validation error occurs, load the error listing
                return redirect()->back()->withErrors($Validator);
            } else {
                $user = User::where('email', '=', $request->email)->first();
                if($user){
                    $newPassword = strtotime(date('Y-m-d H:i:s'));
                    $user->password = $newPassword;
                    $user->save();
                    $siteSettings = Helper::getSiteSettings();

                    $data['to_email'] = $user->email;
                    $data['to_name'] = $user->full_name;
                    $data['from_email'] = $siteSettings->from_email;
                    $data['from_name'] = $siteSettings->website_title;
                    \Mail::send('email_templates.reset_password', ['user' => $user, 'newPassword' => $newPassword], function ($m) use ($data) {
                        $m->from($data['from_email'], $data['from_name']);

                        $m->to($data['to_email'], $data['to_name'])->subject('Password reset request');
                    });
                    return redirect()->back()->with('alert-success', "Please check your email inbox, we send you new password");
                } else {
                    return redirect()->back()->with('alert-danger', "This email address is not registered with us");
                }                
            }
        }
        return view('admin.auth.forget_password', $data);       
    }
}