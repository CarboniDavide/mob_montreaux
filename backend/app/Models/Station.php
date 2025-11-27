<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $table = 'stations';

    // The JSON data used numeric ids; treat id as non-incrementing
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'short_name',
        'long_name',
    ];

    public function parentDistances()
    {
        return $this->hasMany(Distance::class, 'parent_station_id');
    }

    public function childDistances()
    {
        return $this->hasMany(Distance::class, 'child_station_id');
    }
}
