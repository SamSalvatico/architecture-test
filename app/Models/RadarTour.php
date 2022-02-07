<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $operator_id
 */
class RadarTour extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'operator_id',
        'operator_tour_id',
        'description',
        'datetime_start',
        'datetime_end'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'datetime_start' => 'datetime',
        'datetime_end' => 'datetime',
    ];

    public function radarTourAttachments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RadarTourAttachment::class);
    }
}
