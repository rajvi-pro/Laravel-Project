@extends('layouts.doctor-layout')

@section('page-title', 'Mark Unavailable')
@section('title', 'Mark Unavailable - Doctor Portal')

@section('content')
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap;">
        <h2><i class="fas fa-calendar-times"></i> Mark Unavailable</h2>
        <a href="{{ route('doctor.dashboard') }}" class="btn btn-secondary" style="padding: 8px 14px; font-weight: 600; border-radius: 8px;">
            <i class="fas fa-arrow-left"></i> Go Back to Dashboard
        </a>
    </div>

    <div class="row" style="margin-bottom: 30px;">
        <div class="col-md-6 offset-md-3">
            <div class="card" style="border: 1px solid #e5e7eb; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px; border-radius: 10px 10px 0 0;">
                    <h5 style="margin: 0; font-weight: 700;">Set Your Unavailability</h5>
                </div>

                <div class="card-body" style="padding: 20px;">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 style="margin-top: 0;">Please fix the following errors:</h6>
                            <ul style="margin-bottom: 0; margin-left: 20px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('doctor.unavailable.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="unavailable_date" class="form-label" style="font-weight: 600;">Start Date <span style="color: #dc3545;">*</span></label>
                            <input type="date" id="unavailable_date" name="unavailable_date" class="form-control" required value="{{ old('unavailable_date') }}" style="border-radius: 6px;">
                            <small class="form-text text-muted" style="display: block; margin-top: 5px;">Select the start date of unavailability</small>
                        </div>

                        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                            <div class="form-group">
                                <label for="end_date" class="form-label" style="font-weight: 600;">End Date <span style="color: #dc3545;">*</span></label>
                                <input type="date" id="end_date" name="end_date" class="form-control" required value="{{ old('end_date') }}" style="border-radius: 6px;">
                                <small class="form-text text-muted" style="display: block; margin-top: 5px;">Select the end date of unavailability</small>
                            </div>

                            <div style="display: flex; align-items: flex-end;">
                                <div style="background: #e3f2fd; border-left: 4px solid #0d47a1; padding: 10px; border-radius: 4px; font-size: 0.85rem; color: #0d47a1;">
                                    <i class="fas fa-info-circle"></i> <strong>Note:</strong> Both dates inclusive
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="reason" class="form-label" style="font-weight: 600;">Reason (Optional)</label>
                            <textarea id="reason" name="reason" rows="3" class="form-control" placeholder="Explain why you're unavailable (e.g., Conference, Personal time, Medical appointment)" style="border-radius: 6px; resize: vertical;">{{ old('reason') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" style="padding: 10px 20px; font-weight: 600; border-radius: 6px;">
                            <i class="fas fa-check"></i> Mark Unavailable
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($unavailabilities->count() > 0)
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="card" style="border: 1px solid #e5e7eb; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <div class="card-header" style="background-color: #f8f9fa; padding: 15px; border-radius: 10px 10px 0 0; border-bottom: 2px solid #e5e7eb;">
                        <h5 style="margin: 0; font-weight: 700;">Your Unavailability Schedule</h5>
                    </div>

                    <div class="card-body" style="padding: 15px;">
                        <div style="overflow-x: auto;">
                            <table class="table table-hover table-sm" style="margin-bottom: 0;">
                                <thead>
                                    <tr style="background-color: #f8f9fa;">
                                        <th style="padding: 10px; font-weight: 600;">From Date</th>
                                        <th style="padding: 10px; font-weight: 600;">To Date</th>
                                        <th style="padding: 10px; font-weight: 600;">Reason</th>
                                        <th style="padding: 10px; font-weight: 600; text-align: center; width: 100px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($unavailabilities as $unavailable)
                                        <tr style="border-bottom: 1px solid #e5e7eb;">
                                            <td style="padding: 10px;">
                                                <strong>{{ $unavailable->unavailable_date->format('d M Y') }}</strong>
                                                @if($unavailable->unavailable_date->isPast())
                                                    <br><small style="color: #999;">Past</small>
                                                @elseif($unavailable->unavailable_date->isToday())
                                                    <br><small style="color: #0d6efd;">Today</small>
                                                @endif
                                            </td>
                                            <td style="padding: 10px;">
                                                <strong>{{ ($unavailable->end_date ?? $unavailable->unavailable_date)->format('d M Y') }}</strong>
                                                @if($unavailable->end_date && $unavailable->end_date->isPast())
                                                    <br><small style="color: #999;">Past</small>
                                                @endif
                                            </td>
                                            <td style="padding: 10px; max-width: 300px; overflow-wrap: break-word;">
                                                {{ $unavailable->reason ?? '-' }}
                                            </td>
                                            <td style="padding: 10px; text-align: center;">
                                                <form action="{{ route('doctor.unavailable.delete', $unavailable->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remove this unavailability?');" style="padding: 4px 8px; font-size: 0.8rem;">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
