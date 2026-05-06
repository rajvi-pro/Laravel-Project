<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return session('admin_logged_in');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:doctors,email,' . $this->route('doctor'),
            'phone' => 'required|string|regex:/^[0-9]{10}$/|size:10',
            'specialization' => 'required|string|min:3|max:255',
            'qualification' => 'required|string|min:3|max:255',
            'experience_years' => 'required|integer|min:0|max:70',
            'consultation_fee' => 'nullable|numeric|min:0|max:999999'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Doctor name is required.',
            'name.min' => 'Doctor name must be at least 3 characters.',
            'name.max' => 'Doctor name cannot exceed 255 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'This email is already in use.',
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Phone number must contain exactly 10 digits.',
            'phone.size' => 'Phone number must be exactly 10 digits.',
            'specialization.required' => 'Specialization is required.',
            'specialization.min' => 'Specialization must be at least 3 characters.',
            'specialization.max' => 'Specialization cannot exceed 255 characters.',
            'qualification.required' => 'Qualification is required.',
            'qualification.min' => 'Qualification must be at least 3 characters.',
            'qualification.max' => 'Qualification cannot exceed 255 characters.',
            'experience_years.required' => 'Experience years is required.',
            'experience_years.integer' => 'Experience years must be a number.',
            'experience_years.min' => 'Experience years cannot be negative.',
            'experience_years.max' => 'Please enter a valid experience year.',
            'consultation_fee.numeric' => 'Consultation fee must be a number.',
            'consultation_fee.min' => 'Consultation fee cannot be negative.',
        ];
    }
}
