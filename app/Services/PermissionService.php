<?php


namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\BaseService;
use Illuminate\Support\Facades\Session;
use App\Repositories\ModuleRepository as Module;
use App\Repositories\PermissionRepository as Permission;

class PermissionService extends BaseService{

    /**
     * load menu and module model
     */
    protected $permission;
    protected $module;

    public function __construct(Permission $permission, Module $module)
    {
        $this->permission = $permission;
        $this->module = $module;
    }

    /**
     * get module data
     */
    public function index(){
        $data['modules'] = $this->module->module_list(1);
        return $data;
    }

    /**
     * get datatable data from menu repository
     */
    public function get_datatable_data(Request $request){
        if($request->ajax()){
            if(!empty($request->name)){
                $this->permission->setName($request->name);
            }
            if(!empty($request->module_id)){
                $this->permission->setModuleID($request->module_id);
            }

            $this->permission->setOrderValue($request->input('order.0.column'));
            $this->permission->setDirValue($request->input('order.0.dir'));
            $this->permission->setLengthValue($request->input('length'));
            $this->permission->setStartValue($request->input('start'));

            $list = $this->permission->getDatatableList();

            $data = [];
            $no = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';

                /**
                 * menu edit link
                 */
                if(permission('permission-edit')){
                    $action .= '<a href="#" class="dropdown-item edit_data" data-id="'.$value->id.'"><i class="fas fa-edit text-primary"></i> Edit</a>';
                }
                

                /**
                 * menu delete link
                 */
                if(permission('permission-delete')){
                    $action .= '<a href="#" class="dropdown-item delete_data" data-id="'.$value->id.'" data-name="'.$value->name.'"><i class="fas fa-trash text-danger"></i> Delete</a>';
                }


                $row = [];
                if(permission('permission-bulk-delete')){
                    $row[] = table_checkbox($value->id);
                }
                $row[] = $no;
                $row[] = $value->module->module_name;
                $row[] = $value->name;
                $row[] = $value->slug;
                $row[] = action_button($action);
                $data[] = $row;
            }

            return $this->datatable_draw($request->input('draw'), $this->permission->count_all(), $this->permission->count_filtered(), $data);
        }
    }

    /**
     * store or update data in database
     */
    public function store(Request $request){
        $permission_data = [];
        foreach($request->permission as $value){
            $permission_data[] = [
                'module_id'  => $request->module_id,
                'name'       => $value['name'],
                'slug'       => $value['slug'],
                'created_at' => Carbon::now()
            ];
        }

        $result = $this->permission->insert($permission_data);
        if($result){
            if(auth()->user()->role_id){
                $this->restore_session_permission_list();
            }
        }
        return $result;
    }

    /**
     * permission update
     */
    public function update(Request $request){
        $collection = collect($request->validated());
        $updated_at = Carbon::now();
        $collection = $collection->merge('updated_at');
        $result = $this->permission->update($collection->all(), $request->update_id);
        if($result){
            if(auth()->user()->role_id){
                $this->restore_session_permission_list();
            }
        }
        return $result;
    }

    /**
     * get menu edit info
     */
    public function edit(Request $request){
        return $this->permission->find($request->id);
    }

    /**
     * delete menu single data
     */
    public function delete(Request $request){
        $result = $this->permission->delete($request->id);
        if($result){
            if(auth()->user()->id == 1){
                $this->restore_session_permission_list();
            }
        }
        return $result;
    }

    /**
     * bulk delete data from database
     */
    public function bulk_delete(Request $request){
        $result = $this->permission->destroy($request->ids);
        if($result){
            if(auth()->user()->id == 1){
                $this->restore_session_permission_list();
            }
        }
        return $result;
    }

    /**
     * restore session permission list
     */
    public function restore_session_permission_list(){
        $permissions = $this->permission->session_permission_list();

        $permission = [];
        if(!$permissions->isEmpty()){
            foreach ($permissions as $value) {
                array_push($permission, $value->slug);
            }

            Session::forget('permission');
            Session::put('permission', $permission);
            return true;
        }
        return false;
    }


}