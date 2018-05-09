<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as LModel;

class RoleUser extends LModel
{
    protected $primaryKey = 'user_id';

    protected $table = 'role_user';

    public $timestamps = false;

    protected $fillable = ['user_id', 'role_id'];
}
