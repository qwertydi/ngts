<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'devices';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'owner_id',
        'type',
        'name',
        'ip_address',
        'active',
        'mac_address',
    ];
    
    /**
     * A device belongs to a user.
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Build Devices Relationships.
     *
     * @var array
     */
    public function motion()
    {
        return $this->hasMany('App\Models\Motion');
    }
}
