<?php

namespace Modules\Base\Entities;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * set data table initial data
     */
    protected $order = ['id' => 'desc'];
    protected $column_order;
    protected $orderValue;
    protected $dirValue;
    protected $startValue;
    protected $lengthValue;

    /**
     * set order value
     */
    public function setOrderValue($orderValue){
        $this->orderValue = $orderValue;
    }

    /**
     * set direction value
     */
    public function setDirValue($dirValue){
        $this->dirValue = $dirValue;
    }

    /**
     * set start value
     */
    public function setStartValue($startValue){
        $this->startValue = $startValue;
    }

    /**
     * set length value
     */
    public function setLengthValue($lengthValue){
        $this->lengthValue = $lengthValue;
    }
}
