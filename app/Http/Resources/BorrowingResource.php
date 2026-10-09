<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'borrow_date' => $this->borrow_date?->toDateString(),
            'expected_return_date' => $this->expected_return_date?->toDateString(),
            'actual_return_date' => $this->actual_return_date?->toDateString(),
            'fine_amount' => $this->fine_amount,
            'status' => $this->status,
            'notes' => $this->notes,
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ]),
            'iot_kit' => new IoTKitResource($this->whenLoaded('iotKit')),
        ];
    }
}
