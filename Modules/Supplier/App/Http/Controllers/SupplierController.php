<?php

namespace Modules\Supplier\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Base\App\Http\Controllers\BaseController;
use Modules\Supplier\App\Http\Requests\SupplierFormRequest;
use Modules\Supplier\Entities\Supplier;

class SupplierController extends BaseController
{
    /**
     * load supplier model
     */

    public function __construct(Supplier $supplier)
    {
        $this->model = $supplier;
    }

    /**
     * get index view
     */
    public function index(){
        if(permission('supplier-access')){
            $this->setPageData('Supplier', 'Supplier', 'fas fa-user-tie');
            return view('supplier::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * get datatable data
     */
    public function get_datatable_data(Request $request){
        if($request->ajax()){
            if(permission('supplier-access')){
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
                    if(permission('supplier-edit')){
                        $action .= '<a href="#" class="dropdown-item edit_data" data-id="'.$value->id.'"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }

                    /**
                     * menu edit link
                     */
                    if(permission('supplier-view')){
                        $action .= '<a href="#" class="dropdown-item view_data" data-id="'.$value->id.'"><i class="fas fa-eye text-warning"></i> View</a>';
                    }
    
                    /**
                     * menu delete link
                     */
                    if(permission('supplier-delete')){
                        $action .= '<a href="#" class="dropdown-item delete_data" data-id="'.$value->id.'" data-name="'.$value->name.'"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
    
    
                    $row = [];
                    if(permission('supplier-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;
                    $row[] = $value->name;
                    $row[] = $value->company_name;
                    $row[] = $value->vat_number;
                    $row[] = $value->phone;
                    $row[] = $value->email;
                    $row[] = $value->address;
                    $row[] = $value->city;
                    $row[] = $value->state;
                    $row[] = $value->postal_code;
                    $row[] = $value->country;
                    $row[] = permission('supplier-edit') ? change_status($value->id, $value->status, $value->name) : STATUS_LABEL[$value->status];
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
    public function store_or_update(SupplierFormRequest $request){
        if($request->ajax()){
            if(permission('supplier-add') || permission('supplier-edit')){
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
            if(permission('supplier-edit')){
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
            if(permission('supplier-view')){
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
            if(permission('supplier-delete')){
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
            if(permission('supplier-bulk-delete')){
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
            if(permission('supplier-edit')){
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
