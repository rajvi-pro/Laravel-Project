@extends('admin-layout')

@section('title', 'Doctor Management')

@section('sidebar')
<nav>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('admin.patients.index') }}"><i class="bi bi-people"></i> Patients</a>
    <a href="{{ route('admin.doctors.index') }}" class="active"><i class="bi bi-person-check"></i> Doctors</a>
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
    <!-- Premium Attractive Header Bar with Blue Gradient & Stats -->
    <div style="background: linear-gradient(135deg, #0d6efd 0%, #0a4a9f 50%, #0c3fa8 100%); border-radius: 16px; padding: 32px; margin-bottom: 32px; box-shadow: 0 8px 32px rgba(13, 110, 253, 0.2), 0 2px 8px rgba(13, 110, 253, 0.15); position: relative; overflow: hidden;">
        <!-- Animated Background Elements -->
        <div style="position: absolute; top: -50%; right: -10%; width: 300px; height: 300px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; filter: blur(40px);"></div>
        <div style="position: absolute; bottom: -30%; left: -5%; width: 250px; height: 250px; background: rgba(255, 255, 255, 0.08); border-radius: 50%; filter: blur(40px);"></div>
        
        <div class="d-flex justify-content-between align-items-center mb-4" style="position: relative; z-index: 1;">
            <div>
                <h5 style="color: rgba(255, 255, 255, 0.85); font-weight: 600; margin-bottom: 8px; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">👨‍⚕️ Healthcare Providers</h5>
                <h1 style="color: #ffffff; font-weight: 800; font-size: 2.5rem; margin: 0; text-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);">Doctor Management</h1>
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
                    <i class="fas fa-user-md" style="color: rgba(255, 255, 255, 0.8); font-size: 1.8rem; margin-bottom: 8px;"></i>
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.75rem; font-weight: 600; margin: 8px 0 0 0; text-transform: uppercase; letter-spacing: 0.4px;">TOTAL DOCTORS</p>
                    <h2 style="color: #ffffff; font-weight: 800; margin: 10px 0 0 0; font-size: 2.2rem; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">{{ $doctors->total() ?? 0 }}</h2>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div style="padding: 20px; background: rgba(255, 255, 255, 0.15); border-radius: 12px; text-align: center; backdrop-filter: blur(10px); border: 1.5px solid rgba(255, 255, 255, 0.25); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-check-circle" style="color: rgba(255, 255, 255, 0.8); font-size: 1.8rem; margin-bottom: 8px;"></i>
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.75rem; font-weight: 600; margin: 8px 0 0 0; text-transform: uppercase; letter-spacing: 0.4px;">AVAILABLE TODAY</p>
                    <h2 style="color: #ffffff; font-weight: 800; margin: 10px 0 0 0; font-size: 2.2rem; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                        @php
                            $available = 0;
                            foreach($doctors as $d) {
                                if($d->is_available_today) $available++;
                            }
                            echo $available;
                        @endphp
                    </h2>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div style="padding: 20px; background: rgba(255, 255, 255, 0.15); border-radius: 12px; text-align: center; backdrop-filter: blur(10px); border: 1.5px solid rgba(255, 255, 255, 0.25); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-star" style="color: rgba(255, 255, 255, 0.8); font-size: 1.8rem; margin-bottom: 8px;"></i>
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.75rem; font-weight: 600; margin: 8px 0 0 0; text-transform: uppercase; letter-spacing: 0.4px;">SPECIALIZATIONS</p>
                    <h2 style="color: #ffffff; font-weight: 800; margin: 10px 0 0 0; font-size: 2.2rem; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">{{ $doctors->pluck('specialization')->unique()->count() ?? 0 }}</h2>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div style="padding: 20px; background: rgba(255, 255, 255, 0.15); border-radius: 12px; text-align: center; backdrop-filter: blur(10px); border: 1.5px solid rgba(255, 255, 255, 0.25); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-calendar" style="color: rgba(255, 255, 255, 0.8); font-size: 1.8rem; margin-bottom: 8px;"></i>
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.75rem; font-weight: 600; margin: 8px 0 0 0; text-transform: uppercase; letter-spacing: 0.4px;">TOTAL APPOINTMENTS</p>
                    <h2 style="color: #ffffff; font-weight: 800; margin: 10px 0 0 0; font-size: 2.2rem; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                        @php
                            $totalAppts = 0;
                            foreach($doctors as $d) {
                                $totalAppts += $d->appointments_count ?? 0;
                            }
                            echo $totalAppts;
                        @endphp
                    </h2>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="d-flex justify-content-between align-items-center mt-4" style="position: relative; z-index: 1;">
            <div></div>
            <a href="{{ route('admin.doctors.create') }}" class="btn btn-light" style="font-weight: 700; padding: 12px 28px; font-size: 1rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); transition: all 0.3s ease; border: none;">
                <i class="fas fa-plus" style="margin-right: 8px;"></i> Register New Doctor
            </a>
        </div>
    </div>

    <!-- Doctors Table -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">All Doctors ({{ $doctors->total() }})</h5>
        </div>
        <div class="card-body">
            @if($doctors->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Specialization</th>
                                <th>Experience</th>
                                <th>Fee</th>
                                <th>Appointments</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($doctors as $doctor)
                                <tr>
                                    <td>#{{ $doctor->id }}</td>
                                    <td>
                                        <strong>{{ $doctor->name }}</strong>
                                    </td>
                                    <td>{{ $doctor->email }}</td>
                                    <td>{{ $doctor->phone }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $doctor->specialization }}</span>
                                    </td>
                                    <td>{{ $doctor->experience_years }} years</td>
                                    <td>₹{{ number_format($doctor->consultation_fee ?? 0, 2) }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $doctor->appointments_count ?? 0 }}</span>
                                    </td>
                                    <td>
                                        @if($doctor->is_available_today)
                                            <span class="badge bg-success">✅ Available</span>
                                        @else
                                            <span class="badge bg-danger">❌ Unavailable</span>
                                            @if($doctor->unavailable_message)
                                                <br><small class="text-muted">{{ $doctor->unavailable_message }}</small>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="d-flex gap-1">
                                        <a href="{{ route('admin.doctors.show', $doctor->id) }}" class="btn btn-info btn-sm" title="View Details">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" title="Delete Doctor" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $doctor->id }}">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>

                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade" id="deleteModal{{ $doctor->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form method="POST" action="{{ route('admin.doctors.destroy', $doctor->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to delete <strong>{{ $doctor->name }}</strong>?</p>
                                                            <p class="text-muted small">This action cannot be undone.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Delete Doctor</button>
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
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $doctors->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No doctors found. <a href="{{ route('admin.doctors.create') }}">Register a new doctor</a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>

<script>
    // Ensure delete forms submit properly
    document.querySelectorAll('[id^="deleteForm"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            console.log('Form submitted:', this.action);
        });
    });
</script>
@endsection
