<?php

namespace Modules\Product\App\Http\Controllers;

use Modules\Base\App\Http\Controllers\BaseController;
use Modules\Product\App\Http\Requests\BarcodeFormRequest;
use Modules\Product\Entities\Product;

class BarcodeController extends BaseController
{
    /**
     * load product model
     */
    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    /**
     * load print barcode view
     */
    public function index(){
        if(permission('print-barcode-access')){
            $this->setPageData('Print Barcode','Print Barcode','fas fa-barcode');
            $products = $this->model->all();
            return view('product::barcode.index', compact('products'));
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * generate barcode
     */
    public function generate_barcode(BarcodeFormRequest $request){
        if($request->ajax()){
            $data  = [
                'name'              => $request->name,
                'code'              => $request->code,
                'barcode_symbology' => $request->barcode_symbology,
                'price'             => $request->price ? number_format($request->price, 2) : '0.00',
                'barcode_qty'       => $request->barcode_qty,
                'row_qty'           => $request->row_qty,
                'width'             => $request->width,
                'height'            => $request->height,
                'unit'              => $request->unit,
            ];
            return view('product::barcode.barcode', $data)->render();
        }
    }
}
