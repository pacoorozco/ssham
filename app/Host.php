<?php namespace SSHAM;

use Illuminate\Database\Eloquent\Model;

class Host extends Model
{

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
        'type'
    ];

    /**
     * A Host belongs to many Hostgroups (many-to-many)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany('SSHAM\Hostgroup');
    }

    /**
     * This method return full hostname string, composed by username@hostname
     *
     * @return string
     */
    public function getFullHostname()
    {
        return $this->username . '@' . $this->hostname;
    }

    /**
     * Set Host sync status
     *    0 = Host is not sync, it needs to transfer SSH Key file
     *    1 = Host is sync
     *
     * @param int $synced
     */
    public function setSynced($synced = 0)
    {
        $this->synced = $synced;
    }

    /**
     * Set Host Keys Files Hash. It keeps a hash of last transfered SSH Key File
     *
     * @param $keyHash
     */
    public function setKeyHash($keyHash)
    {
        $this->keyhash = $keyHash;
    }

    /**
     * Set Host enabled status
     *     1, enabled, true = Host is enabled
     *     0, disabled, false = Host is disabled
     *
     * @param $status
     */
    public function setStatus($status)
    {
        $status = ($status === 1 || $status == 'enabled' || $status === true) ? 1 : 0;
        $this->enabled = $status;
    }
}
