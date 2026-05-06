<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateStaffPatientHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return session('staff_logged_in');
    }

    public function rules(): array
    {
        return [
            'report_date' => 'required|date|before_or_equal:today',
            'diagnosis' => 'required|string|min:5|max:1000',
            'symptoms' => 'nullable|string|min:3|max:2000',
            'treatment' => 'nullable|string|min:3|max:2000',
            'notes' => 'nullable|string|max:2000'
        ];
    }

    public function messages(): array
    {
        return [
            'report_date.required' => 'Report date is required.',
            'report_date.date' => 'Report date must be a valid date.',
            'report_date.before_or_equal' => 'Report date cannot be in the future.',
            'diagnosis.required' => 'Diagnosis is required.',
            'diagnosis.min' => 'Diagnosis must be at least 5 characters.',
            'diagnosis.max' => 'Diagnosis cannot exceed 1000 characters.',
            'symptoms.min' => 'Symptoms must be at least 3 characters.',
            'symptoms.max' => 'Symptoms cannot exceed 2000 characters.',
            'treatment.min' => 'Treatment must be at least 3 characters.',
            'treatment.max' => 'Treatment cannot exceed 2000 characters.',
            'notes.max' => 'Notes cannot exceed 2000 characters.',
        ];
    }
}
