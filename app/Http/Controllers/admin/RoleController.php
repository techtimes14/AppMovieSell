<?php
/*****************************************************/
# RoleController
# Page/Class name   : RoleController
# Author            :
# Created Date      : 27-05-2019
# Functionality     : list
# Purpose           : role management functions
/*****************************************************/
namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Role;
use App\RolePage;
use App\RolePermission;
use App\User;
use App\Http\Helpers\Helper;
use AdminHelper;

class RoleController extends Controller
{
    /*****************************************************/
    # RoleController
    # Function name : list
    # Author        :
    # Created Date  : 27-05-2019
    # Purpose       : To get role permission list
    # Params        :
    /*****************************************************/
    public function list() {
        $roleListData = Role::where("id","!=",'1')->where('is_admin','=','1')->paginate(AdminHelper::ADMIN_ROLE_LIMIT);;
        $pageData['page_title']  = 'Role List';
        $pageData['panel_title'] = 'Role List';
        $pageData['listData']    = $roleListData;
        return view('admin.roles.list',$pageData);
    }

    /*****************************************************/
    # RoleController
    # Function name : addEdit
    # Author        :
    # Created Date  : 30-05-2019
    # Purpose       : To add edit role
    # Params        :
    /*****************************************************/
    public function addEdit(Request $request) {

        $roleName = $request->role_name;
        $roleId = $request->roleId;
        if ($roleName) {
            if ($roleId == "") { //add role
                $role = new Role();
                
            } else {
                $role = Role::where("id", "=" ,$roleId)->first();
            }
            $role->name = $roleName;
            $role->slug = Helper::generateUniqueSlug(new Role, trim($roleName, ' '));
            $role->is_admin = '1';
            $role->save();
            return \Redirect::route('admin.role.list')->with('alert-success', 'Role is saved successfully');
        }
    }

    /*****************************************************/
    # RoleController
    # Function name : permission
    # Author        :
    # Created Date  : 30-05-2019
    # Purpose       : To set role permission
    # Params        : roleType
    /*****************************************************/
    public function permission($roleType, Request $request) {
        
        $routeCollection = \Route::getRoutes();
        // dd($routeCollection);
        $list = [];
        $roleDetails = Role::where("slug", "=", $roleType)->first();
        $rolePermissionList = $roleDetails->permissions;
        $existingPermissions = [];
        if ($rolePermissionList) {
            foreach ($rolePermissionList as $rolePermissions) {
                $existingPermissions[] =  $rolePermissions->page->routeName;
            }
        }
        foreach($routeCollection as $route) {
            $namespace = $route->action['namespace'];
            if (!in_array("POST", $route->methods)  && strstr($namespace,'\admin') != ''){
                $group = str_replace("admin.", "", $route->getName());

                $group = strstr($group, ".", true);
                if (!array_key_exists($group, $list)) {
                    $list[$group] = [];
                }
                array_push($list[$group], [
                    "method" => $route->methods,
                    "uri" => $route->uri,
                    "path"=> $route->getName(),
                    "group_name" => ($group)?$group:'',
                    "middleware"=>$route->middleware(),
                    "active"=> (in_array($route->getName(), $existingPermissions))?1:0
                ]);

            }
        }
        //dd($list);
        $pageData = [];
        $pageData['page_title']  = 'Permission List';
        $pageData['panel_title'] = 'Permission List';
        $pageData['roleDetails'] = $roleDetails;
        $pageData['listData']    = $list;
        return view('admin.roles.permission',$pageData);
    }
    
