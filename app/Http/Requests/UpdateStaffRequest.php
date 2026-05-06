<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return session('admin_logged_in');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:staff,email,' . $this->route('staff'),
            'phone' => 'required|string|regex:/^[0-9]{10}$/|size:10',
            'role' => 'required|string|min:3|max:255',
            'department' => 'required|string|min:3|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Staff name is required.',
            'name.min' => 'Staff name must be at least 3 characters.',
            'name.max' => 'Staff name cannot exceed 255 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'This email is already in use.',
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Phone number must contain exactly 10 digits.',
            'phone.size' => 'Phone number must be exactly 10 digits.',
            'role.required' => 'Role is required.',
            'role.min' => 'Role must be at least 3 characters.',
            'role.max' => 'Role cannot exceed 255 characters.',
            'department.required' => 'Department is required.',
            'department.min' => 'Department must be at least 3 characters.',
            'department.max' => 'Department cannot exceed 255 characters.',
        ];
    }
}
