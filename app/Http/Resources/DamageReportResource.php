<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DamageReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'damage_type' => $this->damage_type,
            'description' => $this->description,
            'image_url' => $this->image_path ? asset('storage/'.$this->image_path) : null,
            'repair_status' => $this->repair_status,
            'repair_note' => $this->repair_note,
            'repairer_name' => $this->repairer_name,
            'reporter' => $this->whenLoaded('reporter', fn () => [
                'id' => $this->reporter->id,
                'name' => $this->reporter->name,
            ]),
            'iot_kit' => new IoTKitResource($this->whenLoaded('iotKit')),
            'borrowing_id' => $this->borrowing_id,
        ];
    }
}
