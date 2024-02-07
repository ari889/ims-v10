<?php 


namespace App\Services;

use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Repositories\MenuRepository as Menu;
use App\Repositories\ModuleRepository as Module;

class ModuleService extends BaseService{
    /**
     * load menu and module repository
     */
    protected $module;
    protected $menu;

    public function __construct(Module $module, Menu $menu)
    {
        $this->module = $module;
        $this->menu = $menu;
    }

    /**
     * load menu items data from menu service
     */
    public function index(int $id){
        $data['menu'] = $this->menu->withMenuItems($id);
        return $data;
    }


    /**
     * store or update module
     */
    public function store_or_update_data(Request $request){
        $collection = collect($request->validated());
        $menu_id = $request->menu_id;
        $created_at = $updated_at = Carbon::now();
        if($request->update_id){
            $collection = $collection->merge('updated_at');
        }else{
            $collection = $collection->merge(compact('menu_id', 'created_at'));
        }

        $result = $this->module->updateOrCreate(['id' => $request->update_id], $collection->all());

        if($result){
            if(auth()->user()->role_id == 1){
                $this->restore_session_module();
            }
        }

        return $result;
    }

    /**
     * get edit module data
     */
    public function edit($menu, $module){
        $data['menu'] = $this->menu->withMenuItems($menu);
        $data['module'] = $this->module->findOrFail($module);
        return $data;
    }

    /**
     * delete module and related permission
     */
    public function delete($module){
        Permission::where('module_id', $module)->delete();
        $this->module->delete_child($module);
        $result = $this->module->delete($module);
        if($result){
            if(auth()->user()->role_id == 1){
                $this->restore_session_module();
            }
        }

        return $result;
    }

    /**
     * restore session module
     */
    public function restore_session_module(){
        $modules = $this->module->session_module_list();

        if(!$modules->isEmpty()){
            Session::forget('menu');
            Session::put('menu', $modules);
            return true;
        }
        return false;
    }
    
}