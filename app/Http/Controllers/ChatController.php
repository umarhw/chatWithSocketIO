<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateCounterRequest;
use Carbon\Carbon;

class ChatController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $leftPanel = Chat::list($userId);
        return view('chat.index', compact('leftPanel','userId'));
    }
    public function GetMessages(Request $request)
    {
        $chatId = $request->input('chatId');
        $list = array();
        if(!empty($chatId)) {
            $list = Message::getTheMessagesList($chatId)->get();
        }
        return response()->json(['status' => 'success', 'list' => $list]);
    }
    public function StoreMessage(StoreMessageRequest $request)
    {
        $validatedData = $request->validated();
        Message::store($validatedData);
        Chat::updateData($validatedData);
        return response()->json(['status' => 'success']);
    }
    public function UpdateCounter(UpdateCounterRequest $request, Chat $record)
    {
        $validatedData = $request->validated();
        $user = Chat::updateCounter($record->id, $validatedData);
        return response()->json(['status' => 'success']);
    }
}
