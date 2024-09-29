<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
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

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
