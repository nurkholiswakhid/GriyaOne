<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InformationCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'order',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->slug) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function informations()
    {
        return $this->hasMany(Information::class, 'category_id');
    }
}
