@extends('layouts.staff-layout')

@section('page-title', 'Billing')
@section('title', 'Billing Management - Staff Portal')

@section('content')
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div style="display: flex; gap: 10px; margin-bottom: 20px;">
        <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <a href="{{ route('staff.billing.create') }}" class="btn-book" title="Create billing from appointment">
            <i class="fas fa-plus"></i> New Billing (Appointment)
        </a>
        <button class="btn-book" data-bs-toggle="modal" data-bs-target="#newBillingModal" title="Create billing from patient">
            <i class="fas fa-plus"></i> New Billing (Patient)
        </button>
    </div>

    <!-- Information Alert -->
    <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
        <strong><i class="fas fa-info-circle"></i> Billing System Info:</strong>
        <ul style="margin: 10px 0 0 20px; padding: 0;">
            <li>Only shows <strong>unpaid billings</strong> (Pending & Overdue)</li>
            <li>Only displays patients with <strong>completed appointments</strong></li>
            <li>Patients with <strong>paid billings</strong> are automatically excluded</li>
            <li>Create new billings only for patients with completed medical services</li>
        </ul>
    </div>

    <!-- Billing Summary Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #0d47a1;">
            <div style="font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 10px;">Total Billings</div>
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a;">{{ $billings->count() ?? 0 }}</div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #26a69a;">
            <div style="font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 10px;">Paid Amount</div>
            <div style="font-size: 32px; font-weight: 700; color: #26a69a;">₹{{ number_format($paidAmount ?? 0, 2) }}</div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #ff9800;">
            <div style="font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 10px;">Pending Amount</div>
            <div style="font-size: 32px; font-weight: 700; color: #ff9800;">₹{{ number_format($pendingAmount ?? 0, 2) }}</div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #1565c0;">
            <div style="font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 10px;">Total Revenue</div>
            <div style="font-size: 32px; font-weight: 700; color: #1565c0;">₹{{ number_format($totalBillingAmount ?? 0, 2) }}</div>
        </div>
    </div>

    <!-- Billings Table -->
    <div class="table-container">
        <div class="table-header"><i class="fas fa-receipt"></i> Billing Records</div>

        @if($billings && $billings->count() > 0)
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-user"></i> Patient</th>
                            <th><i class="fas fa-calendar"></i> Billing Date</th>
                            <th><i class="fas fa-dollar-sign"></i> Amount</th>
                            <th><i class="fas fa-credit-card"></i> Payment Status</th>
                            <th><i class="fas fa-file"></i> Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($billings as $billing)
                            <tr>
                                <td>
                                    <strong>{{ $billing->patient->name ?? $billing->patient->full_name ?? 'N/A' }}</strong>
                                    <br>
                                    <small style="color: #999;">{{ $billing->patient->id ?? $billing->patient->medical_id ?? 'N/A' }}</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($billing->billing_date ?? now())->format('M d, Y') }}</td>
                                <td>
                                    <strong style="color: #1a1a1a; font-weight: 700;">₹{{ number_format($billing->amount ?? 0, 2) }}</strong>
                                    <br>
                                    <small style="color: #999;">Subtotal: ₹{{ number_format($billing->subtotal ?? 0, 2) }}</small>
                                </td>
                                <td>
                                    <span class="badge" style="background-color:
                                        @if($billing->payment_status == 'paid') #26a69a
                                        @elseif($billing->payment_status == 'pending') #ff9800
                                        @elseif($billing->payment_status == 'overdue') #e53935
                                        @else #0d47a1
                                        @endif
                                    ">
                                        {{ ucfirst($billing->payment_status ?? 'Pending') }}
                                    </span>
                                </td>
                                <td>{{ substr($billing->description ?? '-', 0, 30) }}{{ strlen($billing->description ?? '') > 30 ? '...' : '' }}</td>
                                <td style="white-space: nowrap;">
                                    <a href="{{ route('staff.billing.detail', $billing->id) }}" class="btn btn-sm btn-outline-primary" title="View Invoice">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editBillingModal{{ $billing->id }}" title="Edit Billing">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <p style="margin: 0;">No billing records found</p>
            </div>
        @endif
    </div>

    <!-- New Billing Modal -->
    <div class="modal fade" id="newBillingModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Billing Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('staff.billing.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info" role="alert">
                            <strong>📋 Billing Info:</strong> Only patients with completed appointments and unpaid status will appear. Patients with paid billings are automatically excluded.
                        </div>
                        <div class="alert alert-warning" role="alert">
                            <strong>💰 Automatic Calculation:</strong> The bill will automatically include all prescriptions with prices, completed lab test charges, and doctor consultation fees. Taxes (CGST 9% + SGST 9%) are calculated automatically.
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="patient_id" class="form-label"><strong>Patient with Completed Appointment</strong></label>
                                <select class="form-control" name="patient_id" required id="patientSelect">
                                    <option value="">-- Select Patient --</option>
                                    @forelse($patientsWithCompletedAppointments ?? [] as $patient)
                                        <option value="{{ $patient->id }}">
                                            {{ $patient->name ?? $patient->full_name }} 
                                            (ID: {{ $patient->id ?? $patient->medical_id }})
                                        </option>
                                    @empty
                                        <option value="" disabled>No eligible patients found</option>
                                    @endforelse
                                </select>
                                <small class="text-muted">Only shows patients with completed appointments</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="billing_date" class="form-label"><strong>Billing Date</strong></label>
                                <input type="date" class="form-control" name="billing_date" value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cgst_rate" class="form-label">CGST Rate (%)</label>
                                <input type="number" step="0.01" class="form-control" name="cgst_rate" placeholder="9.00" value="9">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sgst_rate" class="form-label">SGST Rate (%)</label>
                                <input type="number" step="0.01" class="form-control" name="sgst_rate" placeholder="9.00" value="9">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="payment_status" class="form-label"><strong>Initial Payment Status</strong></label>
                            <select class="form-control" name="payment_status" required>
                                <option value="pending" selected>Pending</option>
                                <option value="overdue">Overdue</option>
                            </select>
                            <small class="text-muted">Paid billings are automatically excluded from the list</small>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="2" placeholder="Additional notes (optional)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Billing</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Billing Modals (dynamic for each billing) -->
    @foreach($billings ?? [] as $billing)
        <div class="modal fade" id="editBillingModal{{ $billing->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">Edit Billing Record - {{ $billing->patient->name ?? 'N/A' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('staff.billing.update', $billing->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <strong>Note:</strong> You can modify the billing amount, date, and payment status. The system will automatically recalculate taxes.
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><strong>Patient Name</strong></label>
                                    <input type="text" class="form-control" value="{{ $billing->patient->name ?? $billing->patient->full_name ?? 'N/A' }}" readonly style="background-color: #f5f5f5;">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><strong>Patient ID</strong></label>
                                    <input type="text" class="form-control" value="{{ $billing->patient->id ?? $billing->patient->medical_id ?? 'N/A' }}" readonly style="background-color: #f5f5f5;">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><strong>Subtotal Amount (₹)</strong></label>
                                    <input type="number" step="0.01" class="form-control subtotal-amount" name="subtotal" value="{{ $billing->subtotal ?? 0 }}" required>
                                    <small class="text-muted">Sum of prescriptions, lab charges, and doctor fees</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><strong>Total Amount (₹)</strong></label>
                                    <input type="number" step="0.01" class="form-control total-amount" name="amount" value="{{ $billing->amount ?? 0 }}" readonly style="background-color: #f0f0f0; font-weight: bold;">
                                    <small class="text-muted">Includes CGST and SGST</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label"><strong>CGST (₹)</strong></label>
                                    <input type="number" step="0.01" class="form-control cgst-amount" name="cgst" value="{{ $billing->cgst ?? 0 }}" readonly style="background-color: #f0f0f0;">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label"><strong>SGST (₹)</strong></label>
                                    <input type="number" step="0.01" class="form-control sgst-amount" name="sgst" value="{{ $billing->sgst ?? 0 }}" readonly style="background-color: #f0f0f0;">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label"><strong>Total Tax (₹)</strong></label>
                                    <input type="number" step="0.01" class="form-control total-tax-amount" name="total_tax" value="{{ $billing->total_tax ?? 0 }}" readonly style="background-color: #f0f0f0;">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><strong>Billing Date</strong></label>
                                    <input type="date" class="form-control" name="billing_date" value="{{ isset($billing->billing_date) ? \Carbon\Carbon::parse($billing->billing_date)->format('Y-m-d') : now()->format('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><strong>Payment Status</strong></label>
                                    <select class="form-control" name="payment_status" required>
                                        <option value="pending" @if(($billing->payment_status ?? '') == 'pending') selected @endif>Pending</option>
                                        <option value="paid" @if(($billing->payment_status ?? '') == 'paid') selected @endif>Paid</option>
                                        <option value="overdue" @if(($billing->payment_status ?? '') == 'overdue') selected @endif>Overdue</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><strong>Description</strong></label>
                                <textarea class="form-control" name="description" rows="2">{{ $billing->description ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        // Auto-calculate taxes when subtotal changes in edit modal
        document.querySelectorAll('.subtotal-amount').forEach(field => {
            field.addEventListener('change', function() {
                const modalContent = this.closest('.modal-content');
                if (!modalContent) return;
                
                const subtotal = parseFloat(this.value) || 0;
                const cgst = subtotal * 0.09; // 9% CGST
                const sgst = subtotal * 0.09; // 9% SGST
                const totalTax = cgst + sgst;
                const total = subtotal + totalTax;

                // Update display fields
                const cgstField = modalContent.querySelector('input[name="cgst"]');
                const sgstField = modalContent.querySelector('input[name="sgst"]');
                const totalTaxField = modalContent.querySelector('input[name="total_tax"]');
                const totalAmountField = modalContent.querySelector('input[name="amount"]');

                if (cgstField) cgstField.value = cgst.toFixed(2);
                if (sgstField) sgstField.value = sgst.toFixed(2);
                if (totalTaxField) totalTaxField.value = totalTax.toFixed(2);
                if (totalAmountField) totalAmountField.value = total.toFixed(2);
            });
        });
    </script>

@endsection
