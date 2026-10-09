<?php

namespace App\Http\Controllers;

use App\Http\Requests\IoTKitRequest;
use App\Http\Resources\IoTKitResource;
use App\Models\IoTKit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;

class IoTKitController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = IoTKit::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $kits = $query->paginate(10);

        return IoTKitResource::collection($kits);
    }

    public function show($id): IoTKitResource
    {
        $kit = IoTKit::findOrFail($id);

        return new IoTKitResource($kit);
    }

    public function store(IoTKitRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('iot-kits', 'public');
        }

        unset($data['image'], $data['remove_image']);

        $kit = IoTKit::create($data);

        return response()->json([
            'message' => 'Alat berhasil ditambahkan ke katalog',
            'data' => new IoTKitResource($kit),
        ], 201);
    }

    public function update(IoTKitRequest $request, $id): JsonResponse
    {
        $kit = IoTKit::findOrFail($id);
        $data = $request->validated();

        // 1. Kalau user minta hapus gambar existing
        if ($request->boolean('remove_image')) {
            if ($kit->image_path && Storage::disk('public')->exists($kit->image_path)) {
                Storage::disk('public')->delete($kit->image_path);
            }
            $data['image_path'] = null;
        }

        // 2. Kalau user upload gambar baru, timpa yang lama
        if ($request->hasFile('image')) {
            if ($kit->image_path && Storage::disk('public')->exists($kit->image_path)) {
                Storage::disk('public')->delete($kit->image_path);
            }
            $data['image_path'] = $request->file('image')->store('iot-kits', 'public');
        }

        unset($data['image'], $data['remove_image']);

        $kit->update($data);

        return response()->json([
            'message' => 'Alat berhasil diperbarui',
            'data' => new IoTKitResource($kit),
        ], 200);
    }

    public function destroy($id): JsonResponse
    {
        $kit = IoTKit::findOrFail($id);

        if ($kit->image_path && Storage::disk('public')->exists($kit->image_path)) {
            Storage::disk('public')->delete($kit->image_path);
        }

        $kit->delete();

        return response()->json([
            'message' => 'Alat berhasil dihapus permanen',
        ], 200);
    }
}