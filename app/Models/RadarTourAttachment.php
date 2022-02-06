<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadarTourAttachment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'radar_tour_id',
        'filename',
        'url',
        'media_type'
    ];

    public function radarTour(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RadarTour::class);
    }
}
