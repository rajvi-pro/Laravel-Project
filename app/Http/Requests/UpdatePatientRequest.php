<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidBirthDate;

class UpdatePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return session('patient_logged_in') || session('admin_logged_in');
    }

    public function rules(): array
    {
        $patientId = $this->route('patient') ?? session('patient_id');
        
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:patients,email,' . $patientId,
            'phone' => 'required|string|regex:/^[0-9]{10}$/|size:10',
            'date_of_birth' => ['required', 'date', new ValidBirthDate()],
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'required|string|min:5|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'name.min' => 'Name must be at least 3 characters.',
            'name.max' => 'Name cannot exceed 255 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'This email is already in use.',
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Phone number must contain exactly 10 digits.',
            'phone.size' => 'Phone number must be exactly 10 digits.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.date' => 'Date of birth must be a valid date format.',
            'gender.required' => 'Gender is required.',
            'gender.in' => 'Gender must be Male, Female, or Other.',
            'address.required' => 'Address is required.',
            'address.min' => 'Address must be at least 5 characters.',
            'address.max' => 'Address cannot exceed 500 characters.',
            'city.max' => 'City cannot exceed 100 characters.',
            'state.max' => 'State cannot exceed 100 characters.',
        ];
    }
}
