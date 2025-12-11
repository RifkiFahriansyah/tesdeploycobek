<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Menu extends Model
{
    use HasFactory;

    protected $appends = ['photo_full_url'];

    protected $fillable = [
        'category_id','name','description','price','photo_path','is_active'
    ];

    protected $casts = [
        'price' => 'decimal:0',
    ];

    public function category() { return $this->belongsTo(Category::class); }

    public function scopeActive($q) { return $q->where('is_active', true); }

    public function toSnapshot(): array {
        return ['menu_name'=>$this->name, 'unit_price'=>(int)$this->price];
    }

    public function getPhotoFullUrlAttribute(): ?string
    {
        if (!$this->photo_path) {
            return null;
        }
        
        // Force HTTPS for production (Vercel)
        $url = asset($this->photo_path);
        
        // If in production and URL is http, convert to https
        if (app()->environment('production') && str_starts_with($url, 'http://')) {
            $url = 'https://' . substr($url, 7);
        }
        
        return $url;
    }
}
