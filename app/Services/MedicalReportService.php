<?php

namespace App\Services;

use App\Models\MedicalReport;
use Illuminate\Database\Eloquent\Collection;

class MedicalReportService
{
    public function getReportsByPatient($patientId): Collection
    {
        return MedicalReport::where('patient_id', $patientId)
            ->with(['patient', 'doctor'])
            ->orderBy('report_date', 'desc')
            ->get();
    }

    public function getReportsByDoctor($doctorId): Collection
    {
        return MedicalReport::where('doctor_id', $doctorId)
            ->with(['patient', 'doctor'])
            ->orderBy('report_date', 'desc')
            ->get();
    }

    public function createReport(array $data): MedicalReport
    {
        return MedicalReport::create($data);
    }

    public function updateReport(MedicalReport $report, array $data): MedicalReport
    {
        $report->update($data);
        return $report;
    }

    public function getReportWithDetails($reportId)
    {
        return MedicalReport::with(['patient', 'doctor'])
            ->findOrFail($reportId);
    }
}
