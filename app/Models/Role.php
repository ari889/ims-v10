<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['role_name', 'deletable'];

    /**
     * relationship with module one to one
     */
    public function module_role(){
        return $this->belongsToMany(Module::class)->withTimestamps();
    }

    /**
     * relationship with permission one to many
     */
    public function permission_role(){
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    /**
     * relationship with user one to many
     */
    public function users(){
        return $this->hasMany(User::class);
    }
}
