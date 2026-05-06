<?php

namespace App\Services;

use App\Models\LabResult;
use Illuminate\Database\Eloquent\Collection;

class LabResultService
{
    public function getResultsByPatient($patientId): Collection
    {
        return LabResult::where('patient_id', $patientId)
            ->with(['patient', 'doctor'])
            ->orderBy('test_date', 'desc')
            ->get();
    }

    public function getResultsByDoctor($doctorId): Collection
    {
        return LabResult::where('doctor_id', $doctorId)
            ->with(['patient', 'doctor'])
            ->orderBy('test_date', 'desc')
            ->get();
    }

    public function createResult(array $data): LabResult
    {
        return LabResult::create($data);
    }

    public function updateResult(LabResult $result, array $data): LabResult
    {
        $result->update($data);
        return $result;
    }

    public function getResultWithDetails($resultId)
    {
        return LabResult::with(['patient', 'doctor'])
            ->findOrFail($resultId);
    }
}
