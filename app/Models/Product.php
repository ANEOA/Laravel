<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'thumbnail',
        'price',
    ];

    protected static function boot()
    {
        parent::boot();
        /**
         * Перед созданием
         */
        static::creating(function (Model $model) {
            $model->slug = $model->slug ?? str($model->title)->slug();
        });
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
