<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RoleService;
use App\Http\Requests\RoleFormRequest;

class RoleController extends BaseController
{
    /**
     * load menu and module service
     */
    public function __construct(RoleService $role)
    {
        $this->service = $role;
    }

    
    /**
     * load menu index route
     */
    public function index(){
        if(permission('role-access')){
            $this->setPageData('Role', 'Role', 'fas fa-th-list');
            return view('role.index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * get datatable data
     */
    public function get_datatable_data(Request $request){
        if(permission('role-access')){
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
     * create new role
     */
    public function create(){
        if(permission('role-add')){
            $this->setPageData('Create Role', 'Create Role', 'fas fa-th-list');
            $data = $this->service->permission_module_list();
            return view('role.create', compact('data'));
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * store or update data
     */
    public function store_or_update(RoleFormRequest $request){
        if(permission('role-add') || permission('role-edit')){
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
     * edit menu data
     */
    public function edit(int $id){
        if(permission('role-edit')){
            $this->setPageData('Edit Role', 'Edit Role', 'fas fa-edit');
            $data = $this->service->permission_module_list();
            $permission_data = $this->service->edit($id);
            return view('role.edit', compact('data', 'permission_data'));
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * show role details
     */
    public function show(int $id){
        if(permission('role-view')){
            $this->setPageData('Role Details', 'Role Details', 'fas fa-eye');
            $data = $this->service->permission_module_list();
            $permission_data = $this->service->edit($id);
            return view('role.details', compact('data', 'permission_data'));
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * delete menu data
     */
    public function delete(Request $request){
        if(permission('role-delete')){
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
        if(permission('role-bulk-delete')){
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

    
}
