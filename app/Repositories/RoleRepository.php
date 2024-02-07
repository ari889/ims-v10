<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository extends BaseRepository{
    /**
     * set column order
     */
    protected $order = array('id' => 'desc');
    protected $name;

    /**
     * fil model property from BaseRepository class
     */
    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    /**
     * search datatable based on menu name
     */
    public function setRoleName($role_name){
        $this->role_name = $role_name;
    }

    /**
     * set datatable query
     */
    public function get_datatable_query(){
        /**
         * set menu column order asc or desc
         */
        if(permission('role-bulk-delete')){
            $this->column_order = [null, 'id', 'name', 'deletable', null];
        }else{
            $this->column_order = ['id', 'menu_name', 'deletable', null];
        }

        $query = $this->model->toBase();

        /*******************
         * * Search Data **
         *******************/

         if(!empty($this->role_name)){
             $query->where('role_name', 'like', '%'.$this->role_name.'%');
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
     * find with module and permission
     */
    public function find_with_module_permission(int $id){
        return $this->model->with('module_role', 'permission_role')->find($id);
    }
}