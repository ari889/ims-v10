<?php

namespace Modules\System\App\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Base\App\Http\Controllers\BaseController;
use Modules\System\Entities\Unit;
use Modules\System\App\Http\Requests\UnitFormRequest;

class UnitController extends BaseController
{
    /**
     * load brand model
     */

    public function __construct(Unit $unit)
    {
        $this->model = $unit;
    }

    /**
     * get index view
     */
    public function index(){
        if(permission('unit-access')){
            $this->setPageData('Unit', 'Unit', 'fas fa-weight-hanging');
            return view('system::unit.index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * get datatable data
     */
    public function get_datatable_data(Request $request){
        if($request->ajax()){
            if(permission('unit-access')){
                /**
                 * search unit by name
                 */
                if(!empty($request->unit_name)){
                    $this->model->setUnitName($request->unit_name);
                }
    
                $this->set_datatable_default_property($request);
    
                $list = $this->model->getDatatableList();
    
                $data = [];
                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;
                    $action = '';
    
                    /**
                     * menu edit link
                     */
                    if(permission('unit-edit')){
                        $action .= '<a href="#" class="dropdown-item edit_data" data-id="'.$value->id.'"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
    
                    /**
                     * menu delete link
                     */
                    if(permission('unit-delete')){
                        $action .= '<a href="#" class="dropdown-item delete_data" data-id="'.$value->id.'" data-name="'.$value->unit_name.'"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
    
    
                    $row = [];
                    if(permission('unit-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row [] = $no;
                    $row [] = $value->unit_name;
                    $row [] = $value->unit_code;
                    $row [] = $value->baseUnit->unit_name;
                    $row [] = $value->operator;
                    $row [] = $value->operation_value;
                    $row [] = permission('unit-edit') ? change_status($value->id, $value->status, $value->unit_name) : STATUS_LABEL[$value->status];
                    $row [] = action_button($action);
                    $data[] = $row;
                }
    
                return $this->datatable_draw($request->input('draw'), $this->model->count_all(), $this->model->count_filtered(), $data);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    /**
     * store or update category
     */
    public function store_or_update(UnitFormRequest $request){
        if($request->ajax()){
            if(permission('unit-add') || permission('unit-edit')){
                $collection      = collect($request->validated())->except(['operator','operation_value']);
                $base_unit       = $request->base_unit ? $request->base_unit : null;
                $operator        = $request->operator ? $request->operator : '*';
                $operation_value = $request->operation_value ? $request->operation_value : 1;
                $collection      = $collection->merge(compact('base_unit','operator','operation_value'));
                $collection      = $this->track_data($collection, $request->update_id);
                $result          = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
                $output          = $this->store_message($result,$request->update_id);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    /**
     * edit category
     */
    public function edit(Request $request){
        if($request->ajax()){
            if(permission('unit-edit')){
                $data = $this->model->findOrFail($request->id);
                $output = $this->data_message($data);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    /**
     * delete category
     */
    public function delete(Request $request){
        if($request->ajax()){
            if(permission('unit-delete')){
                $result = $this->model->find($request->id)->delete();
                $output = $this->delete_message($result);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    /**
     * bulk delete
     */
    public function bulk_delete(Request $request){
        if($request->ajax()){
            if(permission('unit-bulk-delete')){
                $result = $this->model->destroy($request->ids);
                $output = $this->delete_message($result);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    /**
     * change brand status
     */
    public function change_status(Request $request){
        if($request->ajax()){
            if(permission('unit-edit')){
                $result = $this->model->find($request->id)->update(['status' => $request->status]);
                $output = $result ? ['status'=>'success','message'=>'Status has been changed successfully']
                : ['status'=>'error','message'=>'Failed to change status'];
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    /**
     * get all base unit
     */
    public function base_unit(Request $request){
        if($request->ajax()){
            $units = $this->model->where(['base_unit'=>null, 'status'=>1])->get();
            $output = '<option value="">Select Please</option>';
            if(!$units->isEmpty()){
                foreach ($units as $unit) {
                    $output .= '<option value="'.$unit->id.'">'.$unit->unit_name.'('.$unit->unit_code.')</option>';
                }
            }
            return $output;
        }
    }
}
