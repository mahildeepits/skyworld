<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
//            'epin' => 'required',
            'sponsor' => 'required',
            'full_name' => 'required',
            'password' => 'required',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|integer|unique:users,mobile',
            'confirm_password' => 'required|same:password',
            'agent_category_id' => 'required|exists:agent_categories,id',
            'deposit_amount' => 'required|numeric|min:0',
            'receipt' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
        ];
    }

    public function messages()
    {
        return [
            'card_front.required' => 'Adhaar Front Image is required',
            'card_back.required' => 'Adhaar Back Image is required',
            'email.email' => 'Please enter valid email address',
            'adhaar_number.unique' => 'This Adhaar number is already registered',
            'mobile.integer' => 'Invalid Mobile Number',
            'mobile.unique' => 'This Mobile number is already registered',
            'email.unique' => 'This Email is already registered',
            'confirm_password.same' => 'Password does not match',
            'receipt.required' => 'Deposit screenshot/receipt image is required',
            'receipt.image' => 'The receipt must be an image (screenshot)',
            'receipt.mimes' => 'The receipt must be a jpeg, png, jpg, gif, svg or webp image',
            'receipt.max' => 'The receipt size must not exceed 4MB',
        ];
    }
}
