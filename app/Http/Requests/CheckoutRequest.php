<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'     => 'required|string|max:60',
            'last_name'      => 'required|string|max:60',
            'email'          => 'required|email|max:150',
            'phone'          => 'required|string|max:20',
            'dni'            => 'nullable|string|max:12',
            'department'     => 'required|string|max:60',
            'province'       => 'required|string|max:60',
            'district'       => 'required|string|max:60',
            'address'        => 'required|string|max:200',
            'reference'      => 'nullable|string|max:200',
            'payment_method' => 'required|in:mercadopago,contra_entrega',
            'notes'          => 'nullable|string|max:500',
            'save_address'   => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required'     => 'El nombre es obligatorio.',
            'last_name.required'      => 'El apellido es obligatorio.',
            'email.required'          => 'El correo electrónico es obligatorio.',
            'email.email'             => 'El correo electrónico no es válido.',
            'phone.required'          => 'El teléfono es obligatorio.',
            'department.required'     => 'El departamento es obligatorio.',
            'province.required'       => 'La provincia es obligatoria.',
            'district.required'       => 'El distrito es obligatorio.',
            'address.required'        => 'La dirección es obligatoria.',
            'payment_method.required' => 'Selecciona un método de pago.',
            'payment_method.in'       => 'Método de pago no válido.',
        ];
    }
}
