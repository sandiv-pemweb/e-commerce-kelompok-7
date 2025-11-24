<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellerStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Any authenticated user can try to register/update store
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'about' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:10'],
            'address_id' => ['required', 'string', 'max:255'],
        ];

        if ($this->isMethod('post')) {
            $rules['logo'] = ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'];
        } else {
            $rules['logo'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'];
            $rules['bank_name'] = ['nullable', 'string', 'max:255'];
            $rules['bank_account_name'] = ['nullable', 'string', 'max:255'];
            $rules['bank_account_number'] = ['nullable', 'string', 'max:50'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama toko harus diisi.',
            'logo.required' => 'Logo toko harus diunggah.',
            'logo.image' => 'File harus berupa gambar.',
            'logo.max' => 'Ukuran logo maksimal 2MB.',
            'about.required' => 'Deskripsi toko harus diisi.',
            'phone.required' => 'Nomor telepon harus diisi.',
            'address.required' => 'Alamat lengkap harus diisi.',
            'city.required' => 'Kota harus diisi.',
            'postal_code.required' => 'Kode pos harus diisi.',
            'address_id.required' => 'Lokasi peta harus dipilih.',
        ];
    }
}
