@extends('layouts.patient-layout')

@section('title', 'Select Doctor')

@section('content')
<style>
    .doctor-select-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-header-doctors {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        padding: 25px 30px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .page-header-doctors h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
    }

    .header-subtitle {
        color: #666;
        font-size: 14px;
        margin-top: 5px;
    }

    .btn-back-doctors {
        background: #e0e0e0;
        color: #333;
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .btn-back-doctors:hover {
        background: #d0d0d0;
        transform: translateY(-1px);
    }

    .specialization-filter {
        display: flex;
        gap: 10px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .filter-btn {
        background: white;
        color: #0f61cc;
        border: 2px solid #0f61cc;
        padding: 8px 16px;
        border-radius: 20px;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s ease;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: #0f61cc;
        color: white;
    }

    .doctors-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .doctor-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
        border-top: 4px solid #0f61cc;
        display: flex;
        flex-direction: column;
    }

    .doctor-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .doctor-header {
        padding: 25px;
        text-align: center;
        border-bottom: 1px solid #f0f0f0;
    }

    .doctor-name {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 8px 0;
    }

    .doctor-specialization {
        color: #0f61cc;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .doctor-body {
        padding: 20px 25px;
        flex: 1;
    }

    .info-row {
        display: flex;
        align-items: flex-start;
        margin-bottom: 15px;
        font-size: 13px;
    }

    .info-row:last-child {
        margin-bottom: 0;
    }

    .info-icon {
        color: #0f61cc;
        font-weight: 700;
        margin-right: 10px;
        min-width: 20px;
    }

    .info-label {
        font-weight: 600;
        color: #333;
        min-width: 100px;
    }

    .info-value {
        color: #666;
    }

    .experience-badge {
        display: inline-block;
        background: #e8f4f8;
        color: #0f61cc;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-top: 5px;
    }

    .doctor-footer {
        padding: 20px 25px;
        border-top: 1px solid #f0f0f0;
        background: #f9f9f9;
    }

    .btn-book-now {
        background: #0f61cc;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 700;
        font-size: 14px;
        width: 100%;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-book-now:hover {
        background: #0a4aa8;
        box-shadow: 0 4px 12px rgba(15, 97, 204, 0.3);
        transform: translateY(-2px);
    }

    .btn-book-now:active {
        transform: translateY(0);
    }

    .no-doctors {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .no-doctors-icon {
        font-size: 64px;
        color: #ddd;
        margin-bottom: 20px;
    }

    .no-doctors-text {
        font-size: 16px;
        margin-bottom: 10px;
    }

    @media (max-width: 768px) {
        .doctors-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .page-header-doctors {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .page-header-doctors h1 {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 480px) {
        .doctors-grid {
            grid-template-columns: 1fr;
        }

        .doctor-card {
            border-top: 3px solid #0f61cc;
        }

        .specialization-filter {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }
</style>

<div class="doctor-select-container">
    <div class="page-header-doctors">
        <div>
            <h1>👨‍⚕️ Select a Doctor</h1>
            <p class="header-subtitle">Choose a doctor to book your appointment</p>
        </div>
        <a href="{{ route('patient.dashboard') }}" class="btn-back-doctors">← Back to Dashboard</a>
    </div>

    @if(!$doctors->isEmpty())
        <!-- Specialization Filter -->
        <div class="specialization-filter">
            <button class="filter-btn active" onclick="filterDoctors('all')">
                All Doctors ({{ $doctors->count() }})
            </button>
            @foreach($doctors->groupBy('specialization')->keys() as $spec)
                @php
                    $count = $doctors->where('specialization', $spec)->count();
                @endphp
                <button class="filter-btn" onclick="filterDoctors('{{ $spec }}')">
                    {{ ucfirst($spec) }} ({{ $count }})
                </button>
            @endforeach
        </div>

        <!-- Doctors Grid -->
        <div class="doctors-grid">
            @foreach($doctors as $doctor)
                <div class="doctor-card" data-specialization="{{ $doctor->specialization ?? 'other' }}">
                    <div class="doctor-header">
                        <h3 class="doctor-name">Dr. {{ $doctor->name }}</h3>
                        <div class="doctor-specialization">{{ $doctor->specialization ?? 'General' }}</div>
                    </div>

                    <div class="doctor-body">
                        <!-- Email -->
                        <div class="info-row">
                            <span class="info-icon">📧</span>
                            <div>
                                <div class="info-label">Email</div>
                                <div class="info-value">{{ $doctor->email }}</div>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="info-row">
                            <span class="info-icon">📞</span>
                            <div>
                                <div class="info-label">Phone</div>
                                <div class="info-value">{{ $doctor->phone ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <!-- Experience -->
                        @if($doctor->experience_years)
                            <div class="info-row">
                                <span class="info-icon">⭐</span>
                                <div>
                                    <div class="info-label">Experience</div>
                                    <div class="info-value">{{ $doctor->experience_years }} years</div>
                                </div>
                            </div>
                        @endif

                        <!-- License Number -->
                        @if($doctor->license_number)
                            <div class="info-row">
                                <span class="info-icon">📋</span>
                                <div>
                                    <div class="info-label">License</div>
                                    <div class="info-value">{{ $doctor->license_number }}</div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="doctor-footer">
                        <a href="{{ route('patient.quick-book', $doctor->id) }}" class="btn-book-now">
                            ✓ Book Appointment
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="doctors-grid">
            <div class="no-doctors">
                <div class="no-doctors-icon">👨‍⚕️</div>
                <div class="no-doctors-text">No doctors available</div>
                <p style="font-size: 13px; margin: 0;">Please check back later or contact support.</p>
            </div>
        </div>
    @endif
</div>

<script>
    function filterDoctors(specialization) {
        // Update active button
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');

        // Filter cards
        const cards = document.querySelectorAll('.doctor-card');
        cards.forEach(card => {
            if (specialization === 'all') {
                card.style.display = 'flex';
            } else {
                const cardSpec = card.getAttribute('data-specialization');
                card.style.display = cardSpec === specialization ? 'flex' : 'none';
            }
        });
    }
</script>
@endsection
