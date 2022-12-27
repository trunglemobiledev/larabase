<?php

namespace App\Models;

use App\Traits\UserSignatureTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use App\Models\User;

class PostCategoty extends Model
{
    use UserSignatureTrait;

    protected $table = 'post_categoties';

    protected $fillable = [
        "name",
        "code"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id', 'id');
    }

    public function userCreate()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function userUpdate()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

}
