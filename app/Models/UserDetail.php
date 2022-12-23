<?php

namespace App\Models;

use App\Traits\UserSignatureTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use UserSignatureTrait;

    protected $table = 'user_detail';

    protected $fillable = [
        'hoTen',
        'sdt',
        'gioiTinh',
        'ngaySinh',
        'diaChi',
        'CDDD',
        'ngayCapCCCD',
        'noiCapCCCD',
        'anhDaiDien',
        'gCoin',
        'idUser',
        'created_by',
        'updated_by',
        'vip',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
