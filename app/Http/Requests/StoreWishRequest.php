<?php
// =====================================================================
// FILE: app/Http/Requests/StoreWishRequest.php
// =====================================================================
namespace App\Http\Requests;
 
use Illuminate\Foundation\Http\FormRequest;
 
class StoreWishRequest extends FormRequest
{
    public function authorize(): bool { return true; }
 
    public function rules(): array
    {
        return [
            'sender_name' => 'required|string|max:100',
            'message'     => 'required|string|max:1000',
        ];
    }
 
    public function messages(): array
    {
        return [
            'sender_name.required' => 'Nama pengirim wajib diisi.',
            'message.required'     => 'Pesan ucapan wajib diisi.',
        ];
    }
}