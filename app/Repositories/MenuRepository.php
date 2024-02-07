<?php

namespace App\Repositories;

use App\Models\Menu;


class MenuRepository extends BaseRepository{

    /**
     * set column order
     */
    protected $order = array('id' => 'desc');
    protected $menu_name;

    /**
     * fil model property from BaseRepository class
     */
    public function __construct(Menu $model)
    {
        $this->model = $model;
    }

    /**
     * search datatable based on menu name
     */
    public function setMenuName($menu_name){
        $this->menu_name = $menu_name;
    }

    /**
     * set datatable query
     */
    public function get_datatable_query(){
        /**
         * set menu column order asc or desc
         */
        if(permission('menu-bulk-delete')){
            $this->column_order = [null, 'id', 'menu_name', 'deletable', null];
        }else{
            $this->column_order = ['id', 'menu_name', 'deletable', null];
        }

        $query = $this->model->toBase();

        /*******************
         * * Search Data **
         *******************/

         if(!empty($this->menu_name)){
             $query->where('menu_name', 'like', '%'.$this->menu_name.'%');
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
     * get data with menuitems
     */
    public function withMenuItems($id){
        return $this->model->with('menuItems')->findOrFail($id);
    }

    

}