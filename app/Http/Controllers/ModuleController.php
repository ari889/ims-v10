<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModuleFormRequest;
use App\Services\ModuleService;
use Illuminate\Http\Request;

class ModuleController extends BaseController
{
    /**
     * load module service
     */
    public function __construct(ModuleService $Module)
    {
        $this->service = $Module;
    }


    
    /**
     * load module view with menuitems
     * @param menu_id
     */
    public function index(int $id){
        $this->setPageData('Menu Builder', 'Menu Builder', 'fas fa-th-list');
        $data = $this->service->index($id);
        return view('module.index', compact('data'));
    }

    /**
     * add new module
     */
    public function create($menu){
        $this->setPageData('Create Menu Module', 'Create Menu Module', 'fas fa-th-list');
        $data = $this->service->index($menu);
        return view('module.form', compact('data'));
    }

    /**
     * module create or update
     */
    public function store_or_update(ModuleFormRequest $request){
        $result = $this->service->store_or_update_data($request);
        if($result){
            if($request->update_id){
                session()->flash('success', 'Module updated successfully!');
            }else{
                session()->flash('success', 'Module created successfully!');
            }
            return redirect()->route('menu.builder', ['id' => $request->menu_id]);
        }else{
            if($request->update_id){
                session()->flash('success', 'Module updated failed!');
            }else{
                session()->flash('success', 'Module created failed!');
            }
            return redirect()->back();  
        }
    }

    /**
     * edit module
     */
    public function edit($menu, $module){
        $this->setPageData('Update Menu Module', 'Update Menu Module', 'fas fa-th-list');
        $data = $this->service->edit($menu, $module);
        return view('module.form', compact('data'));
    }

    /**
     * delete module
     */
    public function destroy($module){
        $result = $this->service->delete($module);
        if($result){
            session()->flash('success', 'Module deleted successfully!');
        }else{
            session()->flash('success', 'Module failed to delete!');
        }

        return redirect()->back();
    }

    
}
