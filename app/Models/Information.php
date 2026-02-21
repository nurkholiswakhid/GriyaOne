<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    protected $table = 'information';

    protected $fillable = [
        'title',
        'content',
        'category_id',
        'status',
        'published_date',
        'photo',
    ];

    protected $casts = [
        'published_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(InformationCategory::class, 'category_id');
    }

    public function getStatusBadgeColor()
    {
        return $this->status === 'active' ? 'green' : 'gray';
    }
}
