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
}