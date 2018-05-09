<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as LModel;

class PermissionRole extends LModel
{
    protected $table = 'permission_role';

    public $timestamps = false;

    protected $fillable = ['role_id', 'permission_id'];
}
