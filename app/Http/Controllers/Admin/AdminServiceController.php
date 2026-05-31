<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\SubService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminServiceController extends Controller
{
    // ==================== Main Services (Sections) ====================

    public function index(Request $request)
    {
        $query = Service::with('subServices');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_ar', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%");
            });
        }

        $services = $query->get()->map(function ($service) {
            return [
                'id' => $service->id,
                'name_ar' => $service->name_ar,
                'name_en' => $service->name_en,
                'photo' => $service->photo,
                'sub_services' => $service->subServices->map(function ($sub) {
                    return [
                        'id' => $sub->id,
                        'name_ar' => $sub->name_ar,
                        'name_en' => $sub->name_en,
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'total_services' => Service::count(),
                'services' => $services,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'sub_services' => 'nullable|array',
            'sub_services.*.name_ar' => 'required_with:sub_services|string|max:255',
            'sub_services.*.name_en' => 'required_with:sub_services|string|max:255',
        ]);

        $data = [
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
        ];

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('services', 'public');
        }

        $service = Service::create($data);

        if ($request->has('sub_services')) {
            foreach ($request->sub_services as $sub) {
                $service->subServices()->create([
                    'name_ar' => $sub['name_ar'],
                    'name_en' => $sub['name_en'],
                ]);
            }
        }

        $service->load('subServices');

        return response()->json([
            'success' => true,
            'message' => 'Service created successfully',
            'data' => [
                'id' => $service->id,
                'name_ar' => $service->name_ar,
                'name_en' => $service->name_en,
                'photo' => $service->photo,
                'sub_services' => $service->subServices->map(function ($sub) {
                    return [
                        'id' => $sub->id,
                        'name_ar' => $sub->name_ar,
                        'name_en' => $sub->name_en,
                    ];
                }),
            ],
        ], 201);
    }

    public function update(Request $request, $serviceId)
    {
        $service = Service::find($serviceId);

        if (!$service) {
            return response()->json(['success' => false, 'message' => 'service_not_found'], 404);
        }

        $request->validate([
            'name_ar' => 'sometimes|string|max:255',
            'name_en' => 'sometimes|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->filled('name_ar')) {
            $service->name_ar = $request->name_ar;
        }
        if ($request->filled('name_en')) {
            $service->name_en = $request->name_en;
        }
        if ($request->hasFile('photo')) {
            if ($service->photo) {
                Storage::disk('public')->delete($service->photo);
            }
            $service->photo = $request->file('photo')->store('services', 'public');
        }

        $service->save();

        return response()->json([
            'success' => true,
            'message' => 'Service updated successfully',
        ]);
    }

    public function destroy($serviceId)
    {
        $service = Service::find($serviceId);

        if (!$service) {
            return response()->json(['success' => false, 'message' => 'service_not_found'], 404);
        }

        $service->subServices()->delete();
        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service and all related sub-services deleted successfully',
        ]);
    }

    // ==================== Sub-Services ====================

    public function storeSubService(Request $request, $serviceId)
    {
        $service = Service::find($serviceId);

        if (!$service) {
            return response()->json(['success' => false, 'message' => 'service_not_found'], 404);
        }

        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
        ]);

        $subService = $service->subServices()->create([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sub-service created successfully',
            'data' => [
                'id' => $subService->id,
                'name_ar' => $subService->name_ar,
                'name_en' => $subService->name_en,
            ],
        ], 201);
    }

    public function updateSubService(Request $request, $subServiceId)
    {
        $subService = SubService::find($subServiceId);

        if (!$subService) {
            return response()->json(['success' => false, 'message' => 'sub_service_not_found'], 404);
        }

        $request->validate([
            'name_ar' => 'sometimes|string|max:255',
            'name_en' => 'sometimes|string|max:255',
        ]);

        $subService->update($request->only(['name_ar', 'name_en']));

        return response()->json([
            'success' => true,
            'message' => 'Sub-service updated successfully',
        ]);
    }

    public function destroySubService($subServiceId)
    {
        $subService = SubService::find($subServiceId);

        if (!$subService) {
            return response()->json(['success' => false, 'message' => 'sub_service_not_found'], 404);
        }

        $subService->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sub-service deleted successfully',
        ]);
    }
}
