<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'status',
        'location',
        'photos',
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    public function getStatusBadgeColor()
    {
        return $this->status === 'Available' ? 'green' : 'red';
    }

    public function getCategoryColor()
    {
        return match($this->category) {
            'Bank Cessie' => 'blue',
            'AYDA' => 'purple',
            'Lelang' => 'orange',
            default => 'gray',
        };
    }
}
