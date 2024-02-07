<?php
/**
 * check user permission has or not
 */
if(!function_exists('permission')){
    function permission(string $value){
        if(collect(\Illuminate\Support\Facades\Session::get('permission'))->contains($value)){
            return true;
        }
        return false;
    }
}

/**
 * datatable bulk delete checkbox
 */
if(!function_exists('table_checkbox')){
    function table_checkbox($id){
        return '
            <div class="custom-control custom-checkbox">
                <input type="checkbox" value="'.$id.'" class="custom-control-input select_data" onchange="select_single_item('.$id.')" id="checkbox'.$id.'" />
                <label class="custom-control-label" for-"checkbox'.$id.'"></label>
            </div>
        ';
    }
}

/**
 * data deletable status
 */
define('DELETABLE', ['1' => 'Yes', '2' => 'No']);

/**
 * action button dropdown menu
 */
if(!function_exists('action_button')){
    function action_button($action){
        return '
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-th-list text-white"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        '.$action.'
                    </ul>
                </div>
        ';
    }
}

/**
 * user gender
 */
define('GENDER', ['1' => 'Male', '2' => 'Female']);

/**
 * set user status label
 */
define('STATUS_LABEL', [
    '1' => '<span class="badge bg-success">Active</span>',
    '2' => '<span class="badge bg-danger">Inactive</span>'
]);
define('STATUS', [
    '1' => 'Active',
    '2' => 'Inactive'
]);

/**
 * tax method
 */
define('TAX_METHOD',['1'=>'Exclusive','2'=>'Inclusive']);

/**
 * user avatar path
 */
define('USER_AVATAR_PATH', 'user/');
/**
 * logo path
 */
define('LOGO_PATH', 'logo/');
/**
 * logo path
 */
define('FAVICON_PATH', 'favicon/');
/**
 * logo path
 */
define('BRAND_IMAGE_PATH', 'brand/');
/**
 * product path
 */
define('PRODUCT_IMAGE_PATH', 'product/');
/**
 * purchase document path
 */
define('PURCHASE_DOCUMENT_PATH', 'purchase-document/');

/**
 * change status label
 */
if(!function_exists('change_status')){
    function change_status(int $id,int $status,string $name = null){
        return $status == 1 ? '<span class="badge bg-success change_status" data-id="' . $id . '" data-name="' . $name . '" data-status="2" style="cursor:pointer;">Active</span>' : 
        '<span class="badge bg-danger change_status" data-id="' . $id . '" data-name="' . $name . '" data-status="1" style="cursor:pointer;">Inactive</span>';
    }
}

/**
 * mailer
 */
define('MAIL_MAILER', ['smtp', 'sendmail', 'mail']);

/**
 * mail encryption
 */
define('MAIL_ENCRYPTION', ['NONE' => 'null', 'TLS' => 'tls', 'SSL' => 'ssl']);

/**
 * get datatable image path
 */
if(!function_exists('table_image')){
    function table_image($path, string $name, $image=null){
        return $image ? '<img src="storage/'.$path.$image.'" alt="'.$name.'" style="width: 50px;" />' : '<img src="images/default.svg" alt="Default Image" style="width: 50px;" />';
    }
}

/**
 * define barcode symbology
 */
define('BARCODE_SYMBOLOGY', [
    'c128' => 'Code 128',
    'c39' => 'Code  39',
    'UPCA' => 'UPC-A',
    'UPCE' => 'UPC-E',
    'EAN8' => 'EAN-8',
    'EAN13' => 'EAN-13',
]);

/**
 * purchase status 
 */
define('PURCHASE_STATUS', ['1' => 'Received', '2' => 'Partial', '3' => 'Pending', '4' => 'Ordered']);
define('PURCHASE_STATUS_LABEL', [
    '1' => '<span class="badge bg-success">Received</span>',
    '2' => '<span class="badge bg-warning">Partial</span>',
    '3' => '<span class="badge bg-danger">Pending</span>',
    '4' => '<span class="badge bg-info">Ordered</span>',
]);

/**
 * payment status
 */
define('PAYMENT_STATUS', ['1' => 'Paid', '2' => 'Due']);
define('PAYMENT_STATUS_LABEL', [
    '1' => '<span class="badge bg-success">Paid</span>',
    '2' => '<span class="badge bg-danger">Due</span>',
]);

/**
 * payment method
 */
define('PAYMENT_METHOD',['1'=>'Cash','2'=>'Cheque','3'=>'Mobile']);

/**
 * sale status and label
 */
define('SALE_STATUS',['1'=>'Completed','2'=>'Pending']);
define('SALE_STATUS_LABEL',
['1'=>'<span class="badge bg-success">Completed</span>',
'2'=>'<span class="badge bg-danger">Pending</span>',
]);

/**
 * sale payment status label
 */
define('SALE_PAYMENT_STATUS',['1'=>'Paid','2'=>'Pertially Paid', '3' => 'Due']);
define('SALE_PAYMENT_STATUS_LABEL',
['1'=>'<span class="badge bg-success">Paid</span>',
'2'=>'<span class="badge bg-info">Partial</span>',
'3'=>'<span class="badge bg-danger">Due</span>',
]);

/**
 * sale document path
 */
define('SALE_DOCUMENT_PATH','sale-document/');

/**
 * employee avatar image path
 */
define('EMPLOYEE_IMAGE_PATH','employee/');

/**
 * attendance label
 */
define('ATTENDANCE_STATUS_LABEL',
['1'=>'<span class="badge bg-success">Present</span>',
'2'=>'<span class="badge bg-danger">Late</span>',
]);

/**
 * payroll payment method
 */
define('PAYROLL_PAYMENT_METHOD',['1'=>'Cash','2'=>'Bank','3'=>'Mobile']);