@extends('admin-layout')

@section('title', 'Search Doctors')

@section('sidebar')
<nav>
    <a href="{{ route('patient.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('patient.profile') }}"><i class="bi bi-person"></i> My Profile</a>
    <a href="{{ route('patient.appointments') }}"><i class="bi bi-calendar-check"></i> Appointments</a>
    <a href="{{ route('patient.medical-history') }}"><i class="bi bi-file-text"></i> Medical History</a>
    <a href="{{ route('patient.prescriptions') }}"><i class="bi bi-prescription"></i> Prescriptions</a>
    <a href="{{ route('patient.lab-results') }}"><i class="bi bi-beaker"></i> Lab Results</a>
    <hr class="bg-light">
    <form method="POST" action="{{ route('patient.logout') }}" style="margin-top: auto;">
        @csrf
        <button type="submit" class="btn btn-danger w-100">Logout</button>
    </form>
</nav>
@endsection

@section('content')
<div class="container-fluid">
    <h3 class="mb-3">Search Results for: "{{ $query }}"</h3>

    @if($doctors->isEmpty())
        <div class="alert alert-warning">No doctors found matching your search.</div>
    @else
        <div class="row g-3">
            @foreach($doctors as $doctor)
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $doctor->name }}</h5>
                            <p class="card-text mb-1">Specialization: {{ $doctor->specialization ?? 'N/A' }}</p>
                            <p class="card-text mb-1">Email: {{ $doctor->email }}</p>
                            <p class="card-text mb-1">Phone: {{ $doctor->phone }}</p>
                            <p class="card-text mb-0">Experience: {{ $doctor->experience_years ?? 0 }} years</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection