<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'max_capacity',
        'min_capacity',
        'week_type',
        'price',
        'price_type',
        'benefits',
        'total_stays',
        'is_published',
        'photo',
    ];

    protected $casts = [
        'benefits' => 'array',
        'is_published' => 'boolean',
        'price' => 'integer',
    ];

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeWeekdays($query)
    {
        return $query->where('week_type', 'weekdays');
    }

    public function scopeWeekends($query)
    {
        return $query->where('week_type', 'weekends');
    }

    // Accessors
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getPriceTypeTextAttribute(): string
    {
        return $this->price_type === 'pack' ? 'per paket' : 'per malam';
    }
}
