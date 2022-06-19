<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Profile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickname', 'birthday', 'height', 'style', 'comment', 'phone',
    ];
    
    // Userモデルと1対1のリレーションを張る
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
