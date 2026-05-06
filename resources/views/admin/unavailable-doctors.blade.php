@extends('admin-layout')

@section('title', 'Unavailable Doctors')

@section('sidebar')
<nav>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('admin.patients.index') }}"><i class="bi bi-people"></i> Patients</a>
    <a href="{{ route('admin.doctors.index') }}"><i class="bi bi-person-check"></i> Doctors</a>
    <a href="{{ route('admin.staff.index') }}"><i class="bi bi-person-badge"></i> Staff</a>
    <a href="{{ route('admin.appointments.index') }}"><i class="bi bi-calendar-check"></i> Appointments</a>
    <a href="{{ route('admin.unavailable-doctors') }}" class="active"><i class="bi bi-calendar-times"></i> Unavailable Doctors</a>
    <hr class="bg-light">
    <form method="POST" action="{{ route('admin.logout') }}" style="margin-top: auto;">
        @csrf
        <button type="submit" class="btn btn-danger w-100">Logout</button>
    </form>
</nav>
@endsection

@section('content')
<div class="container-fluid" style="padding: 15px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding: 0 5px;">
        <h2 style="font-size: 1.3rem; font-weight: 700; color: #333; margin: 0;">Unavailable Doctors & Affected Appointments</h2>
    </div>

    @if($unavailabilities->count() > 0)
        <div class="card" style="border: 1px solid #dee2e6; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin: 0;">
            <div class="card-header" style="background-color: #fd7e14; color: white; padding: 10px 15px; border-radius: 8px 8px 0 0;">
                <h5 style="margin: 0; font-size: 1rem; font-weight: 600;">Unavailable Doctors ({{ $unavailabilities->total() }})</h5>
            </div>
            <div class="card-body" style="padding: 10px;">
                <div style="overflow-x: auto;">
                    <table class="table table-striped table-sm" style="margin-bottom: 0;">
                        <thead>
                            <tr style="background-color: #f8f9fa;">
                                <th style="padding: 10px; font-weight: 600;">Doctor Name</th>
                                <th style="padding: 10px; font-weight: 600;">Specialization</th>
                                <th style="padding: 10px; font-weight: 600;">From Date</th>
                                <th style="padding: 10px; font-weight: 600;">To Date</th>
                                <th style="padding: 10px; font-weight: 600;">Reason</th>
                                <th style="padding: 10px; font-weight: 600; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($unavailabilities as $unavailable)
                                <tr style="border-bottom: 1px solid #dee2e6;">
                                    <td style="padding: 10px; font-weight: 500;">{{ $unavailable->doctor->name ?? 'N/A' }}</td>
                                    <td style="padding: 10px;">{{ $unavailable->doctor->specialization ?? 'N/A' }}</td>
                                    <td style="padding: 10px;">
                                        <strong>{{ $unavailable->unavailable_date->format('d M Y') }}</strong>
                                        @if($unavailable->unavailable_date->isPast())
                                            <br><small style="color: #6c757d;">Past</small>
                                        @elseif($unavailable->unavailable_date->isToday())
                                            <br><span class="badge bg-danger" style="font-size: 0.75rem;">Today</span>
                                        @endif
                                    </td>
                                    <td style="padding: 10px;">
                                        <strong>{{ ($unavailable->end_date ?? $unavailable->unavailable_date)->format('d M Y') }}</strong>
                                        @if(($unavailable->end_date ?? $unavailable->unavailable_date)->isPast())
                                            <br><small style="color: #6c757d;">Past</small>
                                        @endif
                                    </td>
                                    <td style="padding: 10px; max-width: 250px; overflow-wrap: break-word;">
                                        {{ isset($unavailable->reason) && $unavailable->reason ? $unavailable->reason : '-' }}
                                    </td>
                                    <td style="padding: 10px; text-align: center;">
                                        <a href="{{ route('admin.affected-appointments', $unavailable->id) }}" class="btn btn-sm btn-primary" style="padding: 4px 8px; font-size: 0.8rem;">
                                            <i class="bi bi-eye"></i> View Affected
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div style="margin-top: 15px; display: flex; justify-content: center;">
                    {{ $unavailabilities->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle"></i> No doctors have marked themselves unavailable currently.
        </div>
    @endif
</div>
@endsection