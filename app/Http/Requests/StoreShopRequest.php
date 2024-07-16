<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Ramsey\Uuid\Type\Integer;

class StoreShopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string",
            "logo" => "nullable|string",
            "images" => "nullable|string",
            "governorate" => "integer",
            "city" => "integer",
            "address" => "nullable|string",
            "location" => "string",
            "created_by" => "required|integer",
        ];
    }
}
