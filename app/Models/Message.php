<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'chat_id',
        'content',
        'sended_from',
        'sceen',
        'status',
        'is_deleted',
        'datetime'
    ];
    protected $casts = [
        'status' => 'boolean',
        'is_deleted' => 'boolean'
    ];
    public function scopeGetTheMessagesList($query,$chatId)
    {
        return $query->where('chat_id',$chatId)->orderBy('id', 'ASC');
    }
    public function scopeStore($query, $data)
    {
        $now = Carbon::now();
        $user = new Message([
            'chat_id' => $data['chatId'],
            'content' => $data['message'],
            'sended_from' => $data['from'],
            'datetime' => $now
        ]);
        $user->save();
        return $user;
    }
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

}
