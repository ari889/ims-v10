<?php

namespace Modules\Customer\Entities;

use Modules\Base\Entities\BaseModel;
use Modules\System\Entities\CustomerGroup;

class Customer extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['customer_group_id', 'name', 'company_name', 'tax_number', 'email', 'phone', 'address', 'city', 'state', 'postal_code', 'country', 'status', 'created_by', 'updated_by'];

    /**
     * one to one relationship with customer group
     */
    public function customer_group(){
        return $this->belongsTo(CustomerGroup::class);
    }

    /**
     * set data table searchable data
     */
    protected $customer_group_id;
    protected $name;
    protected $phone;
    protected $email;

    public function setCustomerGroupID($customer_group_id){
        $this->customer_group_id = $customer_group_id;
    }
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
            $this->column_order = [null, 'id', 'customer_group_id', 'name', 'company_name', 'tax_number', 'email', 'phone', 'address', 'city', 'state', 'postal_code', 'country', 'status', null];
        }else{
            $this->column_order = ['id', 'customer_group_id', 'name', 'company_name', 'tax_number', 'email', 'phone', 'address', 'city', 'state', 'postal_code', 'country', 'status', null];
        }

        $query = self::with('customer_group');

        /************
         * * Search Data *
         ************/

         if(!empty($this->customer_group_id)){
             $query->where('customer_group_id', $this->customer_group_id);
         }
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
