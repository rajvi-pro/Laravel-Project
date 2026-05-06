<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|date_format:H:i',
            'reason' => 'required|string|max:500',
            'status' => 'required|in:scheduled,completed,cancelled',
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
            'appointment_date.after' => 'Appointment date must be in the future.',
            'appointment_time.required' => 'Appointment time is required.',
            'reason.required' => 'Reason for visit is required.',
            'status.required' => 'Status is required.',
        ];
    }
}
