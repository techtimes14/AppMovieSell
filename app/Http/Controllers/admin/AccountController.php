<?php
/*****************************************************/
# Page/Class name   : AccountController
# Purpose           : Admin Account Management
/*****************************************************/
namespace App\Http\Controllers\admin;

use App;
use App\Http\Controllers\Controller;
use App\SiteSetting;
use App\User;
use Helper;
use Image;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Redirect;
use Validator;
use View;

class AccountController extends Controller
{
    /*****************************************************/
    # Function name : dashboard
    # Created Date  : 26-04-2020
    # Purpose       : After login admin will see dashboard page
    /*****************************************************/
    public function dashboard()
    {
        $data['page_title'] = 'Dashboard';
        $data['panel_title'] = 'Dashboard';

        return view('admin.account.dashboard', $data);
    }

    /*****************************************************/
    # AccountController
    # Function name : editProfile
    # Author        :
    # Created Date  : 13-03-2020
    # Purpose       : Update admin profile values
    # Params        : Request $request
    /*****************************************************/
    public function editProfile(Request $request)
    {
        $data['page_title'] = 'Edit Profile';
        $data['panel_title'] = 'Edit Profile';

        try
        {
            $adminDetail = Auth::guard('admin')->user();
            $data['adminDetail'] = $adminDetail;
            if ($request->isMethod('POST')) {

                // Checking validation
                $validationCondition = array(
                    'full_name' => 'required|min:2|max:255',
                    // 'phone_no' => 'required|regex:/^(?:[+]9)?[0-9]+$/|unique:' . (new User)->getTable() . ',phone_no,' . $adminDetail->id,
                    'phone_no' => 'required|unique:' . (new User)->getTable() . ',phone_no,' . $adminDetail->id,
                ); // validation condition
                $validationMessages = array(
                    'full_name.required' => 'Please enter first name',
                    'full_name.min' => 'First name should be should be at least 2 characters',
                    'full_name.max' => 'First name should not be more than 255 characters',
                    'phone_no.required' => 'Please enter valid phone number',
                );
                $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);

                if ($Validator->fails()) {
                    return redirect()->route('admin.edit-profile')->withErrors($Validator);
                } else {
                    $updateAdminData = array(
                        'full_name' => $request->full_name,
                        'phone_no' => $request->phone_no,
                    );
                    $saveAdminData = User::where('id', $adminDetail->id)->update($updateAdminData);
                    if ($saveAdminData) {
                        $request->session()->flash('alert-success', 'Profile data has been updated successfully');
                        return redirect()->back();
                    } else {
                        $request->session()->flash('alert-danger', 'An error took place while updating the profile');
                        return redirect()->back();
                    }
                }
            }

            return view('admin.account.edit_profile', $data);

        } catch (Exception $e) {
            return redirect()->route('admin.edit-profile')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # AccountController
    # Function name : changePassword
    # Author        :
    # Created Date  : 13-03-2020
    # Purpose       : Change admin password
    # Params        : Request $request
    /*****************************************************/
    public function changePassword(Request $request)
    {
        $data['page_title'] = 'Change password';
        $data['panel_title'] = 'Change password';

        try
        {
            if ($request->isMethod('POST')) {
                $validationCondition = array(
                    'current_password' => 'required|min:8',
                    'password' => 'required|regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                    'confirm_password' => 'required|regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/|same:password',
                );
                $validationMessages = array(
                    'current_password.required' => 'Please enter current password',
                    'password.required' => 'Please enter password',
                    'password.regex' => 'Password should be minimum 8 characters, alphanumeric and special character',
                    'confirm_password.required' => 'Please enter confirm password',
                    'confirm_password.regex' => 'Confirm Password should be minimum 8 characters, alphanumeric and special character',
                    'confirm_password.same' => 'Confirm password should be same as new password',
                );
                $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->route('admin.change-password')->withErrors($Validator);
                } else {
                    $adminDetail = Auth::guard('admin')->user();
                    $user_id = Auth::guard('admin')->user()->id;
                    $hashed_password = $adminDetail->password;

                    // check if current password matches with the saved password
                    if (Hash::check($request->current_password, $hashed_password)) {
                        $adminDetail->password = $request->password;
                        $updatePassword = $adminDetail->save();

                        if ($updatePassword) {
                            $request->session()->flash('alert-success', 'Password has been updated successfully');
                            return redirect()->back();
                        } else {
                            $request->session()->flash('alert-danger', 'An error occurred while updating the password');
                            return redirect()->back();
                        }
                    } else {
                        $request->session()->flash('alert-danger', 'Current password does not match');
                        return redirect()->back();
                    }
                }
            }
            return view('admin.account.change_password', $data);
        } catch (Exception $e) {
            return Redirect::Route('change_password')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # AccountController
    # Function name : siteSettings
    # Author        :
    # Created Date  : 23-05-2019
    # Purpose       : Update admin site settings
    # Params        : Request $request
    /*****************************************************/
    public function siteSettings(Request $request)
    {
        try
        {
            if ($request->isMethod('POST')) {                
                // Checking validation
                $validationCondition = array(
                    'from_email'    => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',
                    'to_email'      => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',
                    'website_title' => 'required|min:2|max:255',
                    'website_link'  => 'required|min:2|max:255',
                ); // validation condition
                $validationMessages = array(
                    'from_email.required'    => 'Please enter from email',
                    'from_email.regex'       => 'Please enter a valid email',
                    'to_email.required'      => 'Please enter to email',
                    'to_email.regex'         => 'Please enter a valid email',
                    'website_title.required' => 'Please enter website title',
                    'website_title.min'      => 'Website title should be should be at least 2 characters',
                    'website_title.max'      => 'Website title should not be more than 255 characters',
                    'website_link.required'  => 'Please enter website link',
                    'website_link.min'       => 'Website link should be should be at least 2 characters',
                    'website_link.max'       => 'Website link should not be more than 255 characters'
                );
                $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);

                if ($Validator->fails()) {
                    return redirect()->route('admin.site-settings')->withErrors($Validator);
                } else {                    
                    $siteSettings = SiteSetting::first();
                    if ($siteSettings == null) {
                        $newSiteSetting                           = new SiteSetting;
                        $newSiteSetting->from_email               = $request->from_email;
                        $newSiteSetting->to_email                 = $request->to_email;
                        $newSiteSetting->website_title            = $request->website_title;
                        $newSiteSetting->website_link             = $request->website_link;
                        $newSiteSetting->facebook_link            = $request->facebook_link;
                        $newSiteSetting->linkedin_link            = $request->linkedin_link;
                        $newSiteSetting->youtube_link             = $request->youtube_link;
                        $newSiteSetting->googleplus_link          = $request->googleplus_link;
                        $newSiteSetting->twitter_link             = $request->twitter_link;
                        $newSiteSetting->rss_link                 = $request->rss_link;
                        $newSiteSetting->pinterest_link           = $request->pinterest_link;
                        $newSiteSetting->instagram_link           = $request->instagram_link;
                        $newSiteSetting->default_meta_title       = $newSiteSetting->default_meta_title;
                        $newSiteSetting->default_meta_keywords    = $newSiteSetting->default_meta_keywords;
                        $newSiteSetting->default_meta_description = $newSiteSetting->default_meta_description;
                        $newSiteSetting->address                  = $request->address;
                        $newSiteSetting->phone_no                 = $request->phone_no;
                        $newSiteSetting->home_short_description   = $request->home_short_description;
                        
                        $saveData = $newSiteSetting->save();
                        
                        if ($saveData) {
                            $request->session()->flash('alert-success', 'Site settings has been added successfully');
                        } else {
                            $request->session()->flash('alert-danger', 'An error occurred while adding the site settings');
                        }
                        return redirect()->back();
                    } else {
                        $updateData = array(
                            'from_email'               => $request->from_email,
                            'to_email'                 => $request->to_email,
                            'website_title'            => $request->website_title,
                            'website_link'             => $request->website_link,
                            'facebook_link'            => $request->facebook_link,
                            'linkedin_link'            => $request->linkedin_link,
                            'youtube_link'             => $request->youtube_link,
                            'googleplus_link'          => $request->googleplus_link,
                            'twitter_link'             => $request->twitter_link,
                            'rss_link'                 => $request->rss_link,
                            'pinterest_link'           => $request->pinterest_link,
                            'instagram_link'           => $request->instagram_link,
                            'default_meta_title'       => $request->default_meta_title,
                            'default_meta_keywords'    => $request->default_meta_keywords,
                            'default_meta_description' => $request->default_meta_description,
                            'address'                  => $request->address,
                            'phone_no'                 => $request->phone_no,
                            'home_short_description'   => $request->home_short_description,
                        );
                        $save = SiteSetting::where('id', $siteSettings->id)->update($updateData);

                        $request->session()->flash('alert-success', 'Site settings has been updated successfully');
                        return redirect()->back();
                    }
                }
            }
            $data = [
                'page_title'               => 'Site Settings',
                'panel_title'              => 'Site Settings',
                'from_email'               => '',
                'to_email'                 => '',
                'website_title'            => '',
                'website_link'             => '',
                'facebook_link'            => '',
                'linkedin_link'            => '',
                'youtube_link'             => '',
                'googleplus_link'          => '',
                'twitter_link'             => '',
                'rss_link'                 => '',
                'pinterest_link'           => '',
                'instagram_link'           => '',
                'default_meta_title'       => '',
                'default_meta_keywords'    => '',
                'default_meta_description' => '',
                'address'                  => '',
                'phone_no'                 => '',
                'home_short_description'   => '',
            ];

            $siteSettings = SiteSetting::first();
            if ($siteSettings != null) {
                $data['from_email']               = $siteSettings->from_email;
                $data['to_email']                 = $siteSettings->to_email;
                $data['website_title']            = $siteSettings->website_title;
                $data['website_link']             = $siteSettings->website_link;
                $data['facebook_link']            = $siteSettings->facebook_link;
                $data['linkedin_link']            = $siteSettings->linkedin_link;
                $data['youtube_link']             = $siteSettings->youtube_link;
                $data['googleplus_link']          = $siteSettings->googleplus_link;
                $data['twitter_link']             = $siteSettings->twitter_link;
                $data['rss_link']                 = $siteSettings->rss_link;
                $data['pinterest_link']           = $siteSettings->pinterest_link;
                $data['instagram_link']           = $siteSettings->instagram_link;
                $data['default_meta_title']       = $siteSettings->default_meta_title;
                $data['default_meta_keywords']    = $siteSettings->default_meta_keywords;
                $data['default_meta_description'] = $siteSettings->default_meta_description;
                $data['address']                  = $siteSettings->address;
                $data['phone_no']                 = $siteSettings->phone_no;
                $data['home_short_description']   = $siteSettings->home_short_description;
            }
            
            return view('admin.account.site_settings')->with(['siteSettings' => $siteSettings, 'data' => $data]);
        } catch (Exception $e) {
            return redirect()->route('admin.site-settings')->with('error', $e->getMessage());
        }
    }
}