<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'scientific_name',
        'nickname',
        'family',
        'type',
        'image_path',
        'confidence',
        'description',
        'captured_date',
        'location_lat',
        'location_lng',
    ];

    protected $casts = [
        'captured_date' => 'datetime',
        'confidence' => 'integer',
        'location_lat' => 'decimal:8',
        'location_lng' => 'decimal:8',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return null;
    }
}