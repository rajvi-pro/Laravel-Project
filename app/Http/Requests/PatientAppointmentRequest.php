<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return session('patient_logged_in');
    }

    public function rules(): array
    {
        return [
            'doctor_id' => 'required|exists:doctors,id|integer',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'reason' => 'nullable|string|max:500'
        ];
    }

    public function messages(): array
    {
        return [
            'doctor_id.required' => 'Please select a doctor.',
            'doctor_id.exists' => 'Selected doctor does not exist.',
            'appointment_date.required' => 'Appointment date is required.',
            'appointment_date.date' => 'Appointment date must be a valid date.',
            'appointment_date.after_or_equal' => 'Appointment date must be today or in the future.',
            'appointment_time.required' => 'Appointment time is required.',
            'appointment_time.date_format' => 'Appointment time must be in HH:MM format.',
            'reason.max' => 'Reason cannot exceed 500 characters.',
        ];
    }
}
