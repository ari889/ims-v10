<?php

namespace App\Models;

use TypiCMS\NestableTrait;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    /**
     * nestable trait from typicms/nestablecollection
     */
    use NestableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['menu_id', 'type', 'module_name', 'divider_title', 'icon_class', 'url', 'order', 'parent_id', 'target'];

    /**
     * module with menu one to one relationship
     */
    public function menu(){
        return $this->belongsTo(Menu::class);
    }


    /**
     * module parent id with module id one to one relationship
     */
    public function parent(){
        return $this->belongsTo(Module::class, 'parent_id', 'id');
    }


    /**
     * one parent has many children with module class one to many relationship
     */
    public function children(){
        $query = $this->hasMany(Module::class, 'parent_id', 'id');
        if(auth()->user()->role_id != 1){
            $role_id = auth()->user()->role_id;
            $query->whereHas('module_role', function($q) use ($role_id){
                $q->where('role_id', $role_id);
            });
        }

        return $query->orderBy('order', 'asc');
    }

    /**
     * module one to many submenu relationship
     */
    public function submenu(){
        return $this->hasMany(Module::class, 'parent_id', 'id')
                    ->orderBy('order', 'asc')
                    ->with('permission:id,module_id,name');
    }

    /**
     * module with permission one to many relationship
     */
    public function permission(){
        return $this->hasMany(Permission::class);
    }

    /**
     * module role one to many relationship
     */
    public function module_role(){
        return $this->hasMany(ModuleRole::class);
    }
}
