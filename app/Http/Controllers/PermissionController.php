<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PermissionService;
use App\Http\Requests\PermissionFormRequest;
use App\Http\Requests\PermissionUpdateRequest;

class PermissionController extends BaseController
{
    /**
     * load menu and module service
     */
    public function __construct(PermissionService $permission)
    {
        $this->service = $permission;
    }

    
    /**
     * load menu index route
     */
    public function index(){
        if(permission('permission-access')){
            $this->setPageData('Permission', 'Permission', 'fas fa-th-list');
            $data = $this->service->index();
            return view('permission.index', compact('data'));
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * get datatable data
     */
    public function get_datatable_data(Request $request){
        if(permission('permission-access')){
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
    public function store(PermissionFormRequest $request){
        if(permission('permission-add')){
            if($request->ajax()){
                $result = $this->service->store($request);
                if($result){
                    return $this->response_json($status="success", $message="Data has been saved successfully!", $data=null, $response_code=200);
                }else{
                    return $this->response_json($status="error", $message="Data can't Save!", $data=null, $response_code=204);
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
    public function edit(Request $request){
        if(permission('permission-edit')){
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
     * update permission
     */
    public function update(PermissionUpdateRequest $request){
        if(permission('permission-edit')){
            if($request->ajax()){
                $result = $this->service->update($request);
                if($result){
                    return $this->response_json($status="success", $message="Data has been updated successfully!", $data=null, $response_code=200);
                }else{
                    return $this->response_json($status="error", $message="Data can't Update!", $data=null, $response_code=204);
                }
            }else{
                return $this->response_json($status='error',$message=null,$data=null,$response_code=401);
            }
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * delete menu data
     */
    public function delete(Request $request){
        if(permission('permission-delete')){
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
        if(permission('permission-bulk-delete')){
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
