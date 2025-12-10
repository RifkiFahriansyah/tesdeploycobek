<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name','is_active','sort'];

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
