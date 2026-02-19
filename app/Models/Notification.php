<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'asset_id',
        'title',
        'message',
        'icon',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function getTypeColor()
    {
        return match($this->type) {
            'asset_new' => 'blue',
            'asset_sold' => 'green',
            'system' => 'red',
            'info' => 'amber',
            default => 'gray',
        };
    }

    public function getTypeLabel()
    {
        return match($this->type) {
            'asset_new' => 'Aset Baru',
            'asset_sold' => 'Aset Terjual',
            'system' => 'Sistem',
            'info' => 'Informasi',
            default => 'Umum',
        };
    }

    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}
