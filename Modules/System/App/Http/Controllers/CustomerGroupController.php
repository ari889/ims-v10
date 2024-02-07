<?php

namespace Modules\System\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Base\App\Http\Controllers\BaseController;
use Modules\System\Entities\CustomerGroup;
use Modules\System\App\Http\Requests\CustomerGroupFormRequest;

class CustomerGroupController extends BaseController
{
    /**
     * load category model
     */

    public function __construct(CustomerGroup $customer_group)
    {
        $this->model = $customer_group;
    }

    /**
     * get index view
     */
    public function index(){
        if(permission('customer-group-access')){
            $this->setPageData('Customer Group', 'Customer Group', 'fas fa-user-friends');
            return view('system::customer-group.index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * get datatable data
     */
    public function get_datatable_data(Request $request){
        if($request->ajax()){
            if(permission('customer-group-access')){
                if(!empty($request->group_name)){
                    $this->model->setGroupName($request->group_name);
                }
                if(!empty($request->percentage)){
                    $this->model->setPercentage($request->percentage);
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
                    if(permission('customer-group-edit')){
                        $action .= '<a href="#" class="dropdown-item edit_data" data-id="'.$value->id.'"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
    
                    /**
                     * menu delete link
                     */
                    if(permission('customer-group-delete')){
                        $action .= '<a href="#" class="dropdown-item delete_data" data-id="'.$value->id.'" data-name="'.$value->group_name.'"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
    
    
                    $row = [];
                    if(permission('customer-group-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;
                    $row[] = $value->group_name;
                    $row[] = $value->percentage ? number_format($value->percentage) : number_format(0, 2);
                    $row[] = permission('customer-group-edit') ? change_status($value->id, $value->status, $value->group_name) : STATUS_LABEL[$value->status];
                    $row[] = action_button($action);
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
    public function store_or_update(CustomerGroupFormRequest $request)
    {
        if($request->ajax()){
            if(permission('customer-group-add') || permission('customer-group-edit')){
                $collection = collect($request->validated())->except('percentage');
                $percentage = $request->percentage ? $request->percentage : null;
                $collection = $collection->merge(compact('percentage'));
                $collection = $this->track_data($collection, $request->update_id);
                $result = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
                $output = $this->store_message($result,$request->update_id);
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
            if(permission('customer-group-edit')){
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
            if(permission('customer-group-delete')){
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
            if(permission('customer-group-bulk-delete')){
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
     * change category status
     */
    public function change_status(Request $request){
        if($request->ajax()){
            if(permission('customer-group-delete')){
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
}
