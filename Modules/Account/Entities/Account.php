<?php

namespace Modules\Account\Entities;

use Modules\Base\Entities\BaseModel;

class Account extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['account_no', 'name', 'initial_balance', 'note', 'status', 'created_by', 'updated_by'];

    /**
     * set data table searchable data
     */
    protected $account_no;
    protected $account_name;

    public function setAccountNo($account_no){
        $this->account_no = $account_no;
    }
    public function setAccountName($account_name){
        $this->account_name = $account_name;
    }

    public function get_datatable_query(){
        if(permission('account-bulk-delete')){
            $this->column_order = [null, 'id', 'account_no', 'name', 'initial_balance', 'note', 'status', null];
        }else{
            $this->column_order = ['id', 'account_no', 'name', 'initial_balance', 'note', 'status', null];
        }

        $query = self::toBase();

        /************
         * * Search Data *
         ************/

         if(!empty($this->account_no)){
             $query->where('account_no', 'like', '%'.$this->account_no.'%');
         }
         if(!empty($this->account_name)){
             $query->where('name', 'like', '%'.$this->account_name.'%');
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
