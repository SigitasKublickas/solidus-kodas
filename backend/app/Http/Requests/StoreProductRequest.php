<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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
            "name" => ["required", "string"],
            "desc" => ["required", "string"],
            "price" => ["required", "integer"],
            "delivery_time" => ["required", "integer"],
            "stock" => ["required", "integer"],
            "img_url" => ["required", "string"],
            "category_id" => ["required", "integer"],
            "brand" => ["required", "string"],
            "model" => ["required", "string"],
            "condition" => ["required", "string", Rule::in(["New", "Used", "Refurbished"])],

        ];
    }
}
