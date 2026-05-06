@extends('layouts.staff-layout')

@section('page-title', 'Lab Results')
@section('title', 'Lab Results - Staff Portal')

@section('content')
    <div style="display: flex; gap: 10px; margin-bottom: 20px;">
        <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="table-container">
        <div class="table-header"><i class="fas fa-flask"></i> Lab Results</div>
        
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-flask"></i>
            </div>
            <p style="margin: 0; font-size: 16px; font-weight: 500;">Lab results feature is not yet configured</p>
            <p style="margin: 10px 0 0 0; color: #999; font-size: 14px;">Lab results will appear here once the system is configured</p>
        </div>
    </div>

@endsection
