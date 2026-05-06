@extends('layouts.staff-layout')

@section('page-title', 'Appointments')
@section('title', 'Appointments - Staff Portal')

@section('content')
    <!-- Header Section -->
    <div class="page-header">
        <h2><i class="fas fa-calendar-check"></i> Manage Appointments</h2>
        <button class="btn-book" data-bs-toggle="modal" data-bs-target="#newAppointmentModal">
            <i class="fas fa-plus"></i> New Appointment
        </button>
    </div>

    <!-- Metrics Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #0d47a1;">
            <div style="font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 10px;">Total Appointments</div>
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a;">
                {{ $appointments->total() ?? count($appointments) ?? 0 }}
            </div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #ff9800;">
            <div style="font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 10px;">Scheduled</div>
            <div style="font-size: 32px; font-weight: 700; color: #ff9800;">
                {{ $appointments->where('status', 'scheduled')->count() ?? 0 }}
            </div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #26a69a;">
            <div style="font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 10px;">Completed</div>
            <div style="font-size: 32px; font-weight: 700; color: #26a69a;">
                {{ $appointments->where('status', 'completed')->count() ?? 0 }}
            </div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #e53935;">
            <div style="font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 10px;">Cancelled</div>
            <div style="font-size: 32px; font-weight: 700; color: #e53935;">
                {{ $appointments->where('status', 'cancelled')->count() ?? 0 }}
            </div>
        </div>
    </div>

    <!-- Appointments Table -->
    <div class="table-container">
        @if($appointments && $appointments->count() > 0)
            <div class="table-header"><i class="fas fa-list"></i> All Appointments</div>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-calendar"></i> Date</th>
                            <th><i class="fas fa-clock"></i> Time</th>
                            <th><i class="fas fa-user"></i> Patient</th>
                            <th><i class="fas fa-stethoscope"></i> Doctor</th>
                            <th><i class="fas fa-clipboard"></i> Reason</th>
                            <th><i class="fas fa-flag"></i> Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') ?? 'N/A' }}</td>
                                <td>{{ $appointment->appointment_time ?? 'N/A' }}</td>
                                <td>{{ $appointment->patient->name ?? $appointment->patient->full_name ?? 'N/A' }}</td>
                                <td>{{ $appointment->doctor->name ?? 'Unassigned' }}</td>
                                <td>{{ $appointment->reason ?? '-' }}</td>
                                <td>
                                    <span class="badge" style="background-color: 
                                        @if($appointment->status == 'completed') #26a69a
                                        @elseif($appointment->status == 'pending') #ff9800
                                        @elseif($appointment->status == 'cancelled') #e53935
                                        @else #0d47a1
                                        @endif
                                    ">
                                        {{ ucfirst($appointment->status ?? 'Scheduled') }}
                                    </span>
                                </td>
                                <td style="white-space: nowrap;">
                                    <button class="btn btn-sm btn-outline-primary" title="Edit" onclick="editAppointment({{ $appointment->id }})">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" title="Cancel" onclick="openCancelModal({{ $appointment->id }}, '{{ $appointment->patient->name ?? $appointment->patient->full_name ?? 'Patient' }}')">
                                        <i class="fas fa-times-circle"></i> Cancel
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(method_exists($appointments, 'links'))
                <div style="margin-top: 20px; padding: 0 20px;">
                    {{ $appointments->links('pagination::bootstrap-4') }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-calendar"></i>
                </div>
                <p style="margin: 0;">No appointments found</p>
            </div>
        @endif
    </div>

    <!-- New Appointment Modal -->
    <div class="modal fade" id="newAppointmentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('staff.appointment.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="patient_id" class="form-label">Patient</label>
                                <select class="form-control" name="patient_id" required>
                                    <option value="">Select Patient</option>
                                    @foreach($patients ?? [] as $patient)
                                        <option value="{{ $patient->id }}">{{ $patient->name ?? $patient->full_name }} (ID: {{ $patient->id ?? $patient->medical_id }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="doctor_id" class="form-label">Doctor</label>
                                <select class="form-control" id="doctor_id" name="doctor_id" required onchange="checkDoctorAvailability()">
                                    <option value="">Select Doctor</option>
                                    @foreach($doctors ?? [] as $doctor)
                                        @php
                                            $isAvailable = $doctor->isAvailableToday();
                                            $unavailabilityMsg = !$isAvailable ? $doctor->getUnavailabilityMessage() : null;
                                        @endphp
                                        <option value="{{ $doctor->id }}" 
                                            data-available="{{ $isAvailable ? 'true' : 'false' }}"
                                            data-message="{{ $unavailabilityMsg ?? '' }}">
                                            {{ $doctor->name }} ({{ $doctor->specialization ?? 'General' }})
                                            @if(!$isAvailable)
                                                ⚠️ NOT AVAILABLE
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <small id="availability_message" style="display: none; color: #e53935; margin-top: 5px; display: block;"></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="appointment_date" class="form-label">Date</label>
                                <input type="date" class="form-control" name="appointment_date" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="appointment_time" class="form-label">Time</label>
                                <input type="time" class="form-control" name="appointment_time" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason for Appointment</label>
                            <textarea class="form-control" name="reason" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Appointment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Cancel Appointment Modal -->
    <div class="modal fade" id="cancelAppointmentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="cancelAppointmentForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="modal-body">
                        <p style="margin-bottom: 15px; color: #666;">
                            <strong>Patient:</strong> <span id="cancelPatientName"></span>
                        </p>
                        <div class="mb-3">
                            <label for="cancellation_reason" class="form-label">Cancellation Reason <span style="color: #e53935;">*</span></label>
                            <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" rows="4" placeholder="Enter reason for cancellation..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times-circle"></i> Cancel Appointment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        function editAppointment(id) {
            // Redirect to appointment detail page
            window.location.href = '/staff/appointments/' + id;
        }

        function openCancelModal(appointmentId, patientName) {
            document.getElementById('cancelPatientName').textContent = patientName;
            document.getElementById('cancelAppointmentForm').action = '/staff/appointments/' + appointmentId + '/cancel';
            document.getElementById('cancellation_reason').value = '';
            new bootstrap.Modal(document.getElementById('cancelAppointmentModal')).show();
        }

        function checkDoctorAvailability() {
            const select = document.getElementById('doctor_id');
            const selected = select.options[select.selectedIndex];
            const isAvailable = selected.getAttribute('data-available') === 'true';
            const message = selected.getAttribute('data-message');
            const messageDiv = document.getElementById('availability_message');

            if (!isAvailable && message) {
                messageDiv.textContent = '⚠️ Doctor not available: ' + message;
                messageDiv.style.display = 'block';
            } else {
                messageDiv.style.display = 'none';
            }
        }
    </script>
@endsection
