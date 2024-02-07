<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuFormRequest;
use App\Services\MenuService;
use App\Services\ModuleService;
use Illuminate\Http\Request;

class MenuController extends BaseController
{
    /**
     * load menu and module service
     */
    protected $module;
    public function __construct(MenuService $menu, ModuleService $module)
    {
        $this->service = $menu;
        $this->module = $module;
    }

    
    /**
     * load menu index route
     */
    public function index(){
        if(permission('menu-access')){
            $this->setPageData('Menu', 'Menu', 'fas fa-th-list');
            return view('menu.index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * get datatable data
     */
    public function get_datatable_data(Request $request){
        if(permission('menu-access')){
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
    public function store_or_update(MenuFormRequest $request){
        if(permission('menu-add') || permission('menu-edit')){
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
    public function edit(Request $request){
        if(permission('menu-edit')){
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
     * delete menu data
     */
    public function delete(Request $request){
        if(permission('menu-delete')){
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
        if(permission('menu-bulk-delete')){
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
     * re arrange menu
     */
    public function orderItem(Request $request){
        $menuItemOrder = json_decode($request->input('order'));
        $this->service->orderMenu($menuItemOrder, null);
        $this->module->restore_session_module();
    }

}
