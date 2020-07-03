<?php
/*****************************************************/
# Page/Class name   : HomeController
/*****************************************************/
namespace App\Http\Controllers\site;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Helper;
Use Redirect;
use App;
use Hash;
use \Auth;
use \Response;
use \Validator;
use App\SiteSetting;
use App\Cms;
Use App\User;
Use App\Banner;
use Illuminate\Support\Facades\Session;
use Image;

class HomeController extends Controller
{
    /*****************************************************/
    # Function name : index
    # Params        : 
    /*****************************************************/
    public function index()
    {
        $homeData       = Helper::getData('cms', '1');
        $aboutData      = Helper::getData('cms', '2');
        $trendingData   = Helper::getData('cms', '6');
        $browseByData   = Helper::getData('cms', '8');
        $siteSetting    = Helper::getSiteSettings();

        return view('site.home',[
            'title'                 => $homeData['title'], 
            'keyword'               => $homeData['keyword'], 
            'description'           => $homeData['description'],
            'aboutData'             => $aboutData,
            'trendingData'          => $trendingData,
            'browseByData'          => $browseByData,
            'siteSetting'           => $siteSetting,
            ]);
    }    

    /*****************************************************/
    # Function name : contactus
    # Params        : Request $request
    /*****************************************************/
    public function contactUs(Request $request)
    {
        $currentLang = $lang = App::getLocale();
        $metaData = Helper::getMetaData('cms','contact-us');
        $lang = app()->getLocale();
        if ( Session::has('locale') ) {
            $lang = Session::get('locale');
        }
        $cmsData = Cms::where('id', '5')->with([
            'bannerDetails'=> function($bannerQuery) use ($lang) {
                $bannerQuery->with([
            'local' =>  function($bannerLocalQuery) use ($lang) {
                        $bannerLocalQuery->where('lang_code','=', $lang);
                    }
                ]);
            },
            'local'=> function($query) use ($lang) {
                $query->where('lang_code','=', $lang);
            }
            ])
            ->first();

        $contactWidgetData = Contactwidget::whereNull('deleted_at')->where('status', '1')->with([
            'local' => function($query) use ($lang) {
                $query->where('lang_code','=', $lang);
            }])
            ->get();
           
        $countries = Country::select('id','name','phonecode')->get();

        if ($request->isMethod('POST')) {
            // Checking validation
            $validationCondition = array(
                'first_name'        => 'required|min:2|max:255',
                'last_name'         => 'required|min:2|max:255',
                'company'           =>  'required',
                'phonecode'         => 'required',
                'phone_number'      => 'required',
                'country_code'      => 'required',
                'optional'          => 'required'
            ); // validation condition
            $validationMessages = array(
                'first_name.required'=> trans('custom.please_enter_first_name'),
                'first_name.min'     => trans('custom.first_name_min_length_check'),
                'first_name.max'     => trans('custom.first_name_max_length_check'),
                'last_name.required'=> trans('custom.please_enter_last_name'),
                'last_name.min'     => trans('custom.last_name_min_length_check'),
                'last_name.max'     => trans('custom.last_name_max_length_check'),
                'company.required' =>  trans('custom.enter_your_company_name'),
                'phonecode.required' =>  trans('custom.enter_your_phonecode'),
                'phone_number.required' =>  trans('custom.enter_your_phone_number'),
                'country_code.required' =>  trans('custom.enter_your_country_name'),
                'optional.required' =>  trans('custom.enter_your_optional_name')
            );

            if ($request->email != null) {
                $validationConditionEmail = array(
                    'email' => 'unique:'.(new Contact)->getTable().',email'
                );
                $validationMessagesEmail = array(
                    'email.unique'  => trans('custom.email_unique_check')
                );
                $ValidatorEmail = Validator::make($request->all(), $validationConditionEmail, $validationMessagesEmail);
                if ($ValidatorEmail->fails()) {
                    return redirect()->back()->withErrors($ValidatorEmail)->withInput();
                }
            }

            $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);
            if ($Validator->fails()) {
                return Redirect::back()->withErrors($Validator)->withInput();
            } else {
                $siteSetting = Helper::getSiteSettings();
                
                $newContact = new Contact;
                $newContact->first_name             = trim($request->first_name, ' ');
                $newContact->last_name              = trim($request->last_name, ' ');
                $newContact->full_name              = $newContact->first_name.' '.$newContact->last_name;
                $newContact->company                = trim($request->company, ' ');
                $newContact->phonecode              = trim($request->phonecode, ' ');
                $newContact->phone_number           = trim($request->phone_number, ' ');
                $newContact->country_code           = trim($request->country_code, ' ');
                $newContact->optional               = trim($request->optional, ' ');
                $newContact->email                  = trim($request->email, ' ');
                
                $saveContact = $newContact->save();
                // dd($saveContact);

                if ($saveContact) {                    
                    \Mail::send('email_templates.site.thanks_for_contact',
                    [
                        'user' => $newContact,
                        'app_config' => [
                            'appname'       => $siteSetting->website_title,
                            'appLink'       => Helper::getBaseUrl(),
                            'controllerName'=> 'users',
                            'currentLang'=> $currentLang,
                        ],
                    ], function ($m) use ($siteSetting) {
                        $m->to($siteSetting->to_email, $siteSetting->website_title)->subject('Contact Us - Alaa Acountaning Erp');
                    });
                    $request->session()->flash('alert-success',trans('custom.contactus_success_for_email'));
                    return redirect()->back();
                } else {
                    $request->session()->flash('alert-danger', trans('custom.error_for_user_add'));
                    return redirect()->back();
                }
            }
        }

        return view('site.contact-us',[
            'title'     => $metaData['title'],
            'keyword'   => $metaData['keyword'],
            'description'=>$metaData['description'],
            'cmsData'   => $cmsData,
            'contactWidgetData' =>  $contactWidgetData,
            'countries' =>  $countries,
        ]);
    }

   
}