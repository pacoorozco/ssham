<?php namespace SSHAM;

use Illuminate\Database\Eloquent\Model;

class Hostgroup extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hostgroups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description'
    ];

    public function hosts()
    {
        return $this->belongsToMany('SSHAM\Host');
    }

    public function permissions()
    {
        return $this->belongsToMany('SSHAM\Usergroup', 'usergroup_hostgroup_permissions');
    }

}
