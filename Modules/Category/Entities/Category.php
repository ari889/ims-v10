<?php

namespace Modules\Category\Entities;

use Modules\Base\Entities\BaseModel;

class Category extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'status', 'created_by', 'updated_by'];

    /**
     * set data table searchable data
     */
    protected $name;

    public function setName($name){
        $this->name = $name;
    }

    public function get_datatable_query(){
        if(permission('category-bulk-delete')){
            $this->column_order = [null, 'id', 'name', 'status', null];
        }else{
            $this->column_order = ['id', 'name', 'status', null];
        }

        $query = self::toBase();

        /************
         * * Search Data *
         ************/

         if(!empty($this->name)){
             $query->where('name', 'like', '%'.$this->name.'%');
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
