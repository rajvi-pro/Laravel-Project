<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Billing;

class StoreBillingFromAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'staff';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'appointment_id' => [
                'required',
                'integer',
                'exists:appointments,id',
                function ($attribute, $value, $fail) {
                    // Check if billing already exists for this appointment
                    if (Billing::where('appointment_id', $value)->exists()) {
                        $fail('A billing record already exists for this appointment. Duplicate billings are not allowed.');
                    }
                },
            ],
            'lab_items' => 'nullable|array',
            'lab_items.*' => 'array',
            'lab_items.*.id' => [
                'required',
                'integer',
                'exists:lab_results,id',
                function ($attribute, $value, $fail) {
                    // Validate lab result exists and is completed
                    $labResult = \App\Models\LabResult::find($value);
                    if (!$labResult || $labResult->status !== 'completed') {
                        $fail('Invalid or incomplete lab result selected.');
                    }
                },
            ],
            'lab_items.*.charge' => 'required|numeric|min:0|max:999999.99',
            'extra_charges' => 'nullable|numeric|min:0|max:999999.99',
            'extra_charges_description' => 'nullable|string|max:255',
            'payment_status' => 'required|in:pending,paid',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'appointment_id.required' => 'Appointment selection is required.',
            'appointment_id.exists' => 'The selected appointment does not exist.',
            'appointment_id.unique' => 'A billing record already exists for this appointment.',
            'lab_items.*.id.required' => 'Lab test selection is invalid.',
            'lab_items.*.id.exists' => 'One or more selected lab tests do not exist.',
            'lab_items.*.charge.numeric' => 'Lab test charge must be a valid number.',
            'lab_items.*.charge.min' => 'Lab test charge cannot be negative.',
            'extra_charges.numeric' => 'Extra charges must be a valid number.',
            'extra_charges.min' => 'Extra charges cannot be negative.',
            'payment_status.in' => 'Invalid payment status selected.',
            'notes.max' => 'Notes cannot exceed 1000 characters.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure appointment_id is always an integer
        if ($this->has('appointment_id')) {
            $this->merge([
                'appointment_id' => (int) $this->appointment_id,
            ]);
        }

        // Ensure numeric fields are properly typed
        if ($this->has('extra_charges')) {
            $this->merge([
                'extra_charges' => $this->extra_charges ?: 0,
            ]);
        }
    }
}
