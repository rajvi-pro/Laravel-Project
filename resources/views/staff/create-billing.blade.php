@extends('layouts.staff-layout')

@section('page-title', 'Billing')
@section('title', 'Create Billing from Appointment - Staff Portal')

@section('content')
    <style>
        .billing-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 30px;
            margin-bottom: 30px;
        }

        .form-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: #0d47a1;
            font-size: 20px;
        }

        .info-card {
            background: #f8f9fa;
            border-left: 4px solid #0d47a1;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .info-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 15px;
        }

        .info-field {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 12px;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 500;
            color: #333;
        }

        .item-list {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .item-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .item-card:hover {
            border-color: #0d47a1;
            box-shadow: 0 2px 8px rgba(13, 71, 161, 0.1);
        }

        .item-card.selected {
            border-color: #0d47a1;
            background: #f0f5ff;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 5px;
        }

        .item-details {
            font-size: 13px;
            color: #999;
        }

        .item-price {
            font-size: 18px;
            font-weight: 700;
            color: #0d47a1;
            margin-right: 15px;
            min-width: 80px;
            text-align: right;
        }

        .item-checkbox {
            width: 20px;
            height: 20px;
            margin-left: 15px;
            cursor: pointer;
        }

        .total-section {
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
            color: white;
            padding: 25px;
            border-radius: 8px;
            margin-top: 30px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .total-row strong {
            font-weight: 600;
        }

        .total-divider {
            height: 1px;
            background: rgba(255,255,255,0.3);
            margin: 15px 0;
        }

        .final-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 22px;
            font-weight: 700;
        }

        .final-total-label {
            font-size: 14px;
            font-weight: 600;
        }

        .final-total-amount {
            font-size: 28px;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            justify-content: flex-end;
        }

        .error-alert {
            background: #ffebee;
            border-left: 4px solid #e53935;
            color: #c62828;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: none;
        }

        .success-alert {
            background: #e8f5e9;
            border-left: 4px solid #26a69a;
            color: #1b5e20;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: none;
        }

        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(0,0,0,0.1);
            border-top-color: #0d47a1;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .badge-consultation {
            background: #e3f2fd;
            color: #0d47a1;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-lab {
            background: #f3e5f5;
            color: #6a1b9a;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .extra-charges-section {
            background: #fff3e0;
            border: 1px dashed #ff9800;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .appointment-selector {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .appointment-selector:focus {
            border-color: #0d47a1;
            outline: none;
            box-shadow: 0 0 0 3px rgba(13, 71, 161, 0.1);
        }

        .no-items-message {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .info-row {
                grid-template-columns: 1fr;
            }

            .button-group {
                flex-direction: column-reverse;
            }

            .billing-container {
                padding: 20px;
            }
        }
    </style>

    <div style="margin-bottom: 20px;">
        <a href="{{ route('staff.billings') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Billings
        </a>
    </div>

    <div class="billing-container">
        <!-- Error Alert -->
        <div class="error-alert" id="errorAlert">
            <strong><i class="fas fa-exclamation-circle"></i> Error:</strong>
            <span id="errorMessage"></span>
        </div>

        <!-- Success Alert -->
        <div class="success-alert" id="successAlert">
            <strong><i class="fas fa-check-circle"></i> Success:</strong>
            <span id="successMessage"></span>
        </div>

        <form id="billingForm">
            @csrf

            <!-- Step 1: Appointment Selection -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-calendar-check"></i> Step 1: Select Appointment
                </div>

                <div class="mb-3">
                    <label for="appointmentSelect" class="form-label"><strong>Choose Appointment</strong></label>
                    <select id="appointmentSelect" class="appointment-selector" required>
                        <option value="">-- Select an Appointment --</option>
                        @foreach($appointments ?? [] as $apt)
                            <option value="{{ $apt->id }}" data-patient="{{ $apt->patient->name ?? $apt->patient->full_name }}" data-doctor="{{ $apt->doctor->name }}">
                                Patient: {{ $apt->patient->name ?? $apt->patient->full_name }} | Doctor: {{ $apt->doctor->name }} | {{ \Carbon\Carbon::parse($apt->appointment_date)->format('M d, Y H:i') }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Only shows completed appointments without existing billings</small>
                </div>
            </div>

            <!-- Step 2: Patient & Doctor Info (Hidden initially) -->
            <div class="form-section" id="informationSection" style="display: none;">
                <div class="section-title">
                    <i class="fas fa-user"></i> Step 2: Patient & Doctor Information
                </div>

                <div class="info-card">
                    <div class="info-row">
                        <div class="info-field">
                            <span class="info-label">Patient Name</span>
                            <span class="info-value" id="patientName">-</span>
                        </div>
                        <div class="info-field">
                            <span class="info-label">Patient ID</span>
                            <span class="info-value" id="patientId">-</span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-field">
                            <span class="info-label">Patient Email</span>
                            <span class="info-value" id="patientEmail">-</span>
                        </div>
                        <div class="info-field">
                            <span class="info-label">Patient Phone</span>
                            <span class="info-value" id="patientPhone">-</span>
                        </div>
                    </div>
                </div>

                <div class="info-card" style="margin-top: 15px;">
                    <div class="info-row">
                        <div class="info-field">
                            <span class="info-label">Doctor Name</span>
                            <span class="info-value" id="doctorName">-</span>
                        </div>
                        <div class="info-field">
                            <span class="info-label">Specialization</span>
                            <span class="info-value" id="doctorSpecialization">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Billing Items Selection -->
            <div class="form-section" id="billingItemsSection" style="display: none;">
                <div class="section-title">
                    <i class="fas fa-receipt"></i> Step 3: Billing Items
                </div>

                <!-- Consultation Fee -->
                <div style="margin-bottom: 25px;">
                    <h6 style="font-weight: 600; color: #1a1a1a; margin-bottom: 15px;">
                        <i class="fas fa-stethoscope" style="color: #0d47a1; margin-right: 8px;"></i> Consultation Fee
                    </h6>
                    <div class="item-card" id="consultationCard">
                        <div class="item-info">
                            <div class="item-name">Doctor Consultation</div>
                            <div class="item-details">
                                Professional consultation fee
                                <span class="badge-consultation" style="margin-left: 10px;">CONSULTATION</span>
                            </div>
                        </div>
                        <div class="item-price" id="consultationPrice">₹0.00</div>
                    </div>
                    <input type="hidden" id="consultationFee" value="0">
                </div>

                <!-- Lab Reports -->
                <div style="margin-bottom: 25px;">
                    <h6 style="font-weight: 600; color: #1a1a1a; margin-bottom: 15px;">
                        <i class="fas fa-flask" style="color: #6a1b9a; margin-right: 8px;"></i> Lab Reports & Tests
                    </h6>
                    <div id="labItemsContainer">
                        <div class="no-items-message">
                            <i class="fas fa-beaker"></i> No lab reports available for this patient
                        </div>
                    </div>
                </div>

                <!-- Prescriptions (Optional) -->
                <div style="margin-bottom: 25px;">
                    <h6 style="font-weight: 600; color: #1a1a1a; margin-bottom: 15px;">
                        <i class="fas fa-pills" style="color: #1976d2; margin-right: 8px;"></i> Prescriptions (Optional)
                    </h6>
                    <div id="prescriptionsContainer">
                        <div class="no-items-message">
                            <i class="fas fa-prescription-bottle"></i> No prescriptions available
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4: Extra Charges -->
            <div class="form-section" id="extraChargesSection" style="display: none;">
                <div class="section-title">
                    <i class="fas fa-plus-circle"></i> Step 4: Extra Charges (Optional)
                </div>

                <div class="extra-charges-section">
                    <div class="mb-3">
                        <label for="extraCharges" class="form-label"><strong>Additional Charges Amount (₹)</strong></label>
                        <input type="number" class="form-control" id="extraCharges" name="extra_charges" min="0" step="0.01" value="0" placeholder="0.00">
                        <small class="text-muted">e.g., Administration fees, misc charges</small>
                    </div>

                    <div class="mb-0">
                        <label for="extraChargesDesc" class="form-label"><strong>Description</strong></label>
                        <input type="text" class="form-control" id="extraChargesDesc" name="extra_charges_description" placeholder="e.g., Administration Fee, Miscellaneous">
                    </div>
                </div>
            </div>

            <!-- Step 5: Summary & Totals -->
            <div class="form-section" id="summarySection" style="display: none;">
                <div class="section-title">
                    <i class="fas fa-calculator"></i> Step 5: Summary
                </div>

                <div id="billingItemsSummary" style="background: #f8f9fa; border-radius: 6px; padding: 20px; margin-bottom: 20px;">
                    <!-- Summary will be populated by JavaScript -->
                </div>

                <div class="total-section">
                    <div class="total-row">
                        <strong>Subtotal:</strong>
                        <span id="subtotalAmount">₹0.00</span>
                    </div>
                    <div class="total-row">
                        <strong>CGST (9%):</strong>
                        <span id="cgstAmount">₹0.00</span>
                    </div>
                    <div class="total-row">
                        <strong>SGST (9%):</strong>
                        <span id="sgstAmount">₹0.00</span>
                    </div>
                    <div class="total-row">
                        <strong>Total Tax:</strong>
                        <span id="totalTaxAmount">₹0.00</span>
                    </div>
                    <div class="total-divider"></div>
                    <div class="final-total">
                        <span class="final-total-label">TOTAL AMOUNT:</span>
                        <span class="final-total-amount" id="finalTotalAmount">₹0.00</span>
                    </div>
                </div>

                <div class="mt-4">
                    <label for="paymentStatus" class="form-label"><strong>Payment Status</strong></label>
                    <select class="form-control" id="paymentStatus" name="payment_status">
                        <option value="pending" selected>Pending</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>

                <div class="mt-3">
                    <label for="billingNotes" class="form-label"><strong>Notes (Optional)</strong></label>
                    <textarea class="form-control" id="billingNotes" name="notes" rows="3" placeholder="Add any additional notes..."></textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="button-group">
                <button type="reset" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i> Create Billing
                </button>
            </div>
        </form>
    </div>

    <script>
        const form = document.getElementById('billingForm');
        const appointmentSelect = document.getElementById('appointmentSelect');
        const informationSection = document.getElementById('informationSection');
        const billingItemsSection = document.getElementById('billingItemsSection');
        const extraChargesSection = document.getElementById('extraChargesSection');
        const summarySection = document.getElementById('summarySection');

        let selectedLabItems = [];
        let selectedPrescriptions = [];
        let appointmentData = null;

        // Show error message
        function showError(message) {
            const errorAlert = document.getElementById('errorAlert');
            const errorMessage = document.getElementById('errorMessage');
            errorMessage.textContent = message;
            errorAlert.style.display = 'block';
            setTimeout(() => {
                errorAlert.style.display = 'none';
            }, 5000);
        }

        // Show success message
        function showSuccess(message) {
            const successAlert = document.getElementById('successAlert');
            const successMessage = document.getElementById('successMessage');
            successMessage.textContent = message;
            successAlert.style.display = 'block';
        }

        // Format currency
        function formatCurrency(amount) {
            return '₹' + parseFloat(amount || 0).toFixed(2);
        }

        // Calculate and update totals
        function updateTotals() {
            let subtotal = 0;

            // Add consultation fee
            const consultationFee = parseFloat(document.getElementById('consultationFee').value) || 0;
            if (consultationFee > 0) {
                subtotal += consultationFee;
            }

            // Add selected lab items
            selectedLabItems.forEach(itemId => {
                const card = document.querySelector(`[data-lab-id="${itemId}"]`);
                if (card && card.classList.contains('selected')) {
                    const charge = parseFloat(card.querySelector('[data-charge]').getAttribute('data-charge')) || 0;
                    subtotal += charge;
                }
            });

            // Add extra charges
            const extraCharges = parseFloat(document.getElementById('extraCharges').value) || 0;
            if (extraCharges > 0) {
                subtotal += extraCharges;
            }

            // Calculate taxes
            const cgst = (subtotal * 0.09);
            const sgst = (subtotal * 0.09);
            const totalTax = cgst + sgst;
            const total = subtotal + totalTax;

            // Update display
            document.getElementById('subtotalAmount').textContent = formatCurrency(subtotal);
            document.getElementById('cgstAmount').textContent = formatCurrency(cgst);
            document.getElementById('sgstAmount').textContent = formatCurrency(sgst);
            document.getElementById('totalTaxAmount').textContent = formatCurrency(totalTax);
            document.getElementById('finalTotalAmount').textContent = formatCurrency(total);

            // Update summary
            updateSummary(subtotal, cgst, sgst, totalTax, total);
        }

        // Update billing items summary
        function updateSummary(subtotal, cgst, sgst, totalTax, total) {
            const summaryDiv = document.getElementById('billingItemsSummary');
            let html = '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">';

            // Add selected items to summary
            if (consultationFee > 0) {
                html += `
                    <div style="background: white; padding: 12px; border-radius: 6px; border-left: 4px solid #0d47a1;">
                        <div style="font-size: 13px; color: #666;">Doctor Consultation</div>
                        <div style="font-weight: 600; color: #0d47a1;">${formatCurrency(consultationFee)}</div>
                    </div>
                `;
            }

            selectedLabItems.forEach(itemId => {
                const card = document.querySelector(`[data-lab-id="${itemId}"]`);
                if (card && card.classList.contains('selected')) {
                    const testName = card.querySelector('.item-name').textContent;
                    const charge = parseFloat(card.querySelector('[data-charge]').getAttribute('data-charge')) || 0;
                    html += `
                        <div style="background: white; padding: 12px; border-radius: 6px; border-left: 4px solid #6a1b9a;">
                            <div style="font-size: 13px; color: #666;">${testName}</div>
                            <div style="font-weight: 600; color: #6a1b9a;">${formatCurrency(charge)}</div>
                        </div>
                    `;
                }
            });

            const extraCharges = parseFloat(document.getElementById('extraCharges').value) || 0;
            if (extraCharges > 0) {
                html += `
                    <div style="background: white; padding: 12px; border-radius: 6px; border-left: 4px solid #ff9800;">
                        <div style="font-size: 13px; color: #666;">${document.getElementById('extraChargesDesc').value || 'Additional Charges'}</div>
                        <div style="font-weight: 600; color: #ff9800;">${formatCurrency(extraCharges)}</div>
                    </div>
                `;
            }

            html += '</div>';
            summaryDiv.innerHTML = html;
        }

        // Fetch appointment details
        async function fetchAppointmentDetails(appointmentId) {
            try {
                const response = await fetch(`/staff/billing/appointment/${appointmentId}/details`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (!data.success) {
                    showError(data.message || 'Failed to load appointment details.');
                    appointmentSelect.value = '';
                    billingItemsSection.style.display = 'none';
                    informationSection.style.display = 'none';
                    extraChargesSection.style.display = 'none';
                    summarySection.style.display = 'none';
                    return;
                }

                appointmentData = data;

                // Update patient info
                document.getElementById('patientName').textContent = data.patient.name;
                document.getElementById('patientId').textContent = data.patient.id;
                document.getElementById('patientEmail').textContent = data.patient.email || '-';
                document.getElementById('patientPhone').textContent = data.patient.phone || '-';

                // Update doctor info
                document.getElementById('doctorName').textContent = data.doctor.name;
                document.getElementById('doctorSpecialization').textContent = data.doctor.specialization;

                // Update consultation fee
                document.getElementById('consultationFee').value = data.consultation_fee;
                document.getElementById('consultationPrice').textContent = formatCurrency(data.consultation_fee);

                // Populate lab items
                const labContainer = document.getElementById('labItemsContainer');
                if (data.lab_reports && data.lab_reports.length > 0) {
                    let labHtml = '';
                    data.lab_reports.forEach((lab, index) => {
                        labHtml += `
                            <div class="item-card" data-lab-id="${lab.id}">
                                <div class="item-info">
                                    <div class="item-name">${lab.test_name}</div>
                                    <div class="item-details">
                                        Result: ${lab.result}
                                        <span class="badge-lab" style="margin-left: 10px;">LAB TEST</span>
                                    </div>
                                </div>
                                <div class="item-price" data-charge="${lab.charge}">${formatCurrency(lab.charge)}</div>
                            </div>
                        `;
                    });

                    labContainer.innerHTML = labHtml;

                    // Add click handlers
                    document.querySelectorAll('[data-lab-id]').forEach(card => {
                        card.addEventListener('click', function() {
                            const itemId = this.getAttribute('data-lab-id');
                            this.classList.toggle('selected');
                            
                            if (selectedLabItems.includes(itemId)) {
                                selectedLabItems = selectedLabItems.filter(id => id !== itemId);
                            } else {
                                selectedLabItems.push(itemId);
                            }
                            
                            updateTotals();
                        });
                    });
                } else {
                    labContainer.innerHTML = '<div class="no-items-message"><i class="fas fa-beaker"></i> No lab reports available</div>';
                }

                // Show all sections
                informationSection.style.display = 'block';
                billingItemsSection.style.display = 'block';
                extraChargesSection.style.display = 'block';
                summarySection.style.display = 'block';

                updateTotals();

            } catch (error) {
                console.error('Error:', error);
                showError('Error loading appointment details. Please try again.');
            }
        }

        // Appointment selection handler
        appointmentSelect.addEventListener('change', function() {
            if (this.value) {
                selectedLabItems = [];
                selectedPrescriptions = [];
                fetchAppointmentDetails(this.value);
            } else {
                informationSection.style.display = 'none';
                billingItemsSection.style.display = 'none';
                extraChargesSection.style.display = 'none';
                summarySection.style.display = 'none';
            }
        });

        // Extra charges update
        document.getElementById('extraCharges').addEventListener('input', updateTotals);

        // Form submission
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            if (!appointmentSelect.value) {
                showError('Please select an appointment.');
                return;
            }

            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="loading-spinner"></span> Creating...';
            submitBtn.disabled = true;

            const formData = new FormData();
            formData.append('_token', document.querySelector('[name="_token"]').value);
            formData.append('appointment_id', appointmentSelect.value);
            formData.append('extra_charges', document.getElementById('extraCharges').value || 0);
            formData.append('extra_charges_description', document.getElementById('extraChargesDesc').value);
            formData.append('payment_status', document.getElementById('paymentStatus').value);
            formData.append('notes', document.getElementById('billingNotes').value);

            // Add lab items
            selectedLabItems.forEach((itemId, index) => {
                const card = document.querySelector(`[data-lab-id="${itemId}"]`);
                if (card.classList.contains('selected')) {
                    formData.append(`lab_items[${index}][id]`, itemId);
                    formData.append(`lab_items[${index}][charge]`, card.querySelector('[data-charge]').getAttribute('data-charge'));
                }
            });

            try {
                const response = await fetch('{{ route("staff.billing.create-from-appointment") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showSuccess(data.message || 'Billing created successfully!');
                    setTimeout(() => {
                        window.location.href = data.redirect || '{{ route("staff.billings") }}';
                    }, 1000);
                } else {
                    showError(data.message || 'Failed to create billing.');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            } catch (error) {
                console.error('Error:', error);
                showError('An error occurred while creating the billing.');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });

        // Load completed appointments on page load
        window.addEventListener('load', function() {
            // Filter to show only completed appointments without existing billings
            const options = appointmentSelect.querySelectorAll('option[value!=""]');
            options.forEach(option => {
                // In a real scenario, you'd filter via the backend
                // This is handled in the view controller
            });
        });
    </script>

@endsection
