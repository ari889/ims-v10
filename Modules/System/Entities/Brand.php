<?php

namespace Modules\System\Entities;

use Modules\Base\Entities\BaseModel;

class Brand extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'image', 'status', 'created_by', 'updated_by'];

    /**
     * set data table searchable data
     */
    protected $title;

    public function setTitle($title){
        $this->title = $title;
    }

    public function get_datatable_query(){
        if(permission('brand-bulk-delete')){
            $this->column_order = [null, 'id', 'image', 'title', 'status', null];
        }else{
            $this->column_order = ['id', 'image', 'title', 'status', null];
        }

        $query = self::toBase();

        /************
         * * Search Data *
         ************/

         if(!empty($this->title)){
             $query->where('title', 'like', '%'.$this->title.'%');
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
