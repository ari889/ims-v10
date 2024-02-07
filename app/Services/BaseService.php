<?php

namespace App\Services;

class BaseService{
    /**
     * datatable draw info
     */
    protected function datatable_draw($draw, $recordsTotal, $recordsFiltered, $data){
        return array(
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        );
    }
}