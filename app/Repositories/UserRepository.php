<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository{

    /**
     * set column order
     */
    protected $order = array('id' => 'desc');

    protected $role_id;
    protected $name;
    protected $email;
    protected $mobile_no;
    protected $status;

    /**
     * fil model property from BaseRepository class
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * search datatable based on menu name
     */
    public function setRoleID($role_id){
        $this->role_id = $role_id;
    }
    public function setName($name){
        $this->name = $name;
    }
    public function setEmail($email){
        $this->email = $email;
    }
    public function setMobileNo($mobile_no){
        $this->mobile_no = $mobile_no;
    }
    public function setStatus($status){
        $this->status = $status;
    }

    /**
     * set datatable query
     */
    public function get_datatable_query(){
        /**
         * set menu column order asc or desc
         */
        if(permission('user-bulk-delete')){
            $this->column_order = [null, 'id', 'role_id', 'email', 'mobile_no', 'gender', 'status', null];
        }else{
            $this->column_order = ['id', 'role_id', 'email', 'mobile_no', 'gender', 'status', null];
        }

        $query = $this->model->with('role:id,role_name');

        /*******************
         * * Search Data **
         *******************/

         if(!empty($this->name)){
             $query->where('name', 'like', '%'.$this->name.'%');
         }
         if(!empty($this->role_id)){
             $query->where('role_id', $this->role_id);
         }
         if(!empty($this->email)){
             $query->where('email', 'like', '%'.$this->email.'%');
         }
         if(!empty($this->mobile_no)){
             $query->where('mobile_no', 'like', '%'.$this->mobile_no.'%');
         }
         if(!empty($this->status)){
             $query->where('status', $this->status);
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

    

}