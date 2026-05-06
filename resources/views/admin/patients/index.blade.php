@extends('admin-layout')

@section('title', 'Patient Management')

@section('sidebar')
<nav>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('admin.patients.index') }}" class="active"><i class="bi bi-people"></i> Patients</a>
    <a href="{{ route('admin.doctors.index') }}"><i class="bi bi-person-check"></i> Doctors</a>
    <a href="{{ route('admin.staff.index') }}"><i class="bi bi-person-badge"></i> Staff</a>
    <hr class="bg-light">
    <form method="POST" action="{{ route('admin.logout') }}" style="margin-top: auto;">
        @csrf
        <button type="submit" class="btn btn-danger w-100">Logout</button>
    </form>
</nav>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Premium Attractive Header Bar with Background & Stats -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%); border-radius: 16px; padding: 32px; margin-bottom: 32px; box-shadow: 0 8px 32px rgba(102, 126, 234, 0.2), 0 2px 8px rgba(102, 126, 234, 0.15); position: relative; overflow: hidden;">
        <!-- Animated Background Elements -->
        <div style="position: absolute; top: -50%; right: -10%; width: 300px; height: 300px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; filter: blur(40px);"></div>
        <div style="position: absolute; bottom: -30%; left: -5%; width: 250px; height: 250px; background: rgba(255, 255, 255, 0.08); border-radius: 50%; filter: blur(40px);"></div>
        
        <div class="d-flex justify-content-between align-items-center mb-4" style="position: relative; z-index: 1;">
            <div>
                <h5 style="color: rgba(255, 255, 255, 0.85); font-weight: 600; margin-bottom: 8px; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">👥 Manage Your Patients</h5>
                <h1 style="color: #ffffff; font-weight: 800; font-size: 2.5rem; margin: 0; text-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);">Patient Directory</h1>
                <p style="color: rgba(255, 255, 255, 0.9); font-size: 0.95rem; margin: 12px 0 0 0; font-weight: 500;">{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</p>
            </div>
            <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                <div style="text-align: right;">
                    <p style="color: #ffffff; font-weight: 700; margin: 0; font-size: 0.95rem; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</p>
                    <p style="color: rgba(255, 255, 255, 0.85); font-size: 0.85rem; margin: 4px 0 0 0;">Administrator</p>
                </div>
                <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0.15) 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 1.5rem; border: 3px solid rgba(255, 255, 255, 0.4); box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2), inset 0 1px 2px rgba(255, 255, 255, 0.3);">
                    {{ strtoupper(substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1)) }}
                </div>
            </div>
        </div>

        <!-- Stat Cards with Glass Morphism -->
        <div class="row mt-4" style="position: relative; z-index: 1;">
            <div class="col-md-3 mb-3">
                <div style="padding: 20px; background: rgba(255, 255, 255, 0.15); border-radius: 12px; text-align: center; backdrop-filter: blur(10px); border: 1.5px solid rgba(255, 255, 255, 0.25); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-users" style="color: rgba(255, 255, 255, 0.8); font-size: 1.8rem; margin-bottom: 8px;"></i>
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.75rem; font-weight: 600; margin: 8px 0 0 0; text-transform: uppercase; letter-spacing: 0.4px;">TOTAL PATIENTS</p>
                    <h2 style="color: #ffffff; font-weight: 800; margin: 10px 0 0 0; font-size: 2.2rem; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">{{ $patients->total() ?? 0 }}</h2>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div style="padding: 20px; background: rgba(255, 255, 255, 0.15); border-radius: 12px; text-align: center; backdrop-filter: blur(10px); border: 1.5px solid rgba(255, 255, 255, 0.25); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-user-check" style="color: rgba(255, 255, 255, 0.8); font-size: 1.8rem; margin-bottom: 8px;"></i>
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.75rem; font-weight: 600; margin: 8px 0 0 0; text-transform: uppercase; letter-spacing: 0.4px;">CURRENT PAGE</p>
                    <h2 style="color: #ffffff; font-weight: 800; margin: 10px 0 0 0; font-size: 2.2rem; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">{{ $patients->count() ?? 0 }}</h2>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div style="padding: 20px; background: rgba(255, 255, 255, 0.15); border-radius: 12px; text-align: center; backdrop-filter: blur(10px); border: 1.5px solid rgba(255, 255, 255, 0.25); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-envelope" style="color: rgba(255, 255, 255, 0.8); font-size: 1.8rem; margin-bottom: 8px;"></i>
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.75rem; font-weight: 600; margin: 8px 0 0 0; text-transform: uppercase; letter-spacing: 0.4px;">VERIFIED EMAILS</p>
                    <h2 style="color: #ffffff; font-weight: 800; margin: 10px 0 0 0; font-size: 2.2rem; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                        @php
                            $verified = 0;
                            foreach($patients as $p) {
                                if($p->email_verified_at) $verified++;
                            }
                            echo $verified;
                        @endphp
                    </h2>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div style="padding: 20px; background: rgba(255, 255, 255, 0.15); border-radius: 12px; text-align: center; backdrop-filter: blur(10px); border: 1.5px solid rgba(255, 255, 255, 0.25); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-calendar-check" style="color: rgba(255, 255, 255, 0.8); font-size: 1.8rem; margin-bottom: 8px;"></i>
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.75rem; font-weight: 600; margin: 8px 0 0 0; text-transform: uppercase; letter-spacing: 0.4px;">THIS MONTH</p>
                    <h2 style="color: #ffffff; font-weight: 800; margin: 10px 0 0 0; font-size: 2.2rem; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                        @php
                            $thisMonth = 0;
                            foreach($patients as $p) {
                                if($p->created_at?->isCurrentMonth()) $thisMonth++;
                            }
                            echo $thisMonth;
                        @endphp
                    </h2>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="d-flex justify-content-between align-items-center mt-4" style="position: relative; z-index: 1;">
            <div></div>
            <a href="{{ route('admin.patients.create') }}" class="btn btn-light" style="font-weight: 700; padding: 12px 28px; font-size: 1rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); transition: all 0.3s ease; border: none;">
                <i class="fas fa-plus" style="margin-right: 8px;"></i> Add New Patient
            </a>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div style="background: #ffffff; border-radius: 12px; padding: 20px; margin-bottom: 24px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);">
        <form method="GET" action="{{ route('admin.patients.index') }}" class="d-flex gap-2 flex-wrap align-items-end">
            <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                <label for="location" style="font-size: 0.85rem; font-weight: 600; color: #333; margin-bottom: 6px; display: block;">📍 Location</label>
                <input type="text" name="location" id="location" class="form-control" value="{{ request('location') }}" placeholder="City or address" style="border-radius: 8px; border: 1.5px solid #e0e0e0;" />
            </div>
            <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
                <label for="state" style="font-size: 0.85rem; font-weight: 600; color: #333; margin-bottom: 6px; display: block;">🏘️ State</label>
                <input type="text" name="state" id="state" class="form-control" value="{{ request('state') }}" placeholder="State" style="border-radius: 8px; border: 1.5px solid #e0e0e0;" />
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <button type="submit" class="btn btn-primary" style="padding: 8px 20px; border-radius: 8px; font-weight: 600;">🔍 Search</button>
                <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary" style="padding: 8px 20px; border-radius: 8px; font-weight: 600;">↻ Reset</a>
            </div>
        </form>
    </div>

    <!-- Patients Table Card -->
    <div class="card" style="border: none; border-radius: 12px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08); margin: 0; overflow: hidden;">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 16px 20px; border: none;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    @if(request('location') || request('state'))
                        <h5 style="margin: 0; font-size: 1.1rem; font-weight: 700; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);"><i class="fas fa-filter" style="margin-right: 8px;"></i>Filtered Patients</h5>
                        <p style="margin: 6px 0 0 0; font-size: 0.9rem; opacity: 0.9;">Showing {{ $patients->total() }} result(s)</p>
                    @else
                        <h5 style="margin: 0; font-size: 1.1rem; font-weight: 700; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);"><i class="fas fa-list" style="margin-right: 8px;"></i>All Patients</h5>
                        <p style="margin: 6px 0 0 0; font-size: 0.9rem; opacity: 0.9;">Total: {{ $patients->total() }} patient(s)</p>
                    @endif
                </div>
                <div style="background: rgba(255, 255, 255, 0.2); padding: 8px 16px; border-radius: 8px; font-size: 0.9rem; font-weight: 600;">
                    📊 Page {{ $patients->currentPage() }} of {{ $patients->lastPage() }}
                </div>
            </div>
        </div>
        <div class="card-body" style="padding: 0; max-height: calc(100vh - 250px); overflow-y: auto;">
            @if($patients->count() > 0)
                <table class="table table-hover table-sm" style="font-size: 0.85rem; margin-bottom: 0; border-collapse: collapse;">
                        <tr style="background-color: #f8f9fa; font-weight: 700;">
                            <th style="width: 4%; padding: 12px 10px; border-bottom: 2px solid #667eea; font-weight: 700; color: #333;">ID</th>
                            <th style="width: 11%; padding: 12px 10px; border-bottom: 2px solid #667eea; font-weight: 700; color: #333;">Name</th>
                            <th style="width: 13%; padding: 12px 10px; border-bottom: 2px solid #667eea; font-weight: 700; color: #333;">Email</th>
                            <th style="width: 8%; padding: 12px 10px; border-bottom: 2px solid #667eea; font-weight: 700; color: #333;">Phone</th>
                            <th style="width: 5%; padding: 12px 10px; border-bottom: 2px solid #667eea; font-weight: 700; color: #333;">Gender</th>
                            <th style="width: 5%; padding: 12px 10px; border-bottom: 2px solid #667eea; font-weight: 700; color: #333;">Blood</th>
                            <th style="width: 6%; padding: 12px 10px; border-bottom: 2px solid #667eea; font-weight: 700; color: #333; text-align: center;">Appointments</th>
                            <th style="width: 6%; padding: 12px 10px; border-bottom: 2px solid #667eea; font-weight: 700; color: #333; text-align: center;">Reports</th>
                            <th style="width: 5%; padding: 12px 10px; border-bottom: 2px solid #667eea; font-weight: 700; color: #333; text-align: center;">Prescriptions</th>
                            <th style="width: 9%; padding: 12px 10px; border-bottom: 2px solid #667eea; font-weight: 700; color: #333;">Registered</th>
                            <th style="width: 16%; padding: 12px 10px; border-bottom: 2px solid #667eea; font-weight: 700; color: #333;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $patient)
                            <tr style="border-bottom: 1px solid #f0f0f0; height: 44px; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='#f8f9fa'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.05)';" onmouseout="this.style.backgroundColor='transparent'; this.style.boxShadow='none';">
                                <td style="padding: 10px; font-weight: 600; color: #667eea;">#{{ $patient->id }}</td>
                                <td style="padding: 10px; font-weight: 500; color: #333; max-width: 120px; overflow: hidden; text-overflow: ellipsis;">{{ $patient->name }}</td>
                                <td style="padding: 10px; color: #666; font-size: 0.85rem; max-width: 150px; overflow: hidden; text-overflow: ellipsis;">{{ $patient->email }}</td>
                                <td style="padding: 10px; color: #666;">{{ $patient->phone ?? '-' }}</td>
                                <td style="padding: 10px;">
                                    @if($patient->gender)
                                        <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 0.7rem; padding: 4px 8px;">{{ substr($patient->gender, 0, 1) }}</span>
                                    @else
                                        <span style="font-size: 0.8rem; color: #999;">-</span>
                                    @endif
                                </td>
                                <td style="padding: 10px;">
                                    @if($patient->blood_group)
                                        <span class="badge" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); font-size: 0.7rem; padding: 4px 8px;">{{ $patient->blood_group }}</span>
                                    @else
                                        <span style="font-size: 0.8rem; color: #999;">-</span>
                                    @endif
                                </td>
                                <td style="padding: 10px; text-align: center;">
                                    <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 0.7rem; padding: 4px 8px;">{{ $patient->appointments_count ?? 0 }}</span>
                                </td>
                                <td style="padding: 10px; text-align: center;">
                                    <span class="badge" style="background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%); font-size: 0.7rem; padding: 4px 8px; color: #fff;">{{ $patient->medical_reports_count ?? 0 }}</span>
                                </td>
                                <td style="padding: 10px; text-align: center;">
                                    <span class="badge" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); font-size: 0.7rem; padding: 4px 8px;">{{ $patient->prescriptions_count ?? 0 }}</span>
                                </td>
                                <td style="padding: 10px; color: #666; font-size: 0.85rem;">{{ $patient->created_at->format('d M Y') }}</td>
                                <td style="padding: 10px;">
                                    <div style="display: flex; gap: 4px;">
                                        <a href="{{ route('admin.patients.show', $patient->id) }}" class="btn btn-sm" style="padding: 4px 8px; font-size: 0.7rem; background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white; border: none; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 3px; transition: all 0.2s ease;" title="View" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(23, 162, 184, 0.3)';" onmouseout="this.style.transform='none'; this.style.boxShadow='none';">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <button type="button" class="btn btn-sm" style="padding: 4px 8px; font-size: 0.7rem; background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; border: none; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 3px; transition: all 0.2s ease;" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $patient->id }}" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(220, 53, 69, 0.3)';" onmouseout="this.style.transform='none'; this.style.boxShadow='none';">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>

                                    <!-- Delete Confirmation Modal -->
                                    <div class="modal fade" id="deleteModal{{ $patient->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <form method="POST" action="{{ route('admin.patients.destroy', $patient->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);">
                                                    <div class="modal-header" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; border: none; padding: 16px;">
                                                        <h6 class="modal-title" style="margin: 0; font-weight: 700;"><i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>Delete Patient</h6>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="padding: 0;"></button>
                                                    </div>
                                                    <div class="modal-body" style="padding: 16px; font-size: 0.95rem;">
                                                        <p style="margin: 0; color: #333;">Are you sure you want to delete <strong style="color: #dc3545;">{{ $patient->name }}</strong>? This action cannot be undone.</p>
                                                    </div>
                                                    <div class="modal-footer" style="padding: 12px 16px; background: #f8f9fa; border: none;">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    </table>
            @endif
        </div>
    </div>
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table thead th {
        background-color: #f8f9fa !important;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #dee2e6;
        white-space: nowrap;
    }
    
    .table tbody td {
        vertical-align: middle;
        border-bottom: 1px solid #dee2e6;
    }
    
    .btn-sm {
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection
