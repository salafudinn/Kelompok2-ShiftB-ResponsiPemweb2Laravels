<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DamageReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Any authenticated user can report damage
    }

    public function rules(): array
    {
        return [
            'borrowing_id' => 'nullable|exists:borrowings,id',
            'iot_kit_id' => 'required|exists:iot_kits,id',
            'damage_type' => 'required|in:MINOR_COMPONENT,BROKEN_BOARD,MISSING_PARTS',
            'description' => 'required|string',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }
}
