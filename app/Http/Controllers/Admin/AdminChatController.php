<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminChat;
use Illuminate\Http\Request;

class AdminChatController extends Controller
{
    public function startOrGetChat($userId)
    {
        $admin = auth()->user();

        $adminChat = AdminChat::firstOrCreate([
            'admin_id' => $admin->id,
            'user_id' => $userId,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'admin_chat_id' => $adminChat->id,
            ],
        ]);
    }
        public function chatList()
    {
        $admin = auth()->user();

        $chats = AdminChat::where('admin_id', $admin->id)
            ->with(['user', 'messages' => function ($q) {
                $q->latest()->limit(1);
            }])
            ->get();

        $data = $chats->map(function ($chat) {
            $lastMessage = $chat->messages->first();

            return [
                'admin_chat_id' => $chat->id,
                'user_id' => $chat->user->id,
                'user_name' => $chat->user->name,
                'user_photo' => $chat->user->photo,
                'last_message' => $lastMessage?->content ?? '',
                'last_message_time' => $lastMessage?->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

}