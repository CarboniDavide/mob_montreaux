<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TrainRoute extends Model
{
    protected $table = 'train_routes';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'from_station_short',
        'to_station_short',
        'analytic_code',
        'distance_km',
        'path',
    ];

    protected $casts = [
        'path' => 'array',
        'distance_km' => 'float',
    ];

    // ensure uuid is generated
    public static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
}
