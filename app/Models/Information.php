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

    public function getStatusBadgeClass()
    {
        if ($this->status === 'active') {
            return 'bg-green-100 text-green-800';
        } else {
            return 'bg-gray-100 text-gray-800';
        }
    }

    public function getStatusLabel()
    {
        return $this->status === 'active' ? 'Aktif' : 'Arsip';
    }
}
