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
Use App\Service;
use App\Contact;
use App\Contactwidget;
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
            'pageTitle'     => $aboutData['title'],
            'title'         => $homeData['meta_title'], 
            'keyword'       => $homeData['meta_keyword'], 
            'description'   => $homeData['meta_description'],
            'aboutData'     => $aboutData,
            'trendingData'  => $trendingData,
            'browseByData'  => $browseByData,
            'siteSetting'   => $siteSetting,
            ]);
    }

    /*****************************************************/
    # Function name : aboutUs
    # Params        : 
    /*****************************************************/
    public function aboutUs()
    {
        $aboutData      = Helper::getData('cms', '2');
        
        return view('site.about',[
            'pageTitle'     => $aboutData['title'],
            'title'         => $aboutData['meta_title'],
            'keyword'       => $aboutData['meta_keyword'], 
            'description'   => $aboutData['meta_description'],
            'aboutData'     => $aboutData,
        ]);
    }

    /*****************************************************/
    # Function name : services
    # Params        : 
    /*****************************************************/
    public function services()
    {
        $serviceData = Helper::getData('cms', '3');
        $seviceList = Service::where(['status' => '1'])->whereNull('deleted_at')->get();
        
        return view('site.service',[
            'pageTitle'     => $serviceData['title'],
            'title'         => $serviceData['meta_title'],
            'keyword'       => $serviceData['meta_keyword'], 
            'description'   => $serviceData['meta_description'],
            'serviceData'   => $serviceData,
            'seviceList'    => $seviceList,
        ]);
    }

    /*****************************************************/
    # Function name : contactUs
    # Params        : Request $request
    /*****************************************************/
    public function contactUs(Request $request)
    {
        $contactData = Helper::getData('cms', '5');
        $contactWidgetData = Contactwidget::whereNull('deleted_at')->where('status', '1')
            ->get();
        $cmsData = Cms::where('id', 5)->first();

        if ($request->isMethod('POST')) {
            // Checking validation
            $validationCondition = array(
                'first_name'        => 'required|min:2|max:255',
                'last_name'         => 'required|min:2|max:255',
                'email'             => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',
                'phone_number'      => 'required',
                'subject'           => 'required',
            ); // validation condition
            $validationMessages = array(
                'first_name.required'=> 'Please enter first name',
                'first_name.min'     => 'Please enter first name minimum 2',
                'first_name.max'     => 'Please enter first name maximum 255',
                'last_name.required'=> 'Please enter last name' ,
                'last_name.min'     => 'Please enter last name minimum 2',
                'last_name.max'     => 'Please enter last name maximum 255',
                'email.required' =>  'Please enter email',
                'phone_number.required' =>  'Please enter phone number',
                'subject.required'  => 'Please enter subject'
            );

            

            $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);
            if ($Validator->fails()) {
                return Redirect::back()->withErrors($Validator)->withInput();
            } else {
                $siteSetting = Helper::getSiteSettings();
                
                $newContact = new Contact;
                $newContact->first_name             = trim($request->first_name, ' ');
                $newContact->last_name              = trim($request->last_name, ' ');
                $newContact->full_name              = $newContact->first_name.' '.$newContact->last_name;
                $newContact->phone_number           = trim($request->phone_number, ' ');
                $newContact->subject                = trim($request->subject, ' ');
                $newContact->email                  = trim($request->email, ' ');
                
                $saveContact = $newContact->save();
                

                if ($saveContact) {                    
                    \Mail::send('email_templates.site.thanks_for_contact',
                    [
                        'user' => $newContact,
                        'app_config' => [
                            'appname'       => $siteSetting->website_title,
                            'appLink'       => Helper::getBaseUrl(),
                            'controllerName'=> 'users',
                        ],
                    ], function ($m) use ($siteSetting) {
                        $m->to($siteSetting->to_email, $siteSetting->website_title)->subject('Contact Us - '.$siteSetting->website_title);
                    });
                    $request->session()->flash('alert-success','Thank you for contacting with us');
                    return redirect()->back();
                } else {
                    $request->session()->flash('alert-danger', trans('custom.please_try_again'));
                    return redirect()->back();
                }
            }
        }

        return view('site.contact',[
            'pageTitle'     => $contactData['title'],
            'title'         => $contactData['meta_title'],
            'keyword'       => $contactData['meta_keyword'],
            'description'   => $contactData['meta_description'],
            'contactData'   => $contactData,
            'contactWidgetData' => $contactWidgetData,
            'cmsData'       => $cmsData,
        ]);
    }

    /*****************************************************/
    # Function name : legal
    # Params        : 
    /*****************************************************/
    public function legal()
    {
        $legaleData      = Helper::getData('cms', '4');
        
        return view('site.legal',[
            'pageTitle'     => $legaleData['title'], 
            'title'         => $legaleData['meta_title'], 
            'keyword'       => $legaleData['meta_keyword'], 
            'description'   => $legaleData['meta_description'],
            'legaleData'    => $legaleData,
        ]);
    }

   
}