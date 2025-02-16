<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $guarded = [];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function source()
    {
        return $this->belongsTo(Source::class);
    }
    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
