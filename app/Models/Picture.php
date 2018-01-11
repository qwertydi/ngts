<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'picture';

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
        'device_id',
        'url'
    ];
    
    /**
     * A motion belongs to a device.
     *
     * @return mixed
     */
    public function device()
    {
        return $this->belongsTo('App\Models\Device');
    }
}
