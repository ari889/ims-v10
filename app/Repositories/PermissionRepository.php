<?php

namespace App\Repositories;

use App\Models\Permission;


class PermissionRepository extends BaseRepository{

    /**
     * set column order
     */
    protected $order = array('id' => 'desc');
    protected $menu_name;

    /**
     * fil model property from BaseRepository class
     */
    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    /**
     * search datatable based on menu name
     */
    public function setModuleID($module_id){
        $this->module_id = $module_id;
    }

    /**
     * search permission by name
     */
    public function setName($name){
        $this->name = $name;
    }

    /**
     * set datatable query
     */
    public function get_datatable_query(){
        /**
         * set menu column order asc or desc
         */
        if(permission('permission-bulk-delete')){
            $this->column_order = [null, 'id', 'menu_name', 'deletable', null];
        }else{
            $this->column_order = ['id', 'menu_name', 'deletable', null];
        }

        $query = $this->model->with('module:id,module_name');

        /*******************
         * * Search Data **
         *******************/

         if(!empty($this->module_id)){
             $query->where('module_id', $this->module_id);
         }
         if(!empty($this->name)){
             $query->where('name', 'like', '%'.$this->module_id.'%');
         }

         /**
          * set column order value
          */
          if(isset($this->column_order) && isset($this->dirValue)){
              $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);
          }else if(isset($this->order)){
              $query->orderBy(key($this->order), $this->order[key($this->order)]);
          }

          return $query;
    }

    /**
     * get datatable data using datatable query
     */
    public function getDatatableList(){
        $query = $this->get_datatable_query();
        if($this->lengthValue != 1){
            $query->offset($this->startValue)->limit($this->lengthValue);
        }
        return $query->get();
    }

    /**
     * count datatable filtered data
     */
    public function count_filtered(){
        $query = $this->get_datatable_query();
        return $query->get()->count();
    }

    /**
     * count all data from database
     */
    public function count_all(){
        return $this->model->toBase()->get()->count();
    }

    /**
     * get session permission list
     */
    public function session_permission_list(){
        return $this->model->select('slug')->get();
    }

    

}