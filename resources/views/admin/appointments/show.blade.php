
@extends('admin-layout')

@section('title', 'Appointment Detail')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Appointment {{ $appointment->id }}</h1>
        <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="card card-panel">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Patient</dt>
                <dd class="col-sm-9">{{ optional($appointment->patient)->name }} ({{ optional($appointment->patient)->email }})</dd>

                <dt class="col-sm-3">Doctor</dt>
                <dd class="col-sm-9">{{ optional($appointment->doctor)->name }} ({{ optional($appointment->doctor)->department }})</dd>

                <dt class="col-sm-3">Date</dt>
                <dd class="col-sm-9">{{ \Illuminate\Support\Carbon::parse($appointment->appointment_date)->format('d M Y') }}</dd>

                <dt class="col-sm-3">Time</dt>
                <dd class="col-sm-9">{{ $appointment->appointment_time }}</dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">
                    @if($appointment->status == 'completed')
                        <span class="badge bg-success">✅ Completed</span>
                    @elseif($appointment->status == 'cancelled')
                        <span class="badge bg-danger">❌ Cancelled</span>
                    @else
                        <span class="badge bg-primary">📅 Scheduled</span>
                    @endif
                </dd>

                <dt class="col-sm-3">Notes</dt>
                <dd class="col-sm-9">{{ $appointment->notes }}</dd>

                @if($appointment->status == 'cancelled' && $appointment->cancellation_reason)
                    <dt class="col-sm-3">Cancellation Reason</dt>
                    <dd class="col-sm-9"><em>{{ $appointment->cancellation_reason }}</em></dd>
                @endif
            </dl>
        </div>
    </div>
</div>

@endsection
