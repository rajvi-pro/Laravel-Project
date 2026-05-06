<?php

namespace App\Services;

use App\Models\Prescription;
use Illuminate\Database\Eloquent\Collection;

class PrescriptionService
{
    public function getPrescriptionsByPatient($patientId): Collection
    {
        return Prescription::where('patient_id', $patientId)
            ->with(['patient', 'doctor', 'medicines'])
            ->orderBy('prescribed_date', 'desc')
            ->get();
    }

    public function getPrescriptionsByDoctor($doctorId): Collection
    {
        return Prescription::where('doctor_id', $doctorId)
            ->with(['patient', 'doctor', 'medicines'])
            ->orderBy('prescribed_date', 'desc')
            ->get();
    }

    public function createPrescription(array $data): Prescription
    {
        return Prescription::create($data);
    }

    public function updatePrescription(Prescription $prescription, array $data): Prescription
    {
        $prescription->update($data);
        return $prescription;
    }

    public function getPrescriptionWithMedicines($prescriptionId)
    {
        return Prescription::with(['medicines', 'patient', 'doctor'])
            ->findOrFail($prescriptionId);
    }
}
