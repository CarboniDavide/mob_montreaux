<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distance extends Model
{
    protected $table = 'distances';

    protected $fillable = [
        'network',
        'parent_short_name',
        'child_short_name',
        'parent_station_id',
        'child_station_id',
        'distance',
    ];

    public function parentStation()
    {
        return $this->belongsTo(Station::class, 'parent_station_id');
    }

    public function childStation()
    {
        return $this->belongsTo(Station::class, 'child_station_id');
    }
}
