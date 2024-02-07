<?php

namespace Modules\Product\Entities;

use Modules\Base\Entities\BaseModel;
use Modules\Category\Entities\Category;
use Modules\System\Entities\Brand;
use Modules\System\Entities\Tax;
use Modules\System\Entities\Unit;

class Product extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'code', 'barcode_symbology', 'image', 'brand_id', 'category_id', 'unit_id', 'purchase_unit_id', 'sale_unit_id', 'cost', 'price', 'qty', 'alert_qty', 'tax_id', 'tax_method', 'description', 'status', 'created_by', 'updated_by'];

    /**
     * one to one relationship with brand
     */
    public function brand(){
        return $this->belongsTo(Brand::class)->withDefault(['title' => 'No Brand']);
    }
    /**
     * one to one relationship with category
     */
    public function category(){
        return $this->belongsTo(Category::class);
    }
    /**
     * one to one relationship with unit
     */
    public function unit(){
        return $this->belongsTo(Unit::class);
    }
    /**
     * one to one relationship with unit
     */
    public function purchase_unit(){
        return $this->belongsTo(Unit::class, 'purchase_unit_id', 'id');
    }
    /**
     * one to one relationship with unit
     */
    public function sale_unit(){
        return $this->belongsTo(Unit::class, 'sale_unit_id', 'id');
    }
    /**
     * one to one relationship with sale unit
     */
    public function tax(){
        return $this->belongsTo(Tax::class)->withDefault(['name' => 'No Tax', 'rate' => 0]);
    }

    /**
     * set data table searchable data
     */
    protected $name;
    protected $code;
    protected $brand_id;
    protected $category_id;

    public function setName($name){
        $this->name = $name;
    }
    public function setCode($code){
        $this->code = $code;
    }
    public function setBrandID($brand_id){
        $this->brand_id = $brand_id;
    }
    public function setCategoryID($category_id){
        $this->category_id = $category_id;
    }
    public function get_datatable_query(){
        if(permission('product-bulk-delete')){
            $this->column_order = [null, 'id', 'image', 'name', 'code', 'brand_id', 'category_id', 'unit_id', 'cost', 'price', 'qty', 'alert_qty', 'tax_id', 'tax_method', 'status', null];
        }else{
            $this->column_order = ['id', 'image', 'name', 'code', 'brand_id', 'category_id', 'unit_id', 'cost', 'price', 'qty', 'alert_qty', 'tax_id', 'tax_method', 'status', null];
        }

        $query = self::with('brand', 'category', 'unit', 'tax');

        /************
         * * Search Data *
         ************/

         if(!empty($this->name)){
             $query->where('name', 'like', '%'.$this->name.'%');
         }
         if(!empty($this->code)){
             $query->where('code', 'like', '%'.$this->code.'%');
         }
         if(!empty($this->brand_id)){
             $query->where('brand_id', $this->brand_id);
         }
         if(!empty($this->category_id)){
             $query->where('category_id', $this->category_id);
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
