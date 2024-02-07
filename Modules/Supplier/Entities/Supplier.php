<?php

namespace Modules\Supplier\Entities;

use Modules\Base\Entities\BaseModel;

class Supplier extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'company_name', 'vat_number', 'email', 'phone', 'address', 'city', 'state', 'postal_code', 'country', 'status', 'created_by', 'updated_by'];

    /**
     * set data table searchable data
     */
    protected $name;
    protected $phone;
    protected $email;

    public function setName($name){
        $this->name = $name;
    }
    public function setPhone($phone){
        $this->phone = $phone;
    }
    public function setEmail($email){
        $this->email = $email;
    }

    public function get_datatable_query(){
        if(permission('brand-bulk-delete')){
            $this->column_order = [null, 'id', 'name', 'company_name', 'vat_number', 'email', 'phone', 'address', 'city', 'state', 'postal_code', 'country', 'status', null];
        }else{
            $this->column_order = ['id', 'name', 'company_name', 'vat_number', 'email', 'phone', 'address', 'city', 'state', 'postal_code', 'country', 'status', null];
        }

        $query = self::toBase();

        /************
         * * Search Data *
         ************/

         if(!empty($this->name)){
             $query->where('name', 'like', '%'.$this->name.'%');
         }
         if(!empty($this->phone)){
             $query->where('phone', 'like', '%'.$this->phone.'%');
         }
         if(!empty($this->email)){
             $query->where('email', 'like', '%'.$this->email.'%');
         }

         if (isset($this->orderValue) && isset($this->dirValue)) {
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);
        } else if (isset($this->order)) {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }
        return $query;
    }

    /**
     * get datatable list data
     */
    public function getDatatableList()
    {
        $query = $this->get_datatable_query();
        if ($this->lengthVlaue != -1) {
            $query->offset($this->startVlaue)->limit($this->lengthValue);
        }
        return $query->get();
    }

    /**
     * count fiktered data
     */
    public function count_filtered()
    {
        $query = $this->get_datatable_query();
        return $query->get()->count();
    }


    /**
     * count all data from datatable
     */
    public function count_all()
    {
        return self::toBase()->get()->count();
    }
}
