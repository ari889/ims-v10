<?php

namespace Modules\Expense\Entities;

use Modules\Base\Entities\BaseModel;

class ExpenseCategory extends BaseModel
{
    /**
     * model mass assignment
     */
    protected $fillable = ['name','status','created_by','updated_by'];
    
    /**
     * search by category name
     */
    protected $cat_name;

    public function setName($cat_name)
    {
        $this->cat_name = $cat_name;
    }

    /**
     * prepare datatable query
     */
    private function get_datatable_query()
    {
        if(permission('category-bulk-delete')){
            $this->column_order = [null,'id','name','status',null];
        }else{
            $this->column_order = ['id','name','status',null];
        }

        $query = self::toBase();

        /*****************
         * *Search Data **
         ******************/
        if (!empty($this->cat_name)) {
            $query->where('name', 'like', '%' . $this->cat_name . '%');
        }

        if (isset($this->orderValue) && isset($this->dirValue)) {
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);
        } else if (isset($this->order)) {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }
        return $query;
    }

    /**
     * get datatable list
     */
    public function getDatatableList()
    {
        $query = $this->get_datatable_query();
        if ($this->lengthVlaue != -1) {
            $query->offset($this->startValue)->limit($this->lengthValue);
        }
        return $query->get();
    }

    /**
     * count filtered data for datatable
     */
    public function count_filtered()
    {
        $query = $this->get_datatable_query();
        return $query->get()->count();
    }

    /**
     * count all data from datatable list
     */
    public function count_all()
    {
        return self::toBase()->get()->count();
    }
}
