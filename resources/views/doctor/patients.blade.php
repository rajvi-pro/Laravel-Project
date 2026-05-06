@extends('layouts.doctor-layout')

@section('title', 'Patients')

@section('content')
<style>
    .header-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px;
        border-radius: 12px;
        color: white;
        margin-bottom: 30px;
    }

    .header-section h1 {
        margin-bottom: 10px;
        font-weight: 700;
        font-size: 2rem;
    }

    .search-bar {
        margin-bottom: 25px;
    }

    .search-input {
        width: 100%;
        padding: 12px 20px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .search-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .patient-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .patient-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border-top: 5px solid #667eea;
    }

    .patient-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.2);
    }

    .patient-avatar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px;
        text-align: center;
        color: white;
    }

    .patient-avatar-icon {
        font-size: 3rem;
        margin-bottom: 10px;
    }

    .patient-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: white;
    }

    .patient-info {
        padding: 20px;
    }

    .info-item {
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e5e7eb;
    }

    .info-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .info-label {
        font-size: 0.85rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 0.95rem;
        color: #1f2937;
        font-weight: 600;
    }

    .patient-actions {
        padding: 20px;
        padding-top: 0;
        display: flex;
        gap: 10px;
    }

    .action-btn {
        flex: 1;
        padding: 10px 15px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        font-size: 0.9rem;
    }

    .action-btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .action-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .action-btn-secondary {
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #d1d5db;
    }

    .action-btn-secondary:hover {
        background: #e5e7eb;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 20px;
    }

    .patient-count {
        background: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .patient-count-text {
        font-size: 1rem;
        color: #6b7280;
        margin-bottom: 10px;
    }

    .patient-count-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: #667eea;
    }

    @media (max-width: 768px) {
        .patient-grid {
            grid-template-columns: 1fr;
        }

        .header-section h1 {
            font-size: 1.5rem;
        }
    }
</style>

<div class="container-fluid">
    <div class="header-section">
        <h1>👥 My Patients</h1>
        <p style="margin-bottom: 0; opacity: 0.95;">Manage and view all your patients</p>
    </div>

    @if($patients->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">👥</div>
            <h2 style="color: #6b7280; font-size: 1.5rem;">No patients found</h2>
            <p>You don't have any registered patients yet.</p>
            <a href="{{ route('doctor.dashboard') }}" class="action-btn action-btn-primary" style="margin-top: 20px; display: inline-block;">Back to Dashboard</a>
        </div>
    @else
        <div class="patient-count">
            <div class="patient-count-text">📊 Total Patients</div>
            <div class="patient-count-value">{{ $patients->count() }}</div>
        </div>

        <div class="search-bar">
            <input 
                type="text" 
                class="search-input" 
                id="patientSearch" 
                placeholder="🔍 Search patients by name, email, or phone..."
                onkeyup="filterPatients()"
            >
        </div>

        <div class="patient-grid" id="patientGrid">
            @forelse($patients as $patient)
                <div class="patient-card" data-patient-name="{{ strtolower($patient->name) }}" data-patient-email="{{ strtolower($patient->email ?? '') }}" data-patient-phone="{{ $patient->phone ?? '' }}">
                    <div class="patient-avatar">
                        <div class="patient-avatar-icon">👨‍⚕️</div>
                        <div class="patient-name">{{ $patient->name }}</div>
                    </div>

                    <div class="patient-info">
                        <div class="info-item">
                            <div class="info-label">📧 Email</div>
                            <div class="info-value">{{ $patient->email ?? 'N/A' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">📞 Phone</div>
                            <div class="info-value">{{ $patient->phone ?? 'N/A' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">👤 Age</div>
                            <div class="info-value">
                                @if($patient->date_of_birth)
                                    {{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} years old
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">⚕️ Blood Type</div>
                            <div class="info-value">{{ $patient->blood_type ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <div class="patient-actions">
                        <a href="{{ route('doctor.patient.details', $patient->id) }}" class="action-btn action-btn-primary">
                            View Profile
                        </a>
                        <a href="{{ route('doctor.appointments') }}" class="action-btn action-btn-secondary">
                            Appointments
                        </a>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <div class="empty-state-icon">🔍</div>
                    <h2 style="color: #6b7280;">No patients found</h2>
                    <p>Try adjusting your search criteria</p>
                </div>
            @endforelse
        </div>
    @endif
</div>

<script>
    function filterPatients() {
        const searchInput = document.getElementById('patientSearch').value.toLowerCase();
        const patientCards = document.querySelectorAll('.patient-card');

        patientCards.forEach(card => {
            const name = card.getAttribute('data-patient-name');
            const email = card.getAttribute('data-patient-email');
            const phone = card.getAttribute('data-patient-phone');

            if (name.includes(searchInput) || email.includes(searchInput) || phone.includes(searchInput)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>
@endsection
