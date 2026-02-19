<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'category',
        'thumbnail',
        'file_path',
        'duration',
        'is_published',
        'views',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'duration' => 'integer',
        'views' => 'integer',
    ];

    public function getTypeColor()
    {
        return match($this->type) {
            'Video' => 'blue',
            'Materi' => 'purple',
            'Info' => 'amber',
            default => 'gray',
        };
    }

    public function getCategoryColor()
    {
        return match($this->category) {
            'Training' => 'green',
            'Challenge' => 'red',
            'Bonus' => 'yellow',
            default => 'gray',
        };
    }

    public function getTypeIcon()
    {
        return match($this->type) {
            'Video' => '🎬',
            'Materi' => '📄',
            'Info' => '📢',
            default => '📦',
        };
    }

    public function formatDuration()
    {
        if (!$this->duration) return '-';
        $hours = intdiv($this->duration, 3600);
        $minutes = intdiv($this->duration % 3600, 60);
        $seconds = $this->duration % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }
        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Extract YouTube video ID from file_path URL.
     *
     * Supported formats:
     *   https://www.youtube.com/watch?v=VIDEO_ID
     *   https://youtube.com/watch?v=VIDEO_ID&list=...
     *   https://youtu.be/VIDEO_ID
     *   https://youtu.be/VIDEO_ID?t=120
     *   https://www.youtube.com/embed/VIDEO_ID
     *   https://www.youtube.com/v/VIDEO_ID
     *   https://www.youtube.com/shorts/VIDEO_ID
     *
     * Returns null if file_path is empty or no valid ID found.
     */
    public function getYoutubeId()
    {
        $url = $this->file_path;
        if (empty($url)) return null;

        // Single unified regex covering all YouTube URL formats
        $pattern = '/(?:youtube\.com\/(?:watch\?.*v=|embed\/|v\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Get the full YouTube embed URL for iframe usage.
     */
    public function getYoutubeEmbedUrl()
    {
        $id = $this->getYoutubeId();
        if (!$id) return null;

        return "https://www.youtube.com/embed/{$id}?origin=" . urlencode(config('app.url'));
    }

    /**
     * Get the YouTube watch URL.
     */
    public function getYoutubeWatchUrl()
    {
        $id = $this->getYoutubeId();
        if (!$id) return $this->file_path;

        return "https://www.youtube.com/watch?v={$id}";
    }
}
