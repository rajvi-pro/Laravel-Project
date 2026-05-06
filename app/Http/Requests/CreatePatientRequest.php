<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidBirthDate;

class CreatePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|regex:/^[0-9]{10}$/|size:10',
            'date_of_birth' => ['required', 'date', new ValidBirthDate()],
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'required|string|max:500',
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.unique' => 'This email is already registered.',
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Phone number must contain exactly 10 digits.',
            'phone.size' => 'Phone number must be exactly 10 digits.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.date' => 'Date of birth must be a valid date format.',
            'gender.required' => 'Gender is required.',
            'address.required' => 'Address is required.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Passwords do not match.',
        ];
    }
}
