<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return session('staff_logged_in');
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id|integer',
            'doctor_id' => 'required|exists:doctors,id|integer',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'reason' => 'nullable|string|max:500'
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.required' => 'Patient is required.',
            'patient_id.exists' => 'Selected patient does not exist.',
            'doctor_id.required' => 'Doctor is required.',
            'doctor_id.exists' => 'Selected doctor does not exist.',
            'appointment_date.required' => 'Appointment date is required.',
            'appointment_date.date' => 'Appointment date must be a valid date.',
            'appointment_time.required' => 'Appointment time is required.',
            'appointment_time.date_format' => 'Appointment time must be in HH:MM format.',
            'reason.max' => 'Reason cannot exceed 500 characters.',
        ];
    }
}
