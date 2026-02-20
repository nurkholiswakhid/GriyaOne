<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MaterialCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'order',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->slug) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    /**
     * Relationship: MaterialCategory has many Materials
     */
    public function materials()
    {
        return $this->hasMany(Material::class, 'category_id');
    }
}
