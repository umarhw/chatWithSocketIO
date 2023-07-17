<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Chat as ChatModel;
use Carbon\Carbon;

class Chat extends Model
{
    use HasFactory;
    protected $fillable = [
        'from_user',
        'to_user',
        'last_message',
        'unseen',
        'datetime',
        'status',
        'is_deleted',
        'created_at',
        'updated_at',
    ];
    public function scopeList($query,$userId)
    {
        $users = User::leftJoin('chats', function ($join) use ($userId) {
            $join->on('users.id', '=', 'chats.from_user')
                ->where('chats.to_user', $userId)
                ->orWhere(function ($query) use ($userId) {
                    $query->on('users.id', '=', 'chats.to_user')
                        ->where('chats.from_user', $userId);
                });
        })
        ->whereNotNull('chats.id') // Only retrieve users with chat records
        ->where('users.id', '!=', $userId) // Exclude the current logged-in user
        ->select('users.id as user_id', 'users.name as user_name', 'chats.id AS chat_id', 'chats.datetime', 'chats.last_message as lastMessage','chats.unseen')
        ->orderByDesc('chats.datetime')
        ->get();
    
    // $pendingUsers = User::whereNotIn('id', function ($query) use ($userId) {
    //         $query->select('from_user')
    //             ->from('chats')
    //             ->where('from_user', '=', $userId)
    //             ->orWhere('to_user', '=', $userId);
    //     })
    //     ->where('id', '!=', $userId) // Exclude the current logged-in user
    //     ->select('id', 'name', DB::raw('null AS chat_id'), DB::raw('null AS datetime'), DB::raw('null AS last_message'))
    //     ->get();
    
    // $users = $users->concat($pendingUsers)->unique('id'); // Remove duplicate entries based on the 'id' field
        return $users;
    }
    public function scopeUpdateData($query,$data) {
        $now = Carbon::now();
        $user = $query->findOrFail($data['chatId']);
        $user->fill([
            'last_message' => $data['message'],
            'datetime' => $now,
        ]);
        $user->save();
        return $user;
    }
    public function scopeUpdateCounter($query,$recordId,$data) {
        $user = $query->findOrFail($recordId);
        $user->fill([
            'unseen' => $data['counter'],
        ]);
        $user->save();
        return $user;
    }
    public function scopeRegisterAllUsersIntoChat($query, $list,$from)
    {
        if (!$list->isEmpty()) {
            foreach ($list as $item) {
                Chat::create([
                    'from_user' => $from,
                    'to_user' => $item->id,
                    'last_message' => null,
                    'unseen' => 0,
                    'datetime' => null,
                    'status' => 1,
                    'is_deleted' => 0,
                    'is_deleted' => 0,
                    'created_at' => null,
                    'updated_at' => null,
                ]);
            }
        }
    }
}
