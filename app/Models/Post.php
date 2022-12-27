<?php

namespace App\Models;

use App\Traits\UserSignatureTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PostCategoty;

class Post extends Model
{
    use UserSignatureTrait;

    protected $table = 'posts';

    protected $fillable = [
        "title",
        "body",
        "category_id"
    ];

    public function category()
    {
        return $this->belongsTo(PostCategoty::class, 'category_id', 'id');
    }
}
