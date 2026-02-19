<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    protected $fillable = [
        'title',
        'content',
        'category',
        'status',
        'published_date',
    ];

    protected $casts = [
        'published_date' => 'date',
    ];

    public function getStatusBadgeColor()
    {
        return $this->status === 'active' ? 'green' : 'gray';
    }
}
