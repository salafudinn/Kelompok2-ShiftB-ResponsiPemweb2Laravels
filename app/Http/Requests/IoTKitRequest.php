<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IoTKitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'asisten_lab';
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'code' => 'required|string|unique:iot_kits,code' . ($id ? ",$id" : ''),
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'storage_location' => 'nullable|string|max:255',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:AVAILABLE,MAINTENANCE',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_image' => 'nullable|boolean',
        ];
    }
}