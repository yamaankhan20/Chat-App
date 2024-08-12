<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'content',
        'seen'
    ];
    protected $appends = [
      "time_ago"
    ];

    public function sender(){
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
    public function getTimeAgoAttribute(){
        return $this->created_at->diffForHumans();
    }
    public static function get_messages_between_two_users($request ,$receiver_id, $sender_id)
    {
        $query = self::with(['sender', 'receiver'])->where(function ($q) use($request, $receiver_id, $sender_id) {
            $q->where(function ($sub) use($receiver_id, $sender_id) {
                $sub->where('sender_id', $sender_id)
                    ->Where('receiver_id', $receiver_id);
            })
            ->orwhere(function ($sub) use($receiver_id, $sender_id) {
                $sub->where('receiver_id', $sender_id)
                    ->Where('sender_id', $receiver_id);
            });
        });
        return $query;
    }
}
