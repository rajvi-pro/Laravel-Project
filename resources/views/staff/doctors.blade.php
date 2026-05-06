@extends('layouts.staff-layout')

@section('page-title', 'Doctor Schedule')
@section('title', 'Doctor Schedule - Staff Portal')

@section('content')
    <!-- Header Section -->
    <div class="page-header">
        <h2><i class="fas fa-stethoscope"></i> Doctor Schedule</h2>
    </div>

    <!-- Today's Doctor Availability -->
    <div style="margin-bottom: 30px;">
        <h5 style="margin: 0 0 20px 0; font-weight: 700; color: #1a1a1a; font-size: 18px;">Today's Doctor Availability</h5>

        <!-- Doctors Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            @if($doctors && $doctors->count() > 0)
                @foreach($doctors as $doctor)
                    <div style="background: white; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 20px; display: flex; flex-direction: column;">
                        <!-- Doctor Header -->
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 2px solid #e0e0e0;">
                            <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 20px;">
                                {{ strtoupper(substr($doctor->name, 0, 1)) }}
                            </div>
                            <div style="flex: 1;">
                                <h6 style="margin: 0; font-weight: 700; color: #1a1a1a; font-size: 15px;">{{ $doctor->name ?? 'N/A' }}</h6>
                                <p style="margin: 0; color: #999; font-size: 12px; font-weight: 500;">{{ $doctor->specialization ?? 'General Practice' }}</p>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div style="margin-bottom: 15px;">
                            <p style="margin: 0 0 5px 0; color: #999; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Phone</p>
                            <p style="margin: 0; color: #1a1a1a; font-size: 13px; font-weight: 500;">{{ $doctor->phone ?? 'N/A' }}</p>
                            @if($doctor->email)
                                <p style="margin: 5px 0 0 0; color: #1a1a1a; font-size: 13px;">{{ $doctor->email }}</p>
                            @endif
                        </div>

                        <!-- Availability Status -->
                        <div style="margin-bottom: 15px;">
                            @if($doctor->is_available_today)
                                <span style="display: inline-flex; align-items: center; gap: 6px; background: #c8e6c9; color: #2e7d32; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                    <i class="fas fa-check-circle"></i> Available
                                </span>
                            @else
                                <span style="display: inline-flex; align-items: center; gap: 6px; background: #ffcdd2; color: #c62828; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                    <i class="fas fa-ban"></i> Unavailable
                                </span>
                                @if($doctor->unavailable_message)
                                    <div style="font-size: 11px; color: #999; margin-top: 5px;">
                                        {{ $doctor->unavailable_message }}
                                    </div>
                                @endif
                            @endif
                        </div>

                        <!-- Today's Appointments -->
                        <div style="flex: 1; margin-bottom: 15px; padding: 15px; background: #f5f7fa; border-radius: 8px;">
                            <p style="margin: 0 0 8px 0; color: #999; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Today's Appointments</p>
                            @php
                                $todayAppts = $doctor->appointments()
                                    ->whereDate('appointment_date', today())
                                    ->count();
                            @endphp
                            <p style="margin: 0; color: #1a1a1a; font-size: 16px; font-weight: 700;">
                                {{ $todayAppts > 0 ? $todayAppts . ' ' . ($todayAppts > 1 ? 'appointments' : 'appointment') : 'No appointments' }}
                            </p>
                        </div>

                        <!-- View Schedule Button -->
                        <a href="{{ route('staff.doctor.schedule', $doctor->id) }}" style="display: block; text-align: center; padding: 10px 15px; background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%); color: white; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 13px; transition: all 0.3s ease;">
                            <i class="fas fa-calendar-alt"></i> View Full Schedule
                        </a>
                    </div>
                @endforeach
            @else
                <div style="grid-column: 1 / -1;">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <p style="margin: 0;">No doctors found</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection
