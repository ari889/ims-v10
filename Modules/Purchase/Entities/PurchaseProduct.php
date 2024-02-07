<?php

namespace Modules\Purchase\Entities;
use Modules\Base\Entities\BaseModel;

class PurchaseProduct extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'purchase_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['purchase_id', 'product_id', 'qty', 'received', 'unit_id', 'net_unit_cost', 'discount', 'tax_rate', 'tax', 'total'];
}
