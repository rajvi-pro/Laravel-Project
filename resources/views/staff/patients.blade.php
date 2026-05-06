@extends('layouts.staff-layout')

@section('page-title', 'Patients')
@section('title', 'Patients - Staff Portal')

@section('content')
    <!-- Header Section -->
    <div class="page-header">
        <h2><i class="fas fa-users"></i> Patient Management</h2>
        <div style="flex: 1; min-width: 250px; max-width: 300px;">
            <div class="input-group">
                <span class="input-group-text" style="background: white; border: 1px solid #ddd;">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" class="form-control" id="searchPatients" placeholder="Search patients..." style="border: 1px solid #ddd;">
            </div>
        </div>
    </div>

    <!-- Metrics Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #0d47a1;">
            <div style="font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 10px;">Total Patients</div>
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a;">
                {{ $patients->count() ?? 0 }}
            </div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #1565c0;">
            <div style="font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 10px;">Avg. Appointments</div>
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a;">
                {{ $patients->count() > 0 ? number_format($appointments->count() / $patients->count(), 1) : '0' }}
            </div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #26a69a;">
            <div style="font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 10px;">Active This Month</div>
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a;">
                {{ $patients->filter(function($p) { return $p->updated_at && $p->updated_at->isCurrentMonth(); })->count() ?? 0 }}
            </div>
        </div>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #ff9800;">
            <div style="font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 10px;">Avg. Prescriptions</div>
            <div style="font-size: 32px; font-weight: 700; color: #1a1a1a;">
                {{ $patients->count() > 0 ? number_format($prescriptions->count() / $patients->count(), 1) : '0' }}
            </div>
        </div>
    </div>

    <!-- Patients Table -->
    <div class="table-container">
        @if($patients && $patients->count() > 0)
            <div class="table-header"><i class="fas fa-list"></i> All Patients</div>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-user"></i> Patient Name</th>
                            <th><i class="fas fa-envelope"></i> Email</th>
                            <th><i class="fas fa-phone"></i> Phone</th>
                            <th><i class="fas fa-venus-mars"></i> Gender</th>
                            <th><i class="fas fa-calendar"></i> Appointments</th>
                            <th><i class="fas fa-prescription-bottle"></i> Prescriptions</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="patientsTableBody">
                        @foreach($patients as $patient)
                            <tr class="patient-row">
                                <td style="font-weight: 600;">{{ $patient->name ?? $patient->full_name ?? 'N/A' }}</td>
                                <td>{{ $patient->email ?? '-' }}</td>
                                <td>{{ $patient->phone ?? '-' }}</td>
                                <td>
                                    <span class="badge" style="background-color: 
                                        @if(strtolower($patient->gender) == 'female') #ffe0b2
                                        @elseif(strtolower($patient->gender) == 'male') #b3e5fc
                                        @else #e0e0e0
                                        @endif; color:
                                        @if(strtolower($patient->gender) == 'female') #e65100
                                        @elseif(strtolower($patient->gender) == 'male') #006064
                                        @else #666
                                        @endif
                                    ">
                                        {{ ucfirst($patient->gender ?? 'N/A') }}
                                    </span>
                                </td>
                                <td style="text-align: center; font-weight: 600;">
                                    {{ $patient->appointments_count ?? \App\Models\Appointment::where('patient_id', $patient->id)->count() ?? 0 }}
                                </td>
                                <td style="text-align: center; font-weight: 600;">
                                    {{ $patient->prescriptions_count ?? \App\Models\Prescription::where('patient_id', $patient->id)->count() ?? 0 }}
                                </td>
                                <td>
                                    <a href="{{ route('staff.patient.detail', $patient->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-users"></i>
                </div>
                <p style="margin: 0;">No patients found</p>
            </div>
        @endif
    </div>

@endsection

@section('scripts')
    <script>
        // Patient search filter
        document.getElementById('searchPatients').addEventListener('keyup', function(e) {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('.patient-row');
            
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
@endsection
