<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motion extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'motion';

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
        'stream',
        'picture'
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
