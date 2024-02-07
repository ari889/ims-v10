<?php

namespace Modules\System\App\Http\Controllers;

use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Modules\Base\App\Http\Controllers\BaseController;
use Modules\System\Entities\Brand;
use Modules\System\App\Http\Requests\BrandFormRequest;

class BrandController extends BaseController
{
    use UploadAble;
    /**
     * load brand model
     */

    public function __construct(Brand $brand)
    {
        $this->model = $brand;
    }

    /**
     * get index view
     */
    public function index(){
        if(permission('brand-access')){
            $this->setPageData('Brand', 'Brand', 'fas fa-th-list');
            return view('system::brand.index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * get datatable data
     */
    public function get_datatable_data(Request $request){
        if($request->ajax()){
            if(permission('brand-access')){
                if(!empty($request->title)){
                    $this->model->setTitle($request->title);
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
                    if(permission('brand-edit')){
                        $action .= '<a href="#" class="dropdown-item edit_data" data-id="'.$value->id.'"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
    
                    /**
                     * menu delete link
                     */
                    if(permission('brand-delete')){
                        $action .= '<a href="#" class="dropdown-item delete_data" data-id="'.$value->id.'" data-name="'.$value->title.'"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
    
    
                    $row = [];
                    if(permission('brand-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;
                    $row[] = table_image(BRAND_IMAGE_PATH, $value->image, $value->title);
                    $row[] = $value->title;
                    $row[] = permission('brand-edit') ? change_status($value->id, $value->status, $value->title) : STATUS_LABEL[$value->status];
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
    public function store_or_update(BrandFormRequest $request){
        if($request->ajax()){
            if(permission('brand-add') || permission('brand-edit')){
                $collection = collect($request->validated())->only('title');
                $collection = $this->track_data($collection, $request->update_id);
                $image = $request->old_image;
                if($request->hasFile('image')){
                    $image = $this->upload_file($request->file('image'), BRAND_IMAGE_PATH);

                    if(!empty($request->old_image)){
                        $this->delete_file($request->old_image, BRAND_IMAGE_PATH);
                    }
                }
                $collection = $collection->merge(compact('image'));
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
     * edit category
     */
    public function edit(Request $request){
        if($request->ajax()){
            if(permission('brand-edit')){
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
            if(permission('brand-delete')){
                $brand = $this->model->find($request->id);
                $image = $brand->image;
                $result = $brand->delete();
                if($result){
                    if(!empty($image)){
                        $this->delete_file($image, BRAND_IMAGE_PATH);
                    }
                }
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
            if(permission('brand-bulk-delete')){
                $brands = $this->model->toBase()->select('image')->whereIn('id', $request->ids)->get();
                $result = $this->model->destroy($request->ids);
                if($result){
                    if(!empty($brands)){
                        foreach ($brands as $brand) {
                            if($brand->image){
                                $this->delete_file($brand->image, BRAND_IMAGE_PATH);
                            }
                        }
                    }
                }
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
            if(permission('brand-edit')){
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
