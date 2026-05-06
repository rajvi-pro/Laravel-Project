@extends('layouts.patient-layout')

@section('page-title', 'Add Member')
@section('title', 'Add Family Member - HMS')

@section('content')
<style>
    .page-title {
        color: #1a1a1a;
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 25px;
    }

    .form-card {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        max-width: 800px;
    }

    .form-card h3 {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 25px 0;
        padding-bottom: 15px;
        border-bottom: 2px solid #0d47a1;
    }

    .form-label {
        font-weight: 700;
        color: #1a1a1a;
        font-size: 14px;
        margin-bottom: 8px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 11px;
    }

    .form-control, .form-select {
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 10px 13px;
        font-size: 14px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0d47a1;
        box-shadow: 0 0 0 3px rgba(13, 71, 161, 0.1);
        outline: none;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-row.full {
        grid-template-columns: 1fr;
    }

    .btn-save {
        background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
        border: none;
        color: white;
        padding: 12px 30px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-right: 10px;
    }

    .btn-save:hover {
        box-shadow: 0 5px 15px rgba(13, 71, 161, 0.3);
        transform: translateY(-2px);
        color: white;
    }

    .btn-cancel {
        background: white;
        border: 2px solid #ddd;
        color: #1a1a1a;
        padding: 10px 30px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-cancel:hover {
        border-color: #0d47a1;
        color: #0d47a1;
    }

    .alert {
        border-radius: 6px;
        margin-bottom: 20px;
        padding: 15px;
    }

    .alert-danger {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }

    .text-danger {
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<h2 class="page-title">Add Family Member</h2>

<div class="form-card">
    <h3><i class="fas fa-user-plus" style="color: #0d47a1; margin-right: 10px;"></i> Family Member Information</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>
            <ul style="margin: 10px 0 0 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('patient.add-member.store') }}">
        @csrf

        <div class="form-row">
            <div>
                <label class="form-label">Member Full Name *</label>
                <input type="text" class="form-control @error('member_name') is-invalid @enderror" name="member_name" placeholder="e.g., John Smith" value="{{ old('member_name') }}" required>
                @error('member_name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="form-label">Relationship *</label>
                <select class="form-select @error('relationship') is-invalid @enderror" name="relationship" required>
                    <option value="">Select Relationship</option>
                    <option value="Spouse" {{ old('relationship') == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                    <option value="Child" {{ old('relationship') == 'Child' ? 'selected' : '' }}>Child</option>
                    <option value="Parent" {{ old('relationship') == 'Parent' ? 'selected' : '' }}>Parent</option>
                    <option value="Sibling" {{ old('relationship') == 'Sibling' ? 'selected' : '' }}>Sibling</option>
                    <option value="Other" {{ old('relationship') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('relationship') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-row">
            <div>
                <label class="form-label">Date of Birth *</label>
                <input type="date" class="form-control @error('member_date_of_birth') is-invalid @enderror" name="member_date_of_birth" value="{{ old('member_date_of_birth') }}" required>
                @error('member_date_of_birth') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="form-label">Gender *</label>
                <select class="form-select @error('member_gender') is-invalid @enderror" name="member_gender" required>
                    <option value="">Select Gender</option>
                    <option value="male" {{ old('member_gender') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('member_gender') == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('member_gender') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('member_gender') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-row">
            <div>
                <label class="form-label">Phone Number</label>
                <input type="tel" class="form-control" name="member_phone" placeholder="Optional" value="{{ old('member_phone') }}">
            </div>
            <div>
                <label class="form-label">Blood Group</label>
                <select class="form-select" name="member_blood_group">
                    <option value="">Select Blood Group</option>
                    <option value="A+" {{ old('member_blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
                    <option value="A-" {{ old('member_blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
                    <option value="B+" {{ old('member_blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
                    <option value="B-" {{ old('member_blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
                    <option value="O+" {{ old('member_blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                    <option value="O-" {{ old('member_blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
                    <option value="AB+" {{ old('member_blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                    <option value="AB-" {{ old('member_blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                </select>
            </div>
        </div>

        <div class="form-row full">
            <div>
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="member_notes" rows="3" placeholder="Optional notes about this family member">{{ old('member_notes') }}</textarea>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn-save">
                <i class="fas fa-plus"></i> Add Member
            </button>
            <a href="{{ route('patient.profile') }}" class="btn-cancel">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>

@endsection
