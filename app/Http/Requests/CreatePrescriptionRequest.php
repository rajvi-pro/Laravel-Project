<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Appointment;

class CreatePrescriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return session('doctor_logged_in');
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'medicine_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'frequency' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'instructions' => 'nullable|string|max:1000',
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
                $validator->errors()->add('patient_id', 'You can only add prescriptions for patients you have appointments with.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'patient_id.required' => 'Patient is required.',
            'patient_id.exists' => 'Selected patient does not exist.',
            'medicine_name.required' => 'Medicine name is required.',
            'medicine_name.max' => 'Medicine name cannot exceed 255 characters.',
            'dosage.required' => 'Dosage is required.',
            'dosage.max' => 'Dosage cannot exceed 255 characters.',
            'frequency.required' => 'Frequency is required.',
            'frequency.max' => 'Frequency cannot exceed 255 characters.',
            'duration.required' => 'Duration is required.',
            'duration.max' => 'Duration cannot exceed 255 characters.',
        ];
    }
}
