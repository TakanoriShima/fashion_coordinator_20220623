<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Room;

class Chat extends Model
{
    protected $fillable = ['room_id', 'user_id', 'message', 'image'];
    
    /**
     * このチャットを所有するルーム（Roomモデルとの多対1の関係を定義）
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}