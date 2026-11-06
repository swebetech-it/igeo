<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'category',
        'featured'
    ];

    protected $casts = [
        'featured' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships (simplified for existing fields)

    // Accessors & Mutators
    public function getImageUrlAttribute()
    {
        if (!$this->file_path) {
            return $this->getPlaceholderUrl();
        }

        if (Storage::disk('public')->exists($this->file_path)) {
            return asset('storage/' . $this->file_path);
        }

        return $this->getPlaceholderUrl();
    }

    public function getFileExtensionAttribute()
    {
        if (!$this->file_path) {
            return null;
        }

        return strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION));
    }

    public function getIsImageAttribute()
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'tiff'];
        return in_array($this->file_extension, $imageExtensions);
    }

    public function getIsVideoAttribute()
    {
        $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mkv'];
        return in_array($this->file_extension, $videoExtensions);
    }

    public function getIsDocumentAttribute()
    {
        $documentExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'];
        return in_array($this->file_extension, $documentExtensions);
    }

    public function getFileTypeNameAttribute()
    {
        return match(true) {
            $this->is_image => 'Image',
            $this->is_video => 'Video',
            $this->is_document => 'Document',
            default => 'File'
        };
    }

    public function getFileSizeFormattedAttribute()
    {
        if (!$this->file_size) return 'Unknown';

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getDimensionsAttribute()
    {
        if ($this->width && $this->height) {
            return $this->width . ' × ' . $this->height;
        }
        return null;
    }

    public function getPlaceholderUrl()
    {
        return "https://via.placeholder.com/400x300/e2e8f0/64748b?text=" . urlencode($this->title ?: 'No Image');
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->is_image && $this->file_path) {
            // For now, return the same image URL
            // In the future, you could implement thumbnail generation
            return $this->image_url;
        }

        return $this->getFileTypeIcon();
    }

    public function getFileTypeIcon()
    {
        return match(true) {
            $this->is_image => 'fas fa-image',
            $this->is_video => 'fas fa-video',
            $this->is_document => 'fas fa-file-alt',
            default => 'fas fa-file'
        };
    }

    public function getDownloadUrlAttribute()
    {
        if (!$this->file_path) return null;

        return route('gallery.download', $this->id);
    }

    // Business Logic Methods
    public function markAsFeatured(): bool
    {
        // Unmark other featured items in the same category
        static::where('category', $this->category)
              ->where('id', '!=', $this->id)
              ->update(['featured' => false]);

        return $this->update(['featured' => true]);
    }

    public function unmarkAsFeatured(): bool
    {
        return $this->update(['featured' => false]);
    }

    public function updateSortOrder(int $order): bool
    {
        return $this->update(['sort_order' => $order]);
    }

    public function addTag(string $tag): bool
    {
        $tags = $this->tags ?: [];
        if (!in_array($tag, $tags)) {
            $tags[] = $tag;
            $this->update(['tags' => $tags]);
        }
        return true;
    }

    public function removeTag(string $tag): bool
    {
        $tags = $this->tags ?: [];
        $tags = array_filter($tags, fn($t) => $t !== $tag);
        return $this->update(['tags' => array_values($tags)]);
    }

    // Static Methods
    public static function getCategories(): array
    {
        return [
            'before_after' => 'Before & After',
            'construction' => 'Construction Progress',
            'completed' => 'Completed Projects',
            'equipment' => 'Equipment & Tools',
            'team' => 'Team Photos',
            'certificates' => 'Certificates & Awards',
            'materials' => 'Materials & Samples',
            'blueprints' => 'Blueprints & Plans',
            'inspections' => 'Inspections',
            'other' => 'Other'
        ];
    }

    // Scopes
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeImages($query)
    {
        return $query->whereIn('file_type', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    public function scopeVideos($query)
    {
        return $query->whereIn('file_type', ['video/mp4', 'video/avi', 'video/mov']);
    }

    public function scopeDocuments($query)
    {
        return $query->whereIn('file_type', ['application/pdf', 'application/msword', 'text/plain']);
    }

    public function scopeByTags($query, array $tags)
    {
        foreach ($tags as $tag) {
            $query->whereJsonContains('tags', $tag);
        }
        return $query;
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }
}
