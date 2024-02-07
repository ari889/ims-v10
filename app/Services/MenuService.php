<?php


namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\MenuRepository as Menu;
use App\Repositories\ModuleRepository as Module;
use Carbon\Carbon;

class MenuService extends BaseService{

    /**
     * load menu and module model
     */
    protected $menu;
    protected $module;

    public function __construct(Menu $menu, Module $module)
    {
        $this->menu = $menu;
        $this->module = $module;
    }

    /**
     * get datatable data from menu repository
     */
    public function get_datatable_data(Request $request){
        if($request->ajax()){
            if(!empty($request->menu_name)){
                $this->menu->setMenuName($request->menu_name);
            }

            $this->menu->setOrderValue($request->input('order.0.column'));
            $this->menu->setDirValue($request->input('order.0.dir'));
            $this->menu->setLengthValue($request->input('length'));
            $this->menu->setStartValue($request->input('start'));

            $list = $this->menu->getDatatableList();

            $data = [];
            $no = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';

                /**
                 * menu builder link
                 */
                if(permission('menu-builder')){
                    $action .= '<a href="'.route('menu.builder', ["id" => $value->id]).'" class="dropdown-item"><i class="fas fa-th-list text-success"></i> Builder</a>';
                }

                /**
                 * menu edit link
                 */
                if(permission('menu-edit')){
                    $action .= '<a href="#" class="dropdown-item edit_data" data-id="'.$value->id.'"><i class="fas fa-edit text-primary"></i> Edit</a>';
                }

                /**
                 * menu delete link
                 */
                if(permission('menu-delete')){
                    if($value->deletable == 1){
                        $action .= '<a href="#" class="dropdown-item delete_data" data-id="'.$value->id.'" data-name="'.$value->menu_name.'"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                }


                $row = [];
                if(permission('menu-bulk-delete')){
                    $row[] = ($value->deletable == 1) ? table_checkbox($value->id) : '';
                }
                $row[] = $no;
                $row[] = $value->menu_name;
                $row[] = DELETABLE[$value->deletable];
                $row[] = action_button($action);
                $data[] = $row;
            }

            return $this->datatable_draw($request->input('draw'), $this->menu->count_all(), $this->menu->count_filtered(), $data);
        }
    }

    /**
     * store or update data in database
     */
    public function store_or_update_data(Request $request){
        $collection = collect($request->validated());
        $created_at = $updated_at = Carbon::now();
        if($request->update_id){
            $collection = $collection->merge(compact('updated_at'));
        }else{
            $collection = $collection->merge(compact('created_at'));
        }

        return $this->menu->updateOrCreate(['id' => $request->update_id], $collection->all());
    }

    /**
     * get menu edit info
     */
    public function edit(Request $request){
        return $this->menu->find($request->id);
    }

    /**
     * delete menu single data
     */
    public function delete(Request $request){
        return $this->menu->delete($request->id);
    }

    /**
     * bulk delete data from database
     */
    public function bulk_delete(Request $request){
        return $this->menu->destroy($request->ids);
    }

    /**
     * set new order module in database
     */
    public function orderMenu(array $menuItems, $parent_id){
        foreach($menuItems as $index => $menuItem){
            $item = $this->module->findOrFail($menuItem->id);
            $item->order = $index + 1;
            $item->parent_id = $parent_id;
            $item->save();
            if(isset($menuItem->children)){
                $this->orderMenu($menuItem->children, $item->id);
            }
        }
    }


}