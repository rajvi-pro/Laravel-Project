<?php

namespace App\Services;

use App\Models\Billing;
use App\Models\BillItem;
use App\Models\Appointment;
use App\Models\LabResult;
use Illuminate\Support\Facades\DB;

class BillingService
{
    /**
     * Tax rates
     */
    const CGST_RATE = 0.09; // 9%
    const SGST_RATE = 0.09; // 9%

    /**
     * Create a billing record from appointment with line items
     *
     * @param int $appointmentId
     * @param array $labItems
     * @param float $extraCharges
     * @param string $status
     * @param string|null $notes
     * @return Billing
     * @throws \Exception
     */
    public static function createBillingFromAppointment(
        int $appointmentId,
        array $labItems = [],
        float $extraCharges = 0,
        string $status = 'pending',
        string $notes = null
    ): Billing {
        return DB::transaction(function () use (
            $appointmentId,
            $labItems,
            $extraCharges,
            $status,
            $notes
        ) {
            // Get appointment
            $appointment = Appointment::with(['patient', 'doctor'])->findOrFail($appointmentId);

            // Check for existing billing
            if (Billing::where('appointment_id', $appointmentId)->exists()) {
                throw new \Exception('A billing record already exists for this appointment.');
            }

            // Prepare bill items
            $billItems = [];
            $subtotal = 0;

            // Add consultation fee
            $consultationFee = (float) ($appointment->doctor->consultation_fee ?? 0);
            if ($consultationFee > 0) {
                $billItems[] = [
                    'type' => 'consultation',
                    'description' => 'Consultation Fee - ' . $appointment->doctor->name,
                    'amount' => $consultationFee,
                    'quantity' => 1,
                    'total' => $consultationFee
                ];
                $subtotal += $consultationFee;
            }

            // Process lab items
            foreach ($labItems as $labItem) {
                $lab = LabResult::findOrFail($labItem['id']);
                
                // Verify lab result belongs to correct patient and is completed
                if ($lab->patient_id !== $appointment->patient_id || $lab->status !== 'completed') {
                    throw new \Exception('Invalid lab result: ' . $lab->test_name);
                }

                $charge = (float) $labItem['charge'];
                if ($charge > 0) {
                    $billItems[] = [
                        'type' => 'lab_test',
                        'description' => 'Lab Test - ' . $lab->test_name,
                        'amount' => $charge,
                        'quantity' => 1,
                        'total' => $charge
                    ];
                    $subtotal += $charge;
                }
            }

            // Add extra charges
            if ($extraCharges > 0) {
                $billItems[] = [
                    'type' => 'extra_charge',
                    'description' => 'Additional Charges',
                    'amount' => $extraCharges,
                    'quantity' => 1,
                    'total' => $extraCharges
                ];
                $subtotal += $extraCharges;
            }

            // Calculate taxes
            $taxes = self::calculateTaxes($subtotal);

            // Create billing record
            $billing = new Billing();
            $billing->appointment_id = $appointment->id;
            $billing->patient_id = $appointment->patient_id;
            $billing->staff_id = auth()->id();
            $billing->billing_date = now()->toDateString();
            $billing->payment_status = $status;
            $billing->notes = $notes;
            $billing->description = 'Billing for Appointment ID: ' . $appointment->id;

            // Set amounts
            $billing->subtotal = $subtotal;
            $billing->cgst = $taxes['cgst'];
            $billing->sgst = $taxes['sgst'];
            $billing->total_tax = $taxes['total_tax'];
            $billing->amount = $taxes['total'];

            $billing->save();

            // Create bill items
            foreach ($billItems as $item) {
                BillItem::create([
                    'billing_id' => $billing->id,
                    'type' => $item['type'],
                    'description' => $item['description'],
                    'amount' => $item['amount'],
                    'quantity' => $item['quantity'],
                    'total' => $item['total']
                ]);
            }

            return $billing;
        });
    }

    /**
     * Calculate taxes for a given subtotal
     *
     * @param float $subtotal
     * @return array ['cgst' => float, 'sgst' => float, 'total_tax' => float, 'total' => float]
     */
    public static function calculateTaxes(float $subtotal): array
    {
        $cgst = $subtotal * self::CGST_RATE;
        $sgst = $subtotal * self::SGST_RATE;
        $totalTax = $cgst + $sgst;
        $total = $subtotal + $totalTax;

        return [
            'cgst' => round($cgst, 2),
            'sgst' => round($sgst, 2),
            'total_tax' => round($totalTax, 2),
            'total' => round($total, 2),
        ];
    }

    /**
     * Validate appointment is eligible for billing
     *
     * @param int $appointmentId
     * @return array ['valid' => bool, 'message' => string|null]
     */
    public static function validateAppointmentForBilling(int $appointmentId): array
    {
        $appointment = Appointment::find($appointmentId);

        if (!$appointment) {
            return [
                'valid' => false,
                'message' => 'Appointment not found.'
            ];
        }

        if ($appointment->status !== 'completed') {
            return [
                'valid' => false,
                'message' => 'Appointment must be completed to create a billing.'
            ];
        }

        if (Billing::where('appointment_id', $appointmentId)->exists()) {
            return [
                'valid' => false,
                'message' => 'A billing record already exists for this appointment.'
            ];
        }

        return [
            'valid' => true,
            'message' => null
        ];
    }

    /**
     * Get billing summary with itemized details
     *
     * @param Billing $billing
     * @return array
     */
    public static function getBillingSummary(Billing $billing): array
    {
        $billItems = $billing->billItems()->get();

        return [
            'billing_id' => $billing->id,
            'appointment_id' => $billing->appointment_id,
            'patient' => [
                'id' => $billing->patient->id,
                'name' => $billing->patient->name ?? $billing->patient->full_name,
                'email' => $billing->patient->email,
            ],
            'items' => $billItems->map(function ($item) {
                return [
                    'type' => $item->type,
                    'description' => $item->description,
                    'amount' => (float) $item->amount,
                    'quantity' => $item->quantity,
                    'total' => (float) $item->total,
                ];
            })->all(),
            'subtotal' => (float) $billing->subtotal,
            'cgst' => (float) $billing->cgst,
            'sgst' => (float) $billing->sgst,
            'total_tax' => (float) $billing->total_tax,
            'total' => (float) $billing->amount,
            'payment_status' => $billing->payment_status,
            'billing_date' => $billing->billing_date?->format('Y-m-d'),
        ];
    }

    /**
     * Calculate revenue stats
     *
     * @return array
     */
    public static function getRevenueStats(): array
    {
        $paidBillings = Billing::where('payment_status', 'paid')->get();
        $pendingBillings = Billing::whereIn('payment_status', ['pending', 'overdue'])->get();

        return [
            'total_paid' => $paidBillings->sum('amount'),
            'total_pending' => $pendingBillings->sum('amount'),
            'total_billings' => Billing::count(),
            'paid_count' => $paidBillings->count(),
            'pending_count' => $pendingBillings->count(),
        ];
    }
}
