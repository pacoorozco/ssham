<?php namespace SSHAM;

use Illuminate\Database\Eloquent\Model;

class Usergroup extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'usergroups';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
            'name',
            'description'
        ];

    /**
     * An Usergroup is composed by many User (many-to-many)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() {
        return $this->belongsToMany('SSHAM\User');
    }

    public function hostgroups()
    {
        return $this->belongsToMany('SSHAM\Hostgroup', 'usergroup_hostgroup_permissions');
    }
}
