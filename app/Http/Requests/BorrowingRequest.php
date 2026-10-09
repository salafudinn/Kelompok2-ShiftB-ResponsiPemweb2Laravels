<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BorrowingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'mahasiswa';
    }

    public function rules(): array
    {
        return [
            'iot_kit_id' => 'required|exists:iot_kits,id',
            'quantity' => 'required|integer|min:1',
            'borrow_date' => 'required|date',
            'expected_return_date' => 'required|date|after:borrow_date',
        ];
    }
}
