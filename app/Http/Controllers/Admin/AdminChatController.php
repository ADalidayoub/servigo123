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
        public function getMessages($adminChatId)
    {
        $admin = auth()->user();

        $adminChat = AdminChat::find($adminChatId);

        if (!$adminChat) {
            return response()->json(['success' => false, 'message' => 'chat_not_found'], 404);
        }

        if ($adminChat->admin_id !== $admin->id) {
            return response()->json(['success' => false, 'message' => 'forbidden'], 403);
        }

        $messages = $adminChat->messages()->orderBy('created_at', 'asc')->get();

        $data = $messages->map(function ($message) use ($admin) {
            return [
                'id' => $message->id,
                'sender_type' => $message->sender_type,
                'sender_id' => $message->sender_id,
                'content' => $message->content,
                'image_url' => $message->image_url,
                'video_url' => $message->video_url,
                'created_at' => $message->created_at->toDateTimeString(),
                'time' => $message->created_at->format('H:i'),
                'date' => $message->created_at->format('Y-m-d'),
                'is_mine' => $message->sender_type === 'admin' && $message->sender_id === $admin->id,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
        public function sendMessage(Request $request, $adminChatId)
    {
        $admin = auth()->user();

        $adminChat = AdminChat::find($adminChatId);

        if (!$adminChat) {
            return response()->json(['success' => false, 'message' => 'chat_not_found'], 404);
        }

        if ($adminChat->admin_id !== $admin->id) {
            return response()->json(['success' => false, 'message' => 'forbidden'], 403);
        }

        $request->validate([
            'content' => 'nullable|string',
            'image_url' => 'nullable|string',
            'video_url' => 'nullable|string',
        ]);

        $message = $adminChat->messages()->create([
            'sender_type' => 'admin',
            'sender_id' => $admin->id,
            'content' => $request->content,
            'image_url' => $request->image_url,
            'video_url' => $request->video_url,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $message->id,
                'sender_type' => $message->sender_type,
                'sender_id' => $message->sender_id,
                'content' => $message->content,
                'image_url' => $message->image_url,
                'video_url' => $message->video_url,
                'created_at' => $message->created_at->toDateTimeString(),
                'time' => $message->created_at->format('H:i'),
                'date' => $message->created_at->format('Y-m-d'),
                'is_mine' => true,
            ],
        ], 201);
    }



}