    /*****************************************************/
    # RoleController
    # Function name : submitRolePermission
    # Author        :
    # Created Date  : 27-05-2019
    # Purpose       : to submit role permission
    # Params        : roleType
    /*****************************************************/
    public function submitRolePermission($roleType, Request $request) {
        $roleId = $request->role_id;
        $pagePath = $request->page_path;
        $status = $request->status;

        $submitedPagePath = [];
        //check the route name and replace it by list
        $routeNameFind = substr($pagePath, strrpos($pagePath, '.' )+1);
        
        if($routeNameFind !== 'list') {
            $routeNameReplace = str_replace($routeNameFind,"list",$pagePath);
            //check the list roll id of that particular group
            $checkPageListId = RolePage::select('id')->where('routeName', '=', $routeNameReplace)->first(); 
            //dd($checkPageListId->id); 
        }


        //check actual selected roll                
        $pageDetails = RolePage::where('routeName', '=', $pagePath)->first();
        
        if($pageDetails == null) {
            $pageDetails = new RolePage();
            $pageDetails->routeName = $pagePath;
            $pageDetails->save();

            $submitedPagePath[str_replace('.', '_', $pagePath)] = true; 
        }
        
        // check selected roll is permited or not 
        $permissionDetails = RolePermission::where("role_id", "=" ,$roleId)
        ->where("page_id", "=", $pageDetails->id)->first();
        
        if ($status == 0 && $permissionDetails) {

            if($routeNameFind === 'list'){

                $First = ".";
                $Second = ".";
                $Firstpos=stripos($pagePath, $First);
                $Secondpos=strripos($pagePath, $Second);
                $startIndex = min($Firstpos, $Secondpos);
                $length = abs($Firstpos - $Secondpos);

                $takeGroupName = substr($pagePath,  $startIndex + 1,  $length -1);
                

                $list = [];
                $routeCollection = \Route::getRoutes();

                foreach($routeCollection as $route) {
                    
                    $namespace = $route->action['namespace'];
                    if (!in_array("POST", $route->methods)  && strstr($namespace,'\admin') != ''){
                        $group = str_replace("admin.", "", $route->getName());

                        $group = strstr($group, ".", true);
                        if (!array_key_exists($group, $list)) {
                            $list[$group] = [];
                        }
                        array_push($list[$group], [
                            
                            "path"=> $route->getName(),
                            "group_name" => ($group)?$group:'',
                            
                        ]);
                        
                    }
                }
                //dd($list, $takeGroupName);
                $getGroupAllRouteName = [];
                foreach($list as $selectedGroup => $foundGroup){

                    if($foundGroup[0]['group_name'] == $takeGroupName) {
                        foreach($foundGroup as $groupRow){
                            $getGroupAllRouteName[] = RolePage::where('routeName', '=', $groupRow['path'])->pluck('id');
                            $submitedPagePath[str_replace('.', '_', $groupRow['path'])] = false;
                        }
                    }
                }
                //dd($getGroupAllRouteName);
                $getGroupAllRouteIds = [];
                if(count($getGroupAllRouteName)>0){
                    foreach ($getGroupAllRouteName as $key => $routeNameEach) {
                        RolePermission::where("role_id", "=" ,$roleId)->where("page_id", "=", $routeNameEach[0])->delete();
                    }
                } 

            }

            RolePermission::where("role_id", "=" ,$roleId)->where("page_id", "=", $pageDetails->id)->delete();
            
            $submitedPagePath[str_replace('.', '_', $pagePath)] = false; 
        } 
        else {
            $permissionDetails = new RolePermission();
            $permissionDetails->role_id = $roleId;
            $permissionDetails->page_id = $pageDetails->id;
            $permissionDetails->save();

            $submitedPagePath[str_replace('.', '_', $pagePath)] = true; 
        }

        if($routeNameFind !== 'list') {
            //check the list roll id have permission or not
            $listRollPermissionDetails = RolePermission::where("role_id", "=" ,$roleId)
                                        ->where("page_id", "=", $checkPageListId->id)->first(); 
            //dd($listRollPermissionDetails);                            
                                        
            if($listRollPermissionDetails === null){
                //dd($listRollPermissionDetails, $roleId, $checkPageListId->id);
                
                $listPermissionDetails = new RolePermission();
                $listPermissionDetails->role_id = $roleId;
                $listPermissionDetails->page_id = $checkPageListId->id;
                $listPermissionDetails->save();

                $submitedPagePath[str_replace('.', '_', $routeNameReplace)] = true; 
            }
        }
        
        return json_encode($submitedPagePath);
    }

    /*****************************************************/
    # RoleController
    # Function name : delete
    # Author        :
    # Created Date  : 31-05-2019
    # Purpose       : Delete a role after checking user list
    # Params        : Request $request, $id
    /*****************************************************/
    public function delete(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.role.list');
            }

            $isExistUserWithTheRole = User::where('role_id', $id)->whereNull('deleted_at')->count();
            if ($isExistUserWithTheRole > 0) {
                $request->session()->flash('alert-warning', 'Unable to delete, user with this role exist');
                return redirect()->back();
            } else {
                $deleteRole = Role::find($id)->delete();
                if ($deleteRole) {
                    $request->session()->flash('alert-danger', 'Role has been deleted successfully');
                    return redirect()->back();
                } else {
                    $request->session()->flash('alert-danger', 'An error occurred while deleting the role');
                    return redirect()->back();
                }
            }
        } catch (Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
    }
}