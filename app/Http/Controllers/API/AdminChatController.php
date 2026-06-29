<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminChatController extends Controller
{
    public function listAdmins()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'unauthorized'], 401);
        }

        $admins = Admin::all();

        $existingChats = AdminChat::where('user_id', $user->id)
            ->with(['messages' => function ($q) {
                $q->latest()->limit(1);
            }])
            ->get()
            ->keyBy('admin_id');

        $data = $admins->map(function ($admin) use ($existingChats) {
            $chat = $existingChats->get($admin->id);

            return [
                'admin_id' => $admin->id,
                'name' => $admin->name,
                'photo' => $admin->photo,
                'chat_id' => $chat?->id,
                'last_message' => $chat?->messages->first()?->content ?? '',
                'last_message_time' => $chat?->messages->first()?->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}