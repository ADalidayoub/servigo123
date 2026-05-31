<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('filter')) {
            switch ($request->filter) {
                case 'active':
                    $query->where('is_banned', false);
                    break;
                case 'banned':
                    $query->where('is_banned', true);
                    break;
            }
        }

        $customers = $query->latest()->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'photo' => $user->photo ?? null,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'is_banned' => (bool) $user->is_banned,
                'joined_at' => $user->created_at->toDateString(),
            ];
        });

        $totalCustomers = User::where('role', 'user')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_customers' => $totalCustomers,
                'customers' => $customers,
            ],
        ]);
    }

    public function show($userId)
    {
        $user = User::where('role', 'user')->find($userId);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'customer_not_found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'photo' => $user->photo ?? null,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'is_banned' => (bool) $user->is_banned,
                'joined_at' => $user->created_at->toDateString(),
            ],
        ]);
    }

    public function ban($userId)
    {
        $user = User::where('role', 'user')->find($userId);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'customer_not_found'], 404);
        }

        $user->update(['is_banned' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Customer banned successfully',
        ]);
    }

    public function unban($userId)
    {
        $user = User::where('role', 'user')->find($userId);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'customer_not_found'], 404);
        }

        $user->update(['is_banned' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Customer unbanned successfully',
        ]);
    }

    public function delete($userId)
    {
        $user = User::where('role', 'user')->find($userId);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'customer_not_found'], 404);
        }

        Chat::where('participant_one', $user->id)
            ->orWhere('participant_two', $user->id)
            ->delete();

        Rating::where('user_id', $user->id)->update(['user_id' => null]);

        $user->tokens()->delete();
        $user->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully. Reviews and comments remain as "مستخدم محذوف".',
        ]);
    }

    public function update(Request $request, $userId)
    {
        $user = User::where('role', 'user')->find($userId);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'customer_not_found'], 404);
        }

        $fields = $request->only(['name', 'phone', 'email']);

        if (isset($fields['email'])) {
            $exists = User::where('email', $fields['email'])
                ->where('id', '!=', $userId)->exists();
            if ($exists) {
                return response()->json(['success' => false, 'message' => 'email_already_taken'], 422);
            }
        }

        if (!empty($fields)) {
            $user->update($fields);
        }

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully',
        ]);
    }
}
