<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Billing extends Model
{
    use SoftDeletes;

    protected $table = 'billings';

    protected $fillable = [
        'appointment_id',
        'patient_id',
        'staff_id',
        'description',
        'amount',
        'subtotal',
        'cgst',
        'sgst',
        'total_tax',
        'payment_status',
        'payment_method',
        'billing_date',
        'due_date',
        'notes',
        'billing_items'
    ];

    protected $dates = [
        'billing_date',
        'due_date',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the appointment associated with the billing.
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the patient associated with the billing.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the staff member associated with the billing.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the bill items associated with this billing.
     */
    public function billItems()
    {
        return $this->hasMany(BillItem::class);
    }

    /**
     * Get the total revenue for a given period
     */
    public static function getTotalRevenue($startDate = null, $endDate = null)
    {
        $query = self::where('payment_status', 'paid');

        if ($startDate) {
            $query->whereDate('billing_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('billing_date', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    /**
     * Get revenue trends over months
     */
    public static function getRevenueTrends($months = 12)
    {
        return self::selectRaw('DATE_FORMAT(billing_date, "%Y-%m") as month, SUM(amount) as revenue')
            ->where('payment_status', 'paid')
            ->whereDate('billing_date', '>=', now()->subMonths($months))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    /**
     * Get appointment status statistics
     */
    public static function getAppointmentStats()
    {
        return Appointment::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
    }


    /**
     * Get pending payments
     */
    public static function getPendingAmount()
    {
        return self::where('payment_status', 'pending')->sum('amount');
    }

    /**
     * Archive billing items (prescriptions and lab reports) as JSON
     */
    public function archiveBillingItems()
    {
        $patient = $this->patient;
        $items = [
            'prescriptions' => $patient->prescriptions()->get()->map(function($p) {
                return [
                    'type' => 'prescription',
                    'id' => $p->id,
                    'medicine_name' => $p->medicine_name,
                    'dosage' => $p->dosage,
                    'frequency' => $p->frequency,
                    'duration' => $p->duration,
                    'price' => $p->price ?? 0
                ];
            }),
            'lab_results' => $patient->labResults()
                ->where('status', 'completed')
                ->where('charge', '>', 0)
                ->get()
                ->map(function($lab) {
                    return [
                        'type' => 'lab_result',
                        'id' => $lab->id,
                        'test_name' => $lab->test_name,
                        'result' => $lab->result,
                        'charge' => $lab->charge ?? 0
                    ];
                })
        ];

        return json_encode($items);
    }

    /**
     * Calculate billing total with taxes
     */
    public static function calculateBillingTotal($patient_id, $cgst_rate = 9, $sgst_rate = 9)
    {
        $patient = Patient::find($patient_id);
        
        // Calculate subtotal from prescriptions
        $prescriptionTotal = $patient->prescriptions()->sum('price');
        
        // Calculate subtotal from lab results (completed with charge > 0)
        $labChargesTotal = $patient->labResults()
            ->where('status', 'completed')
            ->where('charge', '>', 0)
            ->sum('charge');

        // Total before tax
        $subtotal = $prescriptionTotal + $labChargesTotal;

        // Calculate taxes
        $cgst = ($subtotal * $cgst_rate) / 100;
        $sgst = ($subtotal * $sgst_rate) / 100;
        $totalTax = $cgst + $sgst;
        $total = $subtotal + $totalTax;

        return [
            'subtotal' => $subtotal,
            'cgst' => $cgst,
            'sgst' => $sgst,
            'total_tax' => $totalTax,
            'total' => $total,
            'prescription_total' => $prescriptionTotal,
            'lab_charges_total' => $labChargesTotal
        ];
    }
}
