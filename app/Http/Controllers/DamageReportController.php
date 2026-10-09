<?php

namespace App\Http\Controllers;

use App\Http\Requests\DamageReportRequest;
use App\Http\Resources\DamageReportResource;
use App\Models\DamageReport;
use App\Models\IoTKit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class DamageReportController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();

        if ($user->role === 'mahasiswa') {
            $reports = DamageReport::with(['iotKit', 'reporter'])
                ->where('reporter_id', $user->id)
                ->latest()
                ->paginate(10);
        } else {
            $reports = DamageReport::with(['iotKit', 'reporter'])
                ->latest()
                ->paginate(10);
        }

        return DamageReportResource::collection($reports);
    }

    public function store(DamageReportRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $imagePath = null;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('damage-reports', 'public');

                if (! is_string($imagePath)) {
                    throw new RuntimeException('Gagal menyimpan gambar laporan insiden.');
                }
            }

            $report = DamageReport::create([
                'borrowing_id' => $request->borrowing_id,
                'iot_kit_id' => $request->iot_kit_id,
                'reporter_id' => $request->user()->id,
                'damage_type' => $request->damage_type,
                'description' => $request->description,
                'image_path' => $imagePath,
                'repair_status' => 'REPORTED',
            ]);

            $report->load('iotKit');

            return response()->json([
                'message' => 'Laporan kerusakan berhasil disubmit',
                'data' => new DamageReportResource($report),
            ], 201);
        });
    }

    public function resolve(Request $request, $id): JsonResponse
    {
        $request->validate([
            'repair_status' => 'required|in:IN_REPAIR,RESOLVED,DISCARDED',
            'repair_note' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request, $id) {
            $report = DamageReport::findOrFail($id);
            $kit = IoTKit::findOrFail($report->iot_kit_id);

            $report->update([
                'repair_status' => $request->repair_status,
                'repair_note' => $request->repair_note ?? $report->repair_note,
                'repairer_name' => $request->user()->name,
            ]);

            if ($request->repair_status === 'IN_REPAIR') {
                $kit->update(['status' => 'MAINTENANCE']);
            } elseif (in_array($request->repair_status, ['RESOLVED', 'DISCARDED'], true)) {
                $kit->update(['status' => 'AVAILABLE']);
            }

            $report->load(['iotKit', 'reporter']);

            return response()->json([
                'message' => 'Status perbaikan diperbarui',
                'data' => new DamageReportResource($report),
            ]);
        });
    }
}
