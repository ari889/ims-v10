<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserFormRequest;
use App\Services\RoleService;
use App\Services\UserService;

class UserController extends BaseController
{
    /**
     * load user and module service
     */
    protected $role;
    public function __construct(UserService $user, RoleService $role)
    {
        $this->service = $user;
        $this->role = $role;
    }

    
    /**
     * load user index route
     */
    public function index(){
        if(permission('user-access')){
            $this->setPageData('User', 'User', 'fas fa-th-list');
            $roles = $this->role->index();
            return view('user.index', compact('roles'));
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * get datatable data
     */
    public function get_datatable_data(Request $request){
        if(permission('user-access')){
            if($request->ajax()){
                $output = $this->service->get_datatable_data($request);
            }else{
                $output = ['status' => 'error', 'message' => 'Unauthorized access blocked!'];
            }
    
            return response()->json($output);
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * store or update data
     */
    public function store_or_update(UserFormRequest $request){
        if(permission('user-add') || permission('user-edit')){
            if($request->ajax()){
                $result = $this->service->store_or_update_data($request);
                if($result){
                    return $this->response_json($status="success", $message="Data has been saved successfully!", $data=null, $response_code=200);
                }else{
                    return $this->response_json($status="error", $message="Unauthorized access blocked!", $data=null, $response_code=204);
                }
            }else{
                return $this->response_json($status='error',$message=null,$data=null,$response_code=401);
            }
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * edit user data
     */
    public function edit(Request $request){
        if(permission('user-edit')){
            if($request->ajax()){
                $data = $this->service->edit($request);
                if($data->count() > 0){
                    return $this->response_json($status="success", $message=null, $data=$data, $response_code=201);
                }else{
                    return $this->response_json($status="error", $message="Data Not Found", $data=null, $response_code=204);
                }
            }else{
                return $this->response_json($status='error',$message=null,$data=null,$response_code=401);
            }
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * show single user
     */
    public function show(Request $request){
        if($request->ajax()){
            if(permission('user-view')){
                $user = $this->service->edit($request);
                return view('user.details', compact('user'))->render();
            }
        }
    }

    /**
     * delete user data
     */
    public function delete(Request $request){
        if(permission('user-delete')){
            if($request->ajax()){
                $result = $this->service->delete($request);
                if($result){
                    return $this->response_json($status="success", $message="Data deleted successfully!", $data=null, $response_code=200);
                }else{
                    return $this->response_json($status="error", $message="Failed to delete data!", $data=null, $response_code=204);
                }
            }else{
                return $this->response_json($status='error',$message=null,$data=null,$response_code=401);
            }
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * bulk delete data from database
     */
    public function bulk_delete(Request $request){
        if(permission('user-bulk-delete')){
            if($request->ajax()){
                $result = $this->service->bulk_delete($request);
                if($result){
                    return $this->response_json($status="success", $message="Data deleted successfully!", $data=null, $response_code=200);
                }else{
                    return $this->response_json($status="error", $message="Failed to delete data!", $data=null, $response_code=204);
                }
            }else{
                return $this->response_json($status='error',$message=null,$data=null,$response_code=401);
            }
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * change user status
     */
    public function change_status(Request $request){
        if($request->ajax()){
            if(permission('user-edit')){
                $result = $this->service->change_status($request);

                if($result){
                    return $this->response_json($status="success", $message="Status changed successfully!", $data=null, $response_code=200);
                }else{
                    return $this->response_json($status="error", $message="Failed to change status!", $data=null, $response_code=204);
                }
            }else{
                return $this->unauthorized_access_blocked();
            }
        }else{
            return $this->response_json($status='error',$message=null,$data=null,$response_code=401);
        }
    }
}
