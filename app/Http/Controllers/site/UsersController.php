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
use App\Video;
use App\Board;
use App\FavouriteVideo;
use App;

class UsersController extends Controller
{
    /*****************************************************/
    # Function name : register
    # Params        : 
    /*****************************************************/
    public function register( Request $request )
    {
        $response = [];
        if ($request->isMethod('POST')) {
            $validationCondition = array(
                'first_name'        => 'required|min:2|max:255',
                'last_name'         => 'required|min:2|max:255',
                'email_register'    => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/|unique:'.(new User)->getTable().',email',
                'password_register' => 'required|regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                'agree'             =>  'required',
            ); 
            $validationMessages = array(
                'first_name.required'       => 'Plese enter first name',
                'first_name.min'            => 'First name should be atleast 2 characters',
                'first_name.max'            => 'First name must not be more than 255 characters',
                'last_name.required'        => 'Plese enter last name',
                'last_name.min'             => 'Last name should be atleast 2 characters',
                'last_name.max'             => 'Last name must not be more than 255 characters',
                'email_register.required'   => 'Plese enter email',
                'email_register.regex'      => 'Please enter valid email',
                'email_register.unique'     => 'Email should be unique',
                'password.required'         => 'Please enter password',
                'password.regex'            => 'Password should be minimum 8 characters, alphanumeric and special character',
            );

            $Validation = Validator::make($request->all(), $validationCondition, $validationMessages);
            if ($Validation->fails()) {
                $response['has_error'] = 1;
                $response['error'] = $Validation->errors();
                $response['msg'] = 'This email is already registered with us';
            } else {
                $newUser = new User;
                $newUser->first_name    = trim($request->first_name, ' ');
                $newUser->last_name     = trim($request->last_name, ' ');
                $newUser->full_name     = $newUser->first_name.' '.$newUser->last_name;
                $newUser->email         = trim($request->email_register, ' ');                
                $newUser->password      = $request->password_register;
                $newUser->status        = '1';
                $newUser->agree         = isset($request->agree) ? $request->agree : 0;
                $saveUser = $newUser->save();
                if ($saveUser) {
                    $siteSetting = Helper::getSiteSettings();

                    \Mail::send('email_templates.site.verification',
                    [
                        'user' => $newUser,
                        'password'      => $request->password_register,
                        'siteSetting'   => $siteSetting,
                        'app_config' => [
                        'appname'       => $siteSetting->website_title,
                        'appLink'       => Helper::getBaseUrl(),
                        ],
                    ], function ($m) use ($newUser) {
                        $m->to($newUser->email, $newUser->full_name)->subject('Registration Successful - Stream Fit');
                    });

                    $response['has_error'] = 0;
                    $response['msg'] = 'Your registration was successful';
                } else {
                    $response['has_error'] = 1;
                    $response['msg'] = 'Something went wrong, please try again later';
                }
            }
        }        
        echo json_encode($response);
        // exit(0);
    }

    /*****************************************************/
    # Function name : login
    # Params        : 
    /*****************************************************/
    public function login( Request $request )
    {
        $response = [];
        if ($request->isMethod('POST')) {
            $validationCondition = array(
                'email'     => 'required',
                'password'  => 'required'
            );
            $validationMessages = array(
                'email.required'    => 'Please enter email',
                'password.required' => 'Please enter password',
            );

            $Validation = Validator::make($request->all(), $validationCondition, $validationMessages);
            if ($Validation->fails()) {
                $response['has_error'] = 1;
                $response['error'] = $Validation->errors();
                $response['msg'] = 'Please enter your email and password';
            } else {
                if ($request->email && $request->password) {
                    if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
                        $user = Auth::user();
                        if ($user->status == 0) {
                            $response['has_error'] = 1;
                            $response['msg'] = 'Inactive user, please contact with administrator';
                            Auth::guard('web')->logout();
                        } else if ($user->role_id) {
                            $response['has_error'] = 1;
                            $response['msg'] = 'You are not authorised';
                            Auth::guard('web')->logout();
                        } else {
                            $userData                = Auth::user();
                            $userData->lastlogintime = strtotime(date('Y-m-d H:i:s'));
                            $userData->save();

                            $response['has_error'] = 0;
                            $response['msg'] = 'Login successful';
                            $response['temp_password'] = 0;
                            if ($userData->password_reset_token != null) {
                                $response['temp_password'] = 1;
                            }
                        }
                    } else {
                        $response['has_error'] = 1;
                        $response['msg'] = 'Invalid email or password';
                    }
                } else {
                    $response['has_error'] = 1;
                    $response['msg'] = 'Please enter your email and password';
                }
            }
        }        
        echo json_encode($response);
        // exit(0);
    }

    /*****************************************************/
    # Function name : forgotPassword
    # Params        : 
    /*****************************************************/
    public function forgotPassword( Request $request )
    {
        $response = [];
        if ($request->isMethod('POST')) {
            $validationCondition = array(
                'forgot_email'    => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',
            ); 
            $validationMessages = array(
                'forgot_email.required'   => 'Plese enter email',
                'forgot_email.regex'      => 'Please enter valid email',
            );

            $Validation = Validator::make($request->all(), $validationCondition, $validationMessages);
            if ($Validation->fails()) {
                $response['has_error'] = 1;
                $response['error'] = $Validation->errors();
                $response['msg'] = 'This email is not registered with us';
            } else {
                $emailExist = User::where(['email' => $request->forgot_email])->whereNull('deleted_at')->first();
                if ($emailExist != null) {
                    if ($emailExist->status == '0') {
                        $response['has_error'] = 1;
                        $response['msg'] = 'This user inactive';

                    } else {
                        $randomPassword = Helper::generateRandomPassword();

                        $emailExist->password               = $randomPassword;
                        $emailExist->password_reset_token   = strtotime(date('Y-m-d H:i:s'));
                        $saveUser = $emailExist->save();
                        if ($saveUser) {
                            $siteSetting = Helper::getSiteSettings();

                            \Mail::send('email_templates.site.forgot_password',
                            [
                                'user'          => $emailExist,
                                'password'      => $randomPassword,
                                'siteSetting'   => $siteSetting,
                                'app_config'    => [
                                    'appname'       => $siteSetting->website_title,
                                    'appLink'       => Helper::getBaseUrl(),
                                ],
                            ], function ($m) use ($emailExist) {
                                $m->to($emailExist->email, $emailExist->full_name)->subject('Forgot Password - Stream Fit');
                            });

                            $response['has_error'] = 0;
                            $response['msg'] = 'Please check your email for temporary password';
                        } else {
                            $response['has_error'] = 1;
                            $response['msg'] = 'Something went wrong, please try again later';
                        }
                    }
                } else {
                    $response['has_error'] = 1;
                    $response['msg'] = 'This user either not registered with us or deleted';
                }
            }
        }        
        echo json_encode($response);
        // exit(0);
    }

    /*****************************************************/
    # Function name : editProfile
    # Params        : Request $request
    /*****************************************************/
    public function editProfile(Request $request)
    {
        try
        {
            $cmsData = Helper::getData();
            $userDetail = Auth::guard('web')->user();            
            $data['userDetail'] = $userDetail;

            if ($request->isMethod('POST')) {
                // dd($request);
                $validationCondition = array(
                    'first_name'    => 'required|min:2|max:255',
                    'last_name'     => 'required|min:2|max:255',
                    'email'         => 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/|unique:'.(new User)->getTable().',email,'.Auth::user()->id,
                    'phone_no'      => 'required|unique:'.(new User)->getTable().',phone_no,'.Auth::user()->id,
                    'gender'        =>  'required',
                );
                $validationMessages = array(
                    'first_name.required' => 'Please enter first name',
                    'first_name.min' => 'First name should be atleast 2 characters',
                    'first_name.max' => 'First name must not be more than 255 characters',
                    'last_name.required' => 'Please enter last name',
                    'last_name.min' => 'Last name should be atleast 2 characters',
                    'last_name.max' => 'Last name must not be more than 255 characters',
                    'email.required' => 'Please enter email',
                    'email.regex' => 'Please enter valid email',
                    'email.unique' => 'Please enter unique email',
                    'phone_no.required' => 'Please enter phone number',
                    'phone_no.unique' => 'Please enter unique phone number',
                    'gender.required' => 'Please select gender',
                );
                $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->route('site.users.edit-profile')->withErrors($Validator)->withInput();
                } else {
                    if ($request->current_password != '') {
                        $valiPass = array(
                            'current_password'  => 'regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                            'password'          => 'regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                            'confirm_password'  => 'regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/|same:password',
                        );
                        $validaPass = array(
                            'current_password.regex' => 'Password should be minimum 8 characters, alphanumeric and special character',
                            'password.regex' => 'Password should be minimum 8 characters, alphanumeric and special character',
                            'confirm_password.regex' => 'Password should be minimum 8 characters, alphanumeric and special character',
                            'confirm_password.same' => 'Confirm password should be same as new password',
                        );
                        $ValidatorPass = Validator::make($request->all(), $valiPass, $validaPass);
                        if ($ValidatorPass->fails()) {
                            return redirect()->route('site.users.edit-profile')->withErrors($ValidatorPass)->withInput();
                        } else {
                            $userDetail = Auth::guard('web')->user();
                            $hashedPassword = $userDetail->password;

                            // check if current password matches with the saved password
                            if (Hash::check($request->current_password, $hashedPassword)) {
                                $updateUserData['password'] = Hash::make($request->password);

                                $updateUserData['password_reset_token'] = null;
                            } else {
                                $request->session()->flash('alert-danger', 'Current password doesn\'t match');
                                return redirect()->back()->withInput();
                            }
                        }
                    }
                    $updateUserData['first_name']   = $request->first_name;
                    $updateUserData['last_name']    = $request->last_name;
                    $updateUserData['full_name']    = ucwords($request->first_name).' '.ucwords($request->last_name);
                    $updateUserData['email']        = $request->email;
                    $updateUserData['phone_no']     = $request->phone_no;
                    $updateUserData['gender']       = $request->gender;
                    
                    $saveUserData = User::where('id', $userDetail->id)->update($updateUserData);
                    if ($saveUserData) {
                        Auth::guard('web')->loginUsingId($userDetail->id);                        
                        $request->session()->flash('alert-success', 'Profile updated successfully');
                        return redirect()->back();
                    } else {
                        $request->session()->flash('alert-danger', 'Something went wrong, please try again later');
                        return redirect()->back();
                    }
                }
            }
            return view('site.user.edit_profile',[
                'title'     => $cmsData['title'],
                'keyword'   => $cmsData['keyword'],
                'description'=>$cmsData['description'],
                'cmsData'   => $cmsData,
                'userDetail'=> $userDetail,
            ]);

        } catch (Exception $e) {
            return redirect()->route('site.users.edit-profile')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # Function name : profileVideos
    # Params        : Request $request
    /*****************************************************/
    public function profileVideos(Request $request)
    {
        $cmsData   = Helper::getData('cms', '10');
        $favVideoIds = [];
        $profileVideosList = FavouriteVideo::where(['user_id' => Auth::user()->id])
                                            ->whereHas('videoDetails', function ($query) {
                                                $query->where('status', '1');
                                                $query->whereNull('deleted_at');
                                            })
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(Helper::PROFILE_VIDEO_LIMIT);

        // Loggedin user faviourite video ids
        if (Auth::user()) {
            $favVideoIds = Helper::userFavouiteVideoIds();
        }
                                            
        return view('site.user.profile_videos',[
            'title'                 => $cmsData['title'],
            'keyword'               => $cmsData['keyword'], 
            'description'           => $cmsData['description'],
            'cmsData'               => $cmsData,
            'profileVideosList'     => $profileVideosList,
            'favVideoIds'           => $favVideoIds,
        ]);
    }

    /*****************************************************/
    # Function name : myFavourite
    # Params        : Request $request
    /*****************************************************/
    public function myFavourite(Request $request)
    {
        $cmsData   = Helper::getData('cms', '11');
        $favVideoIds = [];
        $boardList = Board::where(['user_id' => Auth::user()->id, 'status' => '1'])->get();
        $favouriteBoardVideoList = FavouriteVideo::where(['user_id' => Auth::user()->id, 'board_id' => '0'])
                                                    ->whereHas('videoDetails', function ($query) {
                                                        $query->where('status', '1');
                                                        $query->whereNull('deleted_at');
                                                    })
                                                    ->orderBy('created_at', 'desc')
                                                    ->paginate(Helper::MY_FAVOURITE_VIDEO_LIMIT);
        // Loggedin user faviourite video ids
        if (Auth::user()) {
            $favVideoIds = Helper::userFavouiteVideoIds();
        }
        
        return view('site.user.my_favourite',[
            'title'                 => $cmsData['title'],
            'keyword'               => $cmsData['keyword'], 
            'description'           => $cmsData['description'],
            'cmsData'               => $cmsData,
            'boardList'             => $boardList,
            'favouriteBoardVideoList'=> $favouriteBoardVideoList,
            'favVideoIds'           => $favVideoIds,
        ]);
    }
    
    /*****************************************************/
    # Function name : favouriteBoardList
    # Params        : Request $request
    /*****************************************************/
    public function favouriteBoardList($encryptedBoardId)
    {
        $cmsData   = Helper::getData('cms', '11');
        $favVideoIds = [];
        $boardId = Helper::customEncryptionDecryption($encryptedBoardId, 'decrypt');
        $boardData = Board::where(['id' => $boardId])->first();
        $favouriteBoardVideoList = FavouriteVideo::where(['user_id' => Auth::user()->id, 'board_id' => $boardId])
                                                    ->whereHas('videoDetails', function ($query) {
                                                        $query->where('status', '1');
                                                        $query->whereNull('deleted_at');
                                                    })
                                                    ->orderBy('created_at', 'desc')
                                                    ->paginate(Helper::FAVOURITE_BOARD_VIDEO_LIMIT);

        // Loggedin user faviourite video ids
        if (Auth::user()) {
            $favVideoIds = Helper::userFavouiteVideoIds();
        }
        
        return view('site.user.favourite_board',[
            'title'                 => $cmsData['title'],
            'keyword'               => $cmsData['keyword'], 
            'description'           => $cmsData['description'],
            'cmsData'               => $cmsData,
            'boardData'             => $boardData,
            'favouriteBoardVideoList'=> $favouriteBoardVideoList,
            'favVideoIds'           => $favVideoIds,
        ]);
    }

    /*****************************************************/
    # Function name : createBoard
    # Params        : Request $request
    /*****************************************************/
    public function createBoard(Request $request)
    {
        $response['has_error'] = 1;
        $response['msg'] = 'Something went wrong, please try again later';
        if ($request->isMethod('POST')) {
            $validationCondition = array(
                'board_name' => 'required|min:3|max:20',
            ); 
            $validationMessages = array(
                'board_name.required'   => 'Plese enter board name',
                'board_name.min'        => 'Board name should be atleast 3 characters',
                'board_name.max'        => 'Board name must not be more than 20 characters',
            );

            $Validation = Validator::make($request->all(), $validationCondition, $validationMessages);
            if ($Validation->fails()) {
                $response['has_error'] = 1;
                $response['error'] = $Validation->errors();
                $response['msg'] = 'Board name should be between 3 - 20 characters';
            } else {
                $countBoard = Board::where(['user_id' => Auth::user()->id])->count();
                if ($countBoard < Helper::MAX_LIMIT_CREATE_BOARD) {
                    $newBoard = new Board;
                    $newBoard->title    = trim($request->board_name, ' ');
                    $newBoard->user_id  = Auth::user()->id;

                    $existCount = Board::where(['user_id' => Auth::user()->id, 'title' => $newBoard->title])->count();
                    if ($existCount > 0) {
                        $response['has_error'] = 1;
                        $response['msg'] = 'This board is already exist';
                    } else {
                        $saveBoard = $newBoard->save();
                        if ($saveBoard) {
                            $response['has_error'] = 0;
                            $response['msg'] = 'Board created successfully';
                        } else {
                            $response['has_error'] = 1;
                            $response['msg'] = 'Something went wrong, please try again later';
                        }
                    }   
                } else {
                    $response['has_error'] = 1;
                    $response['msg'] = 'You can create maximum '.Helper::MAX_LIMIT_CREATE_BOARD.' board';
                }
            }
        }        
        echo json_encode($response);
        // exit(0);
    }

    /*****************************************************/
    # Function name : makeFavourite
    # Params        : Request $request
    /*****************************************************/
    public function makeFavourite(Request $request)
    {
        $response['has_error'] = 1;
        $response['msg'] = 'Something went wrong, please try again later';
        if ($request->isMethod('POST')) {
            $validationCondition = array(
                'fav_video_id'  => 'required',
                'board'         => 'required',
            ); 
            $validationMessages = array(
                'fav_video_id.required' => 'Please select video',
                'board.required'        => 'Please select board',
            );

            $Validation = Validator::make($request->all(), $validationCondition, $validationMessages);
            if ($Validation->fails()) {
                $response['has_error'] = 1;
                $response['error'] = $Validation->errors();
                $response['msg'] = 'Please select folder/board';
            } else {
                $favVideoId = $request->fav_video_id;
                if ($request->board == 'Save' || $request->board == 'save') {
                    $board  = 0;
                } else {
                    $board  = $request->board;
                }

                // If exist the delete
                FavouriteVideo::where(['user_id' => Auth::user()->id, 'video_id' => $favVideoId])->delete();

                if ($favVideoId != '') {
                    $newFavouriteVideo = new FavouriteVideo;
                    $newFavouriteVideo->user_id     = Auth::user()->id;
                    $newFavouriteVideo->video_id    = $favVideoId;
                    $newFavouriteVideo->board_id    = $board;
                    $saveFavouriteVideo = $newFavouriteVideo->save();
                    if ($saveFavouriteVideo) {
                        $response['has_error'] = 0;
                        $response['msg'] = 'Video added to favourite successfully';
                    } else {
                        $response['has_error'] = 1;
                        $response['msg'] = 'Something went wrong, please try again later';
                    }
                } else {
                    $response['has_error'] = 1;
                    $response['msg'] = 'Something went wrong, please try again later';
                }
            }
        }        
        echo json_encode($response);
        // exit(0);
    }

    /*****************************************************/
    # Function name : removeFavourite
    # Params        : Request $request
    /*****************************************************/
    public function removeFavourite(Request $request)
    {
        $response['has_error'] = 1;
        $response['msg'] = 'Something went wrong, please try again later';
        if ($request->isMethod('POST')) {
            $validationCondition = array(
                'video_id'  => 'required',
            ); 
            $validationMessages = array(
                'video_id.required' => 'Something went wrong, please try again later',
            );

            $Validation = Validator::make($request->all(), $validationCondition, $validationMessages);
            if ($Validation->fails()) {
                $response['has_error'] = 1;
                $response['error'] = $Validation->errors();
                $response['msg'] = 'Something went wrong, please try again later';
            } else {
                $videoId = $request->video_id;
                
                // If exist the delete
                FavouriteVideo::where(['user_id' => Auth::user()->id, 'video_id' => $videoId])->delete();

                $response['has_error'] = 0;
                $response['msg'] = 'Video has been removed from board successfully';
            }
        }        
        echo json_encode($response);
        // exit(0);
    }

    /*****************************************************/
    # Function name : logout
    # Params        : 
    /*****************************************************/
    public function logout()
    {
        if (Auth::guard('web')->logout()) {
            return redirect()->route('site.home');
        } else {
            return redirect()->route('site.users.edit-profile');
        }
    }

}
