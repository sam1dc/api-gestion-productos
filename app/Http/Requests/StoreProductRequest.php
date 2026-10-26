<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                => ['required', 'string', 'max:255'],
            'description'         => ['nullable', 'string'],
            'price'               => ['required', 'numeric', 'min:0'],
            'currency_id'         => ['required', 'integer', 'exists:currencies,id'],
            'tax_cost'            => ['sometimes', 'numeric', 'min:0'],
            'manufacturing_cost'  => ['sometimes', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'El nombre del producto es obligatorio.',
            'name.max'             => 'El nombre no debe superar 255 caracteres.',
            'price.required'       => 'El precio es obligatorio.',
            'price.numeric'        => 'El precio debe ser un valor numérico.',
            'price.min'            => 'El precio no puede ser negativo.',
            'currency_id.required' => 'La divisa base es obligatoria.',
            'currency_id.exists'   => 'La divisa seleccionada no existe.',
            'tax_cost.numeric'     => 'El costo de impuestos debe ser un valor numérico.',
            'tax_cost.min'         => 'El costo de impuestos no puede ser negativo.',
            'manufacturing_cost.numeric' => 'El costo de fabricación debe ser numérico.',
            'manufacturing_cost.min'     => 'El costo de fabricación no puede ser negativo.',
        ];
    }

    /**
     * Retorna errores de validación como JSON (sin redirección HTML).
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Los datos proporcionados no son válidos.',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}
