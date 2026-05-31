<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Complaint;
use App\Models\Provider;
use App\Models\Rating;
use App\Models\SearchLog;
use App\Models\Service;
use App\Models\SubService;
use App\Models\User;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function statistics()
    {
        $totalProviders = User::where('role', 'provider')->count();
        $totalCustomers = User::where('role', 'user')->count();
        $totalUsers = $totalProviders + $totalCustomers;

        $pendingRegistrations = Provider::where('status', 'pending')->count();
        $activeAds = Ad::where('is_active', true)->count();
        $pendingComplaints = Complaint::where('status', 'pending')->count();
        $totalRatings = Rating::count();

        $thisMonthUsers = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $lastMonthUsers = User::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        $growthPercentage = $lastMonthUsers > 0
            ? round((($thisMonthUsers - $lastMonthUsers) / $lastMonthUsers) * 100, 1)
            : ($thisMonthUsers > 0 ? 100 : 0);

        return response()->json([
            'success' => true,
            'data' => [
                'total_providers' => $totalProviders,
                'total_customers' => $totalCustomers,
                'total_users' => $totalUsers,
                'pending_registrations' => $pendingRegistrations,
                'active_ads' => $activeAds,
                'pending_complaints' => $pendingComplaints,
                'total_ratings' => $totalRatings,
                'growth_percentage' => $growthPercentage,
            ],
        ]);
    }

    public function charts()
    {
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months->push([
                'month' => $date->format('Y-m'),
                'label' => $date->format('M Y'),
                'count' => User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ]);
        }

        $totalProviders = User::where('role', 'provider')->count();
        $totalCustomers = User::where('role', 'user')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'user_growth' => $months,
                'user_distribution' => [
                    'providers' => $totalProviders,
                    'customers' => $totalCustomers,
                ],
            ],
        ]);
    }

    public function topSearchedServices()
    {
        $totalSearches = SearchLog::count();

        $topMainServices = SearchLog::selectRaw('main_service_id, COUNT(*) as search_count')
            ->groupBy('main_service_id')
            ->orderByDesc('search_count')
            ->limit(3)
            ->get()
            ->map(function ($log) use ($totalSearches) {
                $service = Service::find($log->main_service_id);
                return [
                    'id' => $log->main_service_id,
                    'name_ar' => $service?->name_ar,
                    'name_en' => $service?->name_en,
                    'search_count' => $log->search_count,
                    'percentage' => $totalSearches > 0
                        ? round(($log->search_count / $totalSearches) * 100, 1)
                        : 0,
                ];
            });

        $topSubServices = SearchLog::selectRaw('sub_service_id, COUNT(*) as search_count')
            ->groupBy('sub_service_id')
            ->orderByDesc('search_count')
            ->limit(3)
            ->get()
            ->map(function ($log) use ($totalSearches) {
                $subService = SubService::find($log->sub_service_id);
                return [
                    'id' => $log->sub_service_id,
                    'name_ar' => $subService?->name_ar,
                    'name_en' => $subService?->name_en,
                    'search_count' => $log->search_count,
                    'percentage' => $totalSearches > 0
                        ? round(($log->search_count / $totalSearches) * 100, 1)
                        : 0,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'total_searches' => $totalSearches,
                'top_main_services' => $topMainServices,
                'top_sub_services' => $topSubServices,
            ],
        ]);
    }
}
