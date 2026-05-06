@extends('layouts.app')

@section('title', 'Staff - HMS')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="mb-3">Our Staff</h1>
            <p class="text-muted">Meet our dedicated healthcare professionals</p>
        </div>
    </div>

    @if($staff && count($staff) > 0)
        <div class="row g-4">
            @foreach($staff as $member)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $member->name ?? 'N/A' }}</h5>
                            <p class="card-text text-muted">{{ $member->role ?? 'Staff Member' }}</p>
                            <p class="card-text">
                                <small class="text-muted">📧 {{ $member->email ?? 'N/A' }}</small>
                            </p>
                            <p class="card-text">
                                <small class="text-muted">📞 {{ $member->phone ?? 'N/A' }}</small>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info text-center py-5">
            <p>No staff members available at the moment.</p>
        </div>
    @endif
</div>
@endsection
