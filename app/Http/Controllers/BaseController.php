<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * define output to show output response
     */
    protected $output;

    /**
     * define service variable to load service
     */
    protected $service;

    /**
     * view share page data like title subtitle and page icon
     */
    protected function setPageData($page_title, $sub_title, $page_icon){
        view()->share(['page_title' => $page_title, 'sub_title' => $sub_title, 'page_icon' => $page_icon]);
    }

    /**
     * show output error message
     */
    protected function showErrorMessage($error_code=404, $message=null){
        $data['message'] = $message;
        return response()->view('errors.'.$error_code, $data, $error_code);
    }

    /**
     * json response with status
     */
    protected function response_json($status='success', $message=null, $data=null, $response_code=200){
        return response()->json([
            'status'        => $status,
            'message'       => $message,
            'data'          => $data,
            'response_code' => $response_code
        ]);
    }

    /**
     * no access without permission / unauthorized access blocked
     */
    protected function unauthorized_access_blocked(){
        return redirect()->route('unauthorized');
    }
}
