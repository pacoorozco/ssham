<?php

namespace SSHAM;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'usergroup_hostgroup_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usergroup_id',
        'hostgroup_id',
        'action',
        'name',
        'enabled'
    ];

}
