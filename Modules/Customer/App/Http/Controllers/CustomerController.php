<?php

namespace Modules\Customer\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Base\App\Http\Controllers\BaseController;
use Modules\Customer\App\Http\Requests\CustomerFormRequest;
use Modules\Customer\Entities\Customer;
use Modules\System\Entities\CustomerGroup;

class CustomerController extends BaseController
{
    /**
     * load supplier model
     */

    public function __construct(Customer $supplier)
    {
        $this->model = $supplier;
    }

    /**
     * get index view
     */
    public function index(){
        if(permission('customer-access')){
            $this->setPageData('Customer', 'Customer', 'fas fa-user');
            $customer_groups = CustomerGroup::activeCustomerGroups();
            return view('customer::index', compact('customer_groups'));
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * get datatable data
     */
    public function get_datatable_data(Request $request){
        if($request->ajax()){
            if(permission('customer-access')){
                /**
                 * search warehouse by name
                 */
                if(!empty($request->customer_group_id)){
                    $this->model->setCustomerGroupID($request->customer_group_id);
                }
                /**
                 * search warehouse by name
                 */
                if(!empty($request->name)){
                    $this->model->setName($request->name);
                }
                /**
                 * search warehouse by phone
                 */
                if(!empty($request->phone)){
                    $this->model->setPhone($request->phone);
                }
                /**
                 * search warehouse by email
                 */
                if(!empty($request->email)){
                    $this->model->setEmail($request->email);
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
                    if(permission('customer-edit')){
                        $action .= '<a href="#" class="dropdown-item edit_data" data-id="'.$value->id.'"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }

                    /**
                     * menu edit link
                     */
                    if(permission('customer-view')){
                        $action .= '<a href="#" class="dropdown-item view_data" data-id="'.$value->id.'"><i class="fas fa-eye text-warning"></i> View</a>';
                    }
    
                    /**
                     * menu delete link
                     */
                    if(permission('customer-delete')){
                        $action .= '<a href="#" class="dropdown-item delete_data" data-id="'.$value->id.'" data-name="'.$value->name.'"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
    
    
                    $row = [];
                    if(permission('customer-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;
                    $row[] = $value->customer_group->group_name;
                    $row[] = $value->name;
                    $row[] = $value->company_name;
                    $row[] = $value->tax_number;
                    $row[] = $value->phone;
                    $row[] = $value->email;
                    $row[] = $value->address;
                    $row[] = $value->city;
                    $row[] = $value->state;
                    $row[] = $value->postal_code;
                    $row[] = $value->country;
                    $row[] = permission('customer-edit') ? change_status($value->id, $value->status, $value->name) : STATUS_LABEL[$value->status];
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
     * store or update warehouse
     */
    public function store_or_update(CustomerFormRequest $request){
        if($request->ajax()){
            if(permission('customer-add') || permission('customer-edit')){
                $collection = collect($request->validated());
                $collection = $this->track_data($collection, $request->update_id);
                $result = $this->model->updateOrCreate(['id' => $request->update_id], $collection->all());
                $output = $this->store_message($result, $request->update_id);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    /**
     * edit warehouse
     */
    public function edit(Request $request){
        if($request->ajax()){
            if(permission('customer-edit')){
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
     * supplier view
     */
    public function show(Request $request){
        if($request->ajax()){
            if(permission('customer-view')){
                $supplier = $this->model->findOrFail($request->id);
                return view('supplier::details', compact('supplier'))->render();
            }
        }
    }

    /**
     * delete category
     */
    public function delete(Request $request){
        if($request->ajax()){
            if(permission('customer-delete')){
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
            if(permission('customer-bulk-delete')){
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
            if(permission('customer-edit')){
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
     * get customer group data
     */
    public function groupData(int $id)
    {
        $data = $this->model->with('customer_group')->find($id);
        return $data ? $data->customer_group->percentage : 0;
    }
}
