<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommentReport;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AdminReportController extends Controller
{
    private function getUserPhoto($user)
    {
        return $user && $user->photo && Storage::disk('public')->exists($user->photo)
            ? asset('storage/' . $user->photo)
            : null;
    }

    // ==================== بلاغات المزودين (Provider Comment Reports) ====================

    public function commentReports()
    {
        $reports = CommentReport::with(['rating.user', 'provider'])
            ->latest()
            ->get()
            ->map(function ($report) {
                return [
                    'id' => $report->id,
                    'rating_id' => $report->rating_id,
                    'comment_text' => $report->rating?->review,
                    'customer' => [
                        'id' => $report->rating?->user?->id,
                        'name' => $report->rating?->user?->name ?? 'مستخدم محذوف',
                        'photo' => $this->getUserPhoto($report->rating?->user),
                    ],
                    'provider' => [
                        'id' => $report->provider?->id,
                        'name' => $report->provider?->name ?? 'مزود محذوف',
                    ],
                    'reason' => $report->reason,
                    'created_at' => $report->created_at->toDateTimeString(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'count' => $reports->count(),
                'reports' => $reports,
            ],
        ]);
    }

    public function banCommentAuthor($reportId)
    {
        $report = CommentReport::with('rating.user')->find($reportId);

        if (!$report) {
            return response()->json(['success' => false, 'message' => 'report_not_found'], 404);
        }

        $customer = $report->rating?->user;

        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'customer_not_found'], 404);
        }

        $customer->update(['is_banned' => true]);

        return response()->json([
            'success' => true,
            'message' => 'customer_banned_successfully',
        ]);
    }

    public function dismissCommentReport($reportId)
    {
        $report = CommentReport::find($reportId);

        if (!$report) {
            return response()->json(['success' => false, 'message' => 'report_not_found'], 404);
        }

        $report->delete();

        return response()->json([
            'success' => true,
            'message' => 'report_dismissed_successfully',
        ]);
    }

    // ==================== بلاغات الزبائن (Customer Complaints) ====================

    public function complaints()
    {
        $complaints = Complaint::with(['user', 'provider'])
            ->latest()
            ->get()
            ->map(function ($complaint) {
                return [
                    'id' => $complaint->id,
                    'customer' => [
                        'id' => $complaint->user?->id,
                        'name' => $complaint->user?->name ?? 'مستخدم محذوف',
                        'photo' => $this->getUserPhoto($complaint->user),
                    ],
                    'provider' => [
                        'id' => $complaint->provider?->id,
                        'name' => $complaint->provider?->name ?? 'مزود محذوف',
                        'photo' => $this->getUserPhoto($complaint->provider),
                    ],
                    'message' => $complaint->message,
                    'created_at' => $complaint->created_at->toDateTimeString(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'count' => $complaints->count(),
                'complaints' => $complaints,
            ],
        ]);
    }

    public function banComplaintProvider($complaintId)
    {
        $complaint = Complaint::with('provider')->find($complaintId);

        if (!$complaint) {
            return response()->json(['success' => false, 'message' => 'complaint_not_found'], 404);
        }

        $provider = $complaint->provider;

        if (!$provider) {
            return response()->json(['success' => false, 'message' => 'provider_not_found'], 404);
        }

        $provider->update(['is_banned' => true]);

        return response()->json([
            'success' => true,
            'message' => 'provider_banned_successfully',
        ]);
    }

    public function dismissComplaint($complaintId)
    {
        $complaint = Complaint::find($complaintId);

        if (!$complaint) {
            return response()->json(['success' => false, 'message' => 'complaint_not_found'], 404);
        }

        $complaint->delete();

        return response()->json([
            'success' => true,
            'message' => 'complaint_dismissed_successfully',
        ]);
    }
}
