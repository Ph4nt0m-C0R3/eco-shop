<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_mm',
        'description',
        'description_mm',
        'status',
        'sort_order',
    ];

    /**
     * Get name based on current locale
     */
    public function getDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'mm' && $this->name_mm
            ? $this->name_mm
            : $this->name;
    }

    /**
     * Get description based on current locale
     */
    public function getDisplayDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'mm' && $this->description_mm
            ? $this->description_mm
            : $this->description;
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
