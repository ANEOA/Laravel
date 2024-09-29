<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function (Model $model) {
            $model->slug = $model->slug ?? str($model->{self::fromSlug()})->slug();
        });
    }

    public static function fromSlug(): String
    {
        return 'title';
    }
}
