<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorrowingRequest;
use App\Http\Resources\BorrowingResource;
use App\Models\Borrowing;
use App\Models\IoTKit;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BorrowingController extends Controller
{
    /**
     * GET /api/borrowings
     * Mahasiswa: riwayat milik sendiri. Asisten: semua data.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();

        if ($user->role === 'mahasiswa') {
            $borrowings = Borrowing::with('iotKit')
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(10);
        } else {
            $borrowings = Borrowing::with(['iotKit', 'user'])
                ->latest()
                ->paginate(10);
        }

        return BorrowingResource::collection($borrowings);
    }

    /**
     * POST /api/borrowings
     * Mahasiswa mengajukan peminjaman baru.
     */
    public function store(BorrowingRequest $request): JsonResponse
    {
        $kit = IoTKit::findOrFail($request->iot_kit_id);

        if ($kit->stock < $request->quantity) {
            throw ValidationException::withMessages([
                'quantity' => ['Stok tidak mencukupi untuk alat ini.'],
            ]);
        }

        $borrowing = Borrowing::create([
            'user_id' => $request->user()->id,
            'iot_kit_id' => $request->iot_kit_id,
            'quantity' => $request->quantity,
            'borrow_date' => $request->borrow_date,
            'expected_return_date' => $request->expected_return_date,
            'status' => 'PENDING',
        ]);

        $borrowing->load('iotKit');

        return response()->json([
            'message' => 'Pengajuan peminjaman berhasil dibuat.',
            'data' => new BorrowingResource($borrowing),
        ], 201);
    }

    /**
     * PUT /api/borrowings/{id}/approve
     * Asisten Lab menyetujui pengajuan. Stok berkurang.
     */
    public function approve(Request $request, int $id): JsonResponse
    {
        return DB::transaction(function () use ($id) {
            $borrowing = Borrowing::findOrFail($id);

            if ($borrowing->status !== 'PENDING') {
                return response()->json(['message' => 'Hanya pengajuan PENDING yang bisa di-approve.'], 422);
            }

            $kit = IoTKit::findOrFail($borrowing->iot_kit_id);

            if ($kit->stock < $borrowing->quantity) {
                throw ValidationException::withMessages(['status' => 'Stok tidak mencukupi untuk approve.']);
            }

            $kit->decrement('stock', $borrowing->quantity);
            $borrowing->update(['status' => 'APPROVED']);
            $borrowing->load(['iotKit', 'user']);

            return response()->json([
                'message' => 'Peminjaman disetujui. Lokasi alat: ' . $kit->storage_location,
                'data' => new BorrowingResource($borrowing),
            ]);
        });
    }

    /**
     * PUT /api/borrowings/{id}/reject
     * Asisten Lab menolak pengajuan.
     */
    public function reject(Request $request, int $id): JsonResponse
    {
        $borrowing = Borrowing::findOrFail($id);

        if ($borrowing->status !== 'PENDING') {
            return response()->json(['message' => 'Hanya pengajuan PENDING yang bisa ditolak.'], 422);
        }

        $borrowing->update(['status' => 'REJECTED']);
        $borrowing->load(['iotKit', 'user']);

        return response()->json([
            'message' => 'Peminjaman ditolak.',
            'data' => new BorrowingResource($borrowing),
        ]);
    }

    /**
     * PUT /api/borrowings/{id}/pickup
     * Asisten Lab menyerahkan barang fisik ke mahasiswa. Status → ON_LOAN.
     */
    public function pickup(Request $request, int $id): JsonResponse
    {
        $borrowing = Borrowing::findOrFail($id);

        if ($borrowing->status !== 'APPROVED') {
            return response()->json(['message' => 'Hanya peminjaman APPROVED yang bisa diserahkan.'], 422);
        }

        $borrowing->update(['status' => 'ON_LOAN']);
        $borrowing->load(['iotKit', 'user']);

        return response()->json([
            'message' => 'Barang diserahkan ke mahasiswa. Status: ON_LOAN.',
            'data' => new BorrowingResource($borrowing),
        ]);
    }

    /**
     * PUT /api/borrowings/{id}/return
     * Asisten Lab menerima pengembalian barang. Hitung denda otomatis.
     */
    public function returnItem(Request $request, int $id): JsonResponse
    {
        return DB::transaction(function () use ($id) {
            $borrowing = Borrowing::findOrFail($id);

            if (! in_array($borrowing->status, ['ON_LOAN', 'OVERDUE'])) {
                return response()->json([
                    'message' => 'Hanya peminjaman ON_LOAN atau OVERDUE yang bisa dikembalikan.',
                ], 422);
            }

            $kit = IoTKit::findOrFail($borrowing->iot_kit_id);
            $today = Carbon::today();

            // Hitung denda dengan method di model
            $denda = $borrowing->hitungDenda($today);

            $borrowing->update([
                'status' => 'RETURNED',
                'actual_return_date' => $today,
                'fine_amount' => $denda,
            ]);

            $kit->increment('stock', $borrowing->quantity);
            $borrowing->load(['iotKit', 'user']);

            $msg = 'Barang dikembalikan.';
            if ($denda > 0) {
                $msg .= ' Denda keterlambatan: Rp ' . number_format($denda, 0, ',', '.');
            }

            return response()->json([
                'message' => $msg,
                'data' => new BorrowingResource($borrowing),
            ]);
        });
    }
}
