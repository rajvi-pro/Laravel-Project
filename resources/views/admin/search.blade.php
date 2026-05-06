@extends('admin-layout')

@section('title', 'Search Results')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Search Results for: <span class="text-primary">"{{ $query }}"</span></h3>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Back to Dashboard</a>
    </div>

    @if($patients->isEmpty() && $doctors->isEmpty() && $staff->isEmpty())
        <div class="text-center py-5">
            <i class="fa fa-search fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No results found</h4>
            <p class="text-muted">Try searching with different keywords or check your spelling.</p>
        </div>
    @else
        @if($patients->count() > 0)
            <div class="mb-5">
                <h4 class="mb-3"><i class="fa fa-user-injured text-primary me-2"></i>Patients ({{ $patients->count() }})</h4>
                <div class="row g-3">
                    @foreach($patients as $patient)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fa fa-user-injured fa-lg text-primary me-2"></i>
                                        <h5 class="card-title mb-0">{{ $patient->name }}</h5>
                                    </div>
                                    <p class="card-text mb-1"><strong>Email:</strong> {{ $patient->email }}</p>
                                    <p class="card-text mb-1"><strong>Phone:</strong> {{ $patient->phone ?: 'N/A' }}</p>
                                    <p class="card-text mb-1"><strong>Gender:</strong> {{ $patient->gender ?: 'N/A' }}</p>
                                    <p class="card-text mb-0"><strong>Blood Group:</strong> {{ $patient->blood_group ?: 'N/A' }}</p>
                                </div>
                                <div class="card-footer bg-transparent border-0">
                                    <a href="{{ route('admin.patients.show', $patient->id) }}" class="btn btn-primary btn-sm">View Details</a>
                                    <a href="{{ route('admin.patients.edit', $patient->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($doctors->count() > 0)
            <div class="mb-5">
                <h4 class="mb-3"><i class="fa fa-user-md text-success me-2"></i>Doctors ({{ $doctors->count() }})</h4>
                <div class="row g-3">
                    @foreach($doctors as $doctor)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fa fa-user-md fa-lg text-success me-2"></i>
                                        <h5 class="card-title mb-0">{{ $doctor->name }}</h5>
                                    </div>
                                    <p class="card-text mb-1"><strong>Specialization:</strong> {{ $doctor->specialization ?: 'N/A' }}</p>
                                    <p class="card-text mb-1"><strong>Email:</strong> {{ $doctor->email }}</p>
                                    <p class="card-text mb-1"><strong>Phone:</strong> {{ $doctor->phone ?: 'N/A' }}</p>
                                    <p class="card-text mb-0"><strong>Experience:</strong> {{ $doctor->experience_years ?: 0 }} years</p>
                                </div>
                                <div class="card-footer bg-transparent border-0">
                                    <a href="{{ route('admin.doctors.show', $doctor->id) }}" class="btn btn-success btn-sm">View Details</a>
                                    <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($staff->count() > 0)
            <div class="mb-5">
                <h4 class="mb-3"><i class="fa fa-users text-warning me-2"></i>Staff ({{ $staff->count() }})</h4>
                <div class="row g-3">
                    @foreach($staff as $member)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fa fa-users fa-lg text-warning me-2"></i>
                                        <h5 class="card-title mb-0">{{ $member->name }}</h5>
                                    </div>
                                    <p class="card-text mb-1"><strong>Role:</strong> {{ $member->role ?: 'N/A' }}</p>
                                    <p class="card-text mb-1"><strong>Email:</strong> {{ $member->email }}</p>
                                    <p class="card-text mb-1"><strong>Phone:</strong> {{ $member->phone ?: 'N/A' }}</p>
                                    <p class="card-text mb-0"><strong>Department:</strong> {{ $member->department ?: 'N/A' }}</p>
                                </div>
                                <div class="card-footer bg-transparent border-0">
                                    <a href="{{ route('admin.staff.show', $member->id) }}" class="btn btn-warning btn-sm">View Details</a>
                                    <a href="{{ route('admin.staff.edit', $member->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif
</div>
@endsection