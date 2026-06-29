<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\AdminMessage;


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
        public function sendMessage(Request $request, $adminId)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'unauthorized'], 401);
        }

        $admin = Admin::find($adminId);
        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'admin_not_found'], 404);
        }

        $request->validate([
            'content' => 'required_without_all:image,video|string|nullable',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:10240',
            'video' => 'nullable|mimes:mp4,mov,avi,mpg|max:51200',
        ]);

        $adminChat = AdminChat::firstOrCreate([
            'admin_id' => $admin->id,
            'user_id' => $user->id,
        ]);

        $imageUrl = null;
        $videoUrl = null;

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = uniqid() . '_' . time() . '.' . $imageFile->getClientOriginalExtension();
            $imagePath = 'admin_chats/images/' . $imageName;
            Storage::disk('public')->put($imagePath, file_get_contents($imageFile->getPathname()));
            $imageUrl = Storage::url($imagePath);
        }

        if ($request->hasFile('video')) {
            $videoFile = $request->file('video');
            $videoName = uniqid() . '_' . time() . '.' . $videoFile->getClientOriginalExtension();
            $videoPath = 'admin_chats/videos/' . $videoName;
            Storage::disk('public')->put($videoPath, file_get_contents($videoFile->getPathname()));
            $videoUrl = Storage::url($videoPath);
        }

        $message = AdminMessage::create([
            'admin_chat_id' => $adminChat->id,
            'sender_type' => 'user',
            'sender_id' => $user->id,
            'content' => $request->input('content'),
            'image_url' => $imageUrl,
            'video_url' => $videoUrl,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'message_sent',
            'data' => [
                'admin_chat_id' => $adminChat->id,
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
            ]
        ], 201);
    }
        public function getMessages($adminChatId)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'unauthorized'], 401);
        }

        $adminChat = AdminChat::find($adminChatId);

        if (!$adminChat) {
            return response()->json(['success' => false, 'message' => 'chat_not_found'], 404);
        }

        if ($adminChat->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'forbidden'], 403);
        }

        $messages = $adminChat->messages()->orderBy('created_at', 'asc')->get();

        $data = $messages->map(function ($message) use ($user) {
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
                'is_mine' => $message->sender_type === 'user' && $message->sender_id === $user->id,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    



    
}