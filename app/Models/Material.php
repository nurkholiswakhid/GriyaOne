<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category_id',
        'file_path',
        'thumbnail',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * Relationship: Material belongs to MaterialCategory
     */
    public function category()
    {
        return $this->belongsTo(MaterialCategory::class);
    }
}
