@extends('layouts.staff-layout')

@section('page-title', 'Unavailable Doctors')
@section('title', 'Unavailable Doctors - Staff Portal')

@section('content')
    <div class="page-header">
        <h2><i class="fas fa-calendar-times"></i> Unavailable Doctors</h2>
    </div>

    @if($unavailabilities->count() > 0)
        <div class="card" style="border: 1px solid #e5e7eb; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div class="card-header" style="background-color: #fd7e14; color: white; padding: 15px; border-radius: 10px 10px 0 0;">
                <h5 style="margin: 0; font-weight: 700;">Unavailable Doctors ({{ $unavailabilities->total() }})</h5>
            </div>

            <div class="card-body" style="padding: 15px;">
                <div style="overflow-x: auto;">
                    <table class="table table-hover table-striped table-sm" style="font-size: 0.9rem;">
                        <thead>
                            <tr style="background-color: #f8f9fa;">
                                <th style="padding: 10px; font-weight: 600;">Doctor Name</th>
                                <th style="padding: 10px; font-weight: 600;">Specialization</th>
                                <th style="padding: 10px; font-weight: 600;">From Date</th>
                                <th style="padding: 10px; font-weight: 600;">To Date</th>
                                <th style="padding: 10px; font-weight: 600;">Reason</th>
                                <th style="padding: 10px; font-weight: 600; text-align: center; width: 150px;">Action</th>
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
                                            <br><span class="badge" style="background: #dc3545; color: white; font-size: 0.7rem;">Today</span>
                                        @endif
                                    </td>
                                    <td style="padding: 10px;">
                                        <strong>{{ ($unavailable->end_date ?? $unavailable->unavailable_date)->format('d M Y') }}</strong>
                                        @if(($unavailable->end_date ?? $unavailable->unavailable_date)->isPast())
                                            <br><small style="color: #6c757d;">Past</small>
                                        @endif
                                    </td>
                                    <td style="padding: 10px; max-width: 250px; overflow-wrap: break-word; font-size: 0.85rem;">
                                        {{ isset($unavailable->reason) && $unavailable->reason ? $unavailable->reason : '-' }}
                                    </td>
                                    <td style="padding: 10px; text-align: center;">
                                        <a href="{{ route('staff.affected-appointments', $unavailable->id) }}" class="btn btn-sm btn-primary" style="padding: 5px 10px; font-size: 0.8rem; white-space: nowrap;">
                                            <i class="fas fa-eye"></i> View
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
        <div class="alert alert-info" role="alert" style="border-radius: 8px; padding: 15px;">
            <i class="fas fa-info-circle"></i> No doctors have marked themselves as unavailable.
        </div>
    @endif
@endsection
