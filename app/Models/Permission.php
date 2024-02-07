<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['module_id', 'name', 'slug'];

    /**
     * relation with module with one to one
     */
    public function module(){
        return $this->belongsTo(Module::class);
    }

    /**
     * relation with permission role one to many
     */
    public function permission_role(){
        return $this->hasMany(PermissionRole::class);
    }
}
