<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
      'slug',
      'title',
      'thumbnail'
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
        return $this->hasMany(Product::class);
    }
}
