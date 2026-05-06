@extends('layouts.doctor-layout')

@section('title', 'Lab Results')

@section('content')
<style>
    .header-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px;
        border-radius: 12px;
        color: white;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-section h1 {
        margin: 0;
        font-weight: 700;
        font-size: 2rem;
    }

    .btn-create {
        background: white;
        color: #667eea;
        padding: 10px 20px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-create:hover {
        background: #f3f4f6;
    }

    .lab-result-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        border-left: 5px solid #667eea;
        transition: all 0.3s ease;
    }

    .lab-result-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.2);
    }

    .result-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .result-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1f2937;
    }

    .result-date {
        background: #f3f4f6;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        color: #374151;
        font-weight: 600;
    }

    .result-patient {
        font-size: 0.95rem;
        color: #6b7280;
        margin-bottom: 15px;
    }

    .result-tests {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin: 15px 0;
    }

    .test-item {
        background: #f9fafb;
        padding: 15px;
        border-radius: 8px;
        border-left: 3px solid #667eea;
    }

    .test-name {
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 5px;
    }

    .test-value {
        font-size: 0.9rem;
        color: #6b7280;
    }

    .result-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .action-btn {
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
        font-size: 0.9rem;
    }

    .action-btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .action-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .header-section {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .header-section h1 {
            font-size: 1.5rem;
        }

        .result-header {
            flex-direction: column;
            gap: 10px;
        }

        .result-tests {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-fluid">
    <div class="header-section">
        <div>
            <h1>🧪 Lab Results</h1>
        </div>
        <a href="#" class="btn-create" data-bs-toggle="modal" data-bs-target="#createLabResultModal">
            ➕ Add Lab Result
        </a>
    </div>
    @if($labResults->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">🧪</div>
            <h2 style="color: #6b7280; font-size: 1.5rem;">No lab results</h2>
            <p>You haven't added any lab results yet. Create your first lab result to get started.</p>
            <button class="action-btn action-btn-primary" data-bs-toggle="modal" data-bs-target="#createLabResultModal" style="margin-top: 20px;">
                Add Lab Result
            </button>
        </div>
    @else
        @foreach($labResults as $result)
            <div class="lab-result-card">
                <div class="result-header">
                    <div>
                        <div class="result-title">🧪 {{ $result->test_name ?? 'Lab Test' }}</div>
                        <div class="result-patient">👤 Patient: {{ $result->patient->name ?? 'N/A' }}</div>
                    </div>
                    <div class="result-date">{{ \Carbon\Carbon::parse($result->created_at)->format('M d, Y') }}</div>
                </div>

                <div class="result-tests">
                    <div class="test-item">
                        <div class="test-name">Test Name</div>
                        <div class="test-value">{{ $result->test_name ?? 'N/A' }}</div>
                    </div>
                    <div class="test-item">
                        <div class="test-name">Result</div>
                        <div class="test-value">{{ $result->result ?? 'N/A' }}</div>
                    </div>
                    <div class="test-item">
                        <div class="test-name">Normal Range</div>
                        <div class="test-value">{{ $result->normal_range ?? 'N/A' }}</div>
                    </div>
                    <div class="test-item">
                        <div class="test-name">Status</div>
                        <div class="test-value">
                            @if($result->status === 'completed')
                                ✅ Completed
                            @else
                                ⏳ Pending
                            @endif
                        </div>
                    </div>
                </div>

                @if($result->notes)
                    <div style="background: #f9fafb; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 3px solid #667eea;">
                        <strong style="color: #1f2937;">Notes:</strong><br>
                        <span style="color: #6b7280;">{{ $result->notes }}</span>
                    </div>
                @endif

                <div class="result-actions">
                    <a href="#" class="action-btn action-btn-primary">👁️ View Details</a>
                </div>
            </div>
        @endforeach
    @endif
</div>

<!-- Create Lab Result Modal -->
<div class="modal fade" id="createLabResultModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border: none; border-radius: 12px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                <h5 class="modal-title">➕ Add Lab Result</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 30px;">
                <form method="POST" action="{{ route('doctor.lab-result.create') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 700; color: #374151;">Select Patient</label>
                        <select class="form-select" name="patient_id" required style="border-radius: 6px; border: 2px solid #e5e7eb;">
                            <option value="" disabled {{ old('patient_id') ? '' : 'selected' }}>Choose a patient...</option>
                            @forelse($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>{{ $patient->name }}</option>
                            @empty
                                <option disabled>No patients available</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 700; color: #374151;">Test Name</label>
                        <input type="text" class="form-control" name="test_name" value="{{ old('test_name') }}" placeholder="e.g., Blood Test, X-Ray, etc." required style="border-radius: 6px; border: 2px solid #e5e7eb;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 700; color: #374151;">Result</label>
                        <input type="text" class="form-control" name="result" value="{{ old('result') }}" placeholder="e.g., 120 mg/dL" required style="border-radius: 6px; border: 2px solid #e5e7eb;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 700; color: #374151;">Normal Range</label>
                        <input type="text" class="form-control" name="normal_range" value="{{ old('normal_range') }}" placeholder="e.g., 70-110 mg/dL" style="border-radius: 6px; border: 2px solid #e5e7eb;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 700; color: #374151;">Status</label>
                        <select class="form-select" name="status" style="border-radius: 6px; border: 2px solid #e5e7eb;">
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>✅ Completed</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 700; color: #374151;">Notes</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Additional notes..." style="border-radius: 6px; border: 2px solid #e5e7eb;">{{ old('notes') }}</textarea>
                    </div>

                    <div style="display: flex; gap: 10px; justify-content: flex-end;">
                        <button type="button" class="btn" data-bs-dismiss="modal" style="padding: 10px 20px; background: #f3f4f6; color: #374151; border-radius: 6px; border: none; font-weight: 600;">Cancel</button>
                        <button type="submit" class="btn" style="padding: 10px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 6px; border: none; font-weight: 600;">Add Lab Result</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modalEl = document.getElementById('createLabResultModal');
            if (modalEl) {
                var modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
        });
    </script>
@endif

@endsection
