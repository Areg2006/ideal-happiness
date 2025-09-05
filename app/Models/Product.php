<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'user_id', 'description', 'category_id', 'is_archived'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id',);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id',);
    }
}

