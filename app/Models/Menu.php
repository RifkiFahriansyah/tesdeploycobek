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
        
        // In production (Vercel), convert image to base64 since static files aren't served
        if (app()->environment('production')) {
            $imagePath = public_path($this->photo_path);
            
            if (file_exists($imagePath)) {
                $imageData = base64_encode(file_get_contents($imagePath));
                $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
                
                return "data:image/{$imageType};base64,{$imageData}";
            }
        }
        
        // For local development, use normal asset URL
        return asset($this->photo_path);
    }
}
