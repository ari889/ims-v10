<?php

namespace Modules\System\Entities;

use Modules\Base\Entities\BaseModel;
use Illuminate\Support\Facades\Cache;

class CustomerGroup extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['group_name', 'percentage', 'status', 'created_by', 'updated_by'];

    /**
     * set data table searchable data
     */
    protected $group_name;
    protected $percentage;

    public function setGroupName($group_name){
        $this->group_name = $group_name;
    }
    public function setPercentage($percentage){
        $this->percentage = $percentage;
    }

    public function get_datatable_query(){
        if(permission('category-bulk-delete')){
            $this->column_order = [null, 'id', 'name', 'percentage', 'status', null];
        }else{
            $this->column_order = ['id', 'name', 'percentage', 'status', null];
        }

        $query = self::toBase();

        /************
         * * Search Data *
         ************/

         if(!empty($this->group_name)){
             $query->where('group_name', 'like', '%'.$this->group_name.'%');
         }
         if(!empty($this->percentage)){
             $query->where('percentage', 'like', '%'.$this->percentage.'%');
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

    /**********************
     * * cache data
     **********************/
    private const ALL_CUSTOMER_GROUPS = '_all_customer_groups';
    private const ACTIVE_CUSTOMER_GROUPS = '_active_customer_groups';

    /**
     * get all customer gorups
     */
    public static function allCustomerGroups(){
        return Cache::rememberForever(self::ALL_CUSTOMER_GROUPS, function () {
            self::toBase()->get();
        });
    }

    /**
     * get active customer gorups
     */
    public static function activeCustomerGroups(){
        return Cache::rememberForever(self::ACTIVE_CUSTOMER_GROUPS, function(){
            return self::toBase()->where('status', 1)->get();
        });
    }

    /**
     * flash cache
     */
    public function flushCache(){
        Cache::forget(self::ALL_CUSTOMER_GROUPS);
        Cache::forget(self::ACTIVE_CUSTOMER_GROUPS);
    }

    /**
     * run flashCache function where model boot
     */
    public static function boot(){
        parent::boot();

        static::created(function(){
            self::flushCache();
        });
        static::updated(function(){
            self::flushCache();
        });
        static::deleted(function(){
            self::flushCache();
        });
    }
}
