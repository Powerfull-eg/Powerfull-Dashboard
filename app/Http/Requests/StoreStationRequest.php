<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStationRequest extends FormRequest
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
            "inet_id" => "required|unique:stations,inet_id",
            "status" => "required",
            "signal_value" => "required",
            "type" => "required",
            "merchant_id" => "required|exists:merchants,id",
            "slots" => "required|integer",
            "rentable_slots" => "integer",
            "return_slots" => "required|integer",
            "fault_slots" => "integer",
            "internet_card" => "nullable|integer",
            "device_ip" => "required|ip",
            "server_ip" => "required|ip",
            "port" => "required|integer",
            "authorize" => "required",
            "created_by" => "required"
        ];
    }
}
