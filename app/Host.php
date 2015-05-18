<?php namespace SSHAM;

use Illuminate\Database\Eloquent\Model;

class Host extends Model {

/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'hosts';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
            'hostname',
            'username',
            'type',
            'keyHash',
            'synced',
            'enabled'
        ];
        
        public function groups() {
            return $this->belongsToMany('SSHAM\Hostgroup');
        }

}
