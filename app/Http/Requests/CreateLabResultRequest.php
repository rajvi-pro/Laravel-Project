<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Appointment;

class CreateLabResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return session('doctor_logged_in');
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id|integer',
            'test_name' => 'required|string|min:3|max:255',
            'result' => 'required|string|min:3|max:2000',
            'normal_range' => 'nullable|string|max:255',
            'status' => 'required|in:pending,completed',
            'notes' => 'nullable|string|max:2000',
            'appointment_id' => 'nullable|exists:appointments,id',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $patientId = $this->input('patient_id');
            $doctorId = session('doctor_id');
            
            $hasAppointment = Appointment::where('patient_id', $patientId)
                ->where('doctor_id', $doctorId)
                ->exists();
            
            if (!$hasAppointment) {
                $validator->errors()->add('patient_id', 'There is no patient found. You can only create lab results for patients you have appointments with.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'patient_id.required' => 'Patient is required.',
            'patient_id.exists' => 'Selected patient does not exist.',
            'test_name.required' => 'Test name is required.',
            'test_name.min' => 'Test name must be at least 3 characters.',
            'test_name.max' => 'Test name cannot exceed 255 characters.',
            'result.required' => 'Test result is required.',
            'result.min' => 'Test result must be at least 3 characters.',
            'result.max' => 'Test result cannot exceed 2000 characters.',
            'normal_range.max' => 'Normal range cannot exceed 255 characters.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be either pending or completed.',
            'notes.max' => 'Notes cannot exceed 2000 characters.',
        ];
    }
}
