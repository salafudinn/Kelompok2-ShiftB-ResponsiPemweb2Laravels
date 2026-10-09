<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssistantAccountRequest;
use App\Http\Requests\UpdateAssistantAccountRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AssistantAccountController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $assistants = User::query()
            ->where('role', 'asisten_lab')
            ->orderBy('name')
            ->get();

        return UserResource::collection($assistants);
    }

    public function store(StoreAssistantAccountRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'asisten_lab';

        $assistant = User::create($data);

        return response()->json([
            'message' => 'Akun asisten lab berhasil dibuat.',
            'data' => new UserResource($assistant),
        ], 201);
    }

    public function update(UpdateAssistantAccountRequest $request, string $assistant): JsonResponse
    {
        $account = $this->findAssistant($assistant);
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $account->update($data);

        return response()->json([
            'message' => 'Akun asisten lab berhasil diperbarui.',
            'data' => new UserResource($account),
        ]);
    }

    public function destroy(string $assistant): JsonResponse
    {
        return DB::transaction(function () use ($assistant): JsonResponse {
            $account = User::query()
                ->where('role', 'asisten_lab')
                ->lockForUpdate()
                ->findOrFail($assistant);

            if ($account->borrowings()->exists() || $account->damageReports()->exists()) {
                return response()->json([
                    'message' => 'Akun tidak dapat dihapus karena memiliki histori peminjaman atau laporan kerusakan.',
                ], 409);
            }

            $account->tokens()->delete();
            $account->delete();

            return response()->json([
                'message' => 'Akun asisten lab berhasil dihapus.',
            ]);
        });
    }

    private function findAssistant(string $id): User
    {
        return User::query()
            ->where('role', 'asisten_lab')
            ->findOrFail($id);
    }
}
