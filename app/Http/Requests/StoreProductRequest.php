<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isSeller();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_category_id' => [
                'required',
                'exists:product_categories,id',
                function ($attribute, $value, $fail) {
                    $category = \App\Models\ProductCategory::find($value);
                    if ($category && $category->store_id !== auth()->user()->store->id) {
                        $fail('Kategori tidak valid.');
                    }
                },
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'weight' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'product_category_id.required' => 'Kategori produk harus dipilih.',
            'product_category_id.exists' => 'Kategori produk tidak valid.',
            'name.required' => 'Nama produk harus diisi.',
            'description.required' => 'Deskripsi produk harus diisi.',
            'price.required' => 'Harga produk harus diisi.',
            'price.numeric' => 'Harga produk harus berupa angka.',
            'price.min' => 'Harga produk tidak boleh negatif.',
            'stock.required' => 'Stok produk harus diisi.',
            'stock.integer' => 'Stok produk harus berupa angka bulat.',
            'stock.min' => 'Stok produk tidak boleh negatif.',
            'weight.required' => 'Berat produk harus diisi.',
            'weight.numeric' => 'Berat produk harus berupa angka.',
            'weight.min' => 'Berat produk tidak boleh negatif.',
        ];
    }
}
