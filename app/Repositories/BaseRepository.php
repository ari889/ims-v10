<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository{

    /**
     * define model property to load model
     */
    protected $model;

    /**
     * datatable column order
     */
    protected $column_order;

    /**
     * datatable order  value;
     */
    protected $orderValue;
    /**
     * data table direction value
     */
    protected $dirValue;
    /**
     * data tale start value
     */
    protected $startValue;
    /**
     * datatable length value
     */
    protected $lengthValue;

    /**
     * load model on construct
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    
    /**
     * create row in database
     */
    public function create(array $attributes){
        return $this->model->create($attributes);
    }

    /**
     * insert multiple data in database
     */
    public function insert(array $attributes){
        return $this->model->insert($attributes);
    }

    /**
     * update data in database
     */
    public function update(array $attributes, int $id) : bool
    {
        return $this->model->find($id)->update($attributes);
    }

    /**
     * update or insert data in database
     */
    public function updateOrCreate(array $search_data, array $attributes){
        return $this->model->updateOrCreate($search_data, $attributes);
    }

    /**
     * get all data from database
     */
    public function all($columns=array('*'), string $orderBy='id', string $sortBy='desc'){
        return $this->model->orderBy($orderBy,$sortBy)->get($columns);
    }

    /**
     * find data from database
     */
    public function find(int $id){
        return $this->model->find($id);
    }

    /**
     * find or fail data
     */
    public function findOrFail(int $id){
        return $this->model->findOrFail($id);
    }

    /**
     * find  with condition
     */
    public function findBy(array $data){
        return $this->model->where($data)->get();
    }

    /**
     * find only one data with condition
     */
    public function findOneBy(array $data){
        return $this->model->where($data)->first();
    }

    /**
     * find one or fail with condition
     */
    public function findOneByFail(array $data){
        return $this->model->where($data)->firstOrFail();
    }

    /**
     * delete one data
     */
    public function delete(int $id) : bool {
        return $this->model->find($id)->delete();
    }

    /**
     * multi delete
     */
    public function destroy(array $data) : bool
    {
        return $this->model->destroy($data);
    }

    /**
     * set datatable column order
     */
    public function setOrderValue($orderValue){
        $this->orderValue = $orderValue;
    }
    /**
     * set datatable column order
     */
    public function setDirValue($dirValue){
        $this->dirValue = $dirValue;
    }
    /**
     * set datatable column order
     */
    public function setStartValue($startValue){
        $this->startValue = $startValue;
    }
    /**
     * set datatable column order
     */
    public function setLengthValue($lengthValue){
        $this->lengthValue = $lengthValue;
    }

}