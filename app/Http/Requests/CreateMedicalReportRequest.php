<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Appointment;

class CreateMedicalReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return session('doctor_logged_in');
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id|integer',
            'diagnosis' => 'required|string|min:3|max:255',
            'symptoms' => 'required|string|min:3|max:2000',
            'treatment' => 'required|string|min:3|max:2000',
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
                $validator->errors()->add('patient_id', 'There is no patient found. You can only create medical reports for patients you have appointments with.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'patient_id.required' => 'Patient is required.',
            'patient_id.exists' => 'Selected patient does not exist.',
            'diagnosis.required' => 'Diagnosis is required.',
            'diagnosis.min' => 'Diagnosis must be at least 3 characters.',
            'diagnosis.max' => 'Diagnosis cannot exceed 255 characters.',
            'symptoms.required' => 'Symptoms description is required.',
            'symptoms.min' => 'Symptoms must be at least 3 characters.',
            'symptoms.max' => 'Symptoms cannot exceed 2000 characters.',
            'treatment.required' => 'Treatment plan is required.',
            'treatment.min' => 'Treatment plan must be at least 3 characters.',
            'treatment.max' => 'Treatment plan cannot exceed 2000 characters.',
            'notes.max' => 'Notes cannot exceed 2000 characters.',
        ];
    }
}
