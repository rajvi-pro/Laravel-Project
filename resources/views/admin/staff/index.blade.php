@extends('admin-layout')

@section('title', 'Staff Management')

@section('sidebar')
<nav>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('admin.patients.index') }}"><i class="bi bi-people"></i> Patients</a>
    <a href="{{ route('admin.doctors.index') }}"><i class="bi bi-person-check"></i> Doctors</a>
    <a href="{{ route('admin.staff.index') }}" class="active"><i class="bi bi-person-badge"></i> Staff</a>
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
                <h5 style="color: rgba(255, 255, 255, 0.85); font-weight: 600; margin-bottom: 8px; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">👥 Team Management</h5>
                <h1 style="color: #ffffff; font-weight: 800; font-size: 2.5rem; margin: 0; text-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);">Staff Management</h1>
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
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.75rem; font-weight: 600; margin: 8px 0 0 0; text-transform: uppercase; letter-spacing: 0.4px;">TOTAL STAFF</p>
                    <h2 style="color: #ffffff; font-weight: 800; margin: 10px 0 0 0; font-size: 2.2rem; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">{{ $staff->total() ?? 0 }}</h2>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div style="padding: 20px; background: rgba(255, 255, 255, 0.15); border-radius: 12px; text-align: center; backdrop-filter: blur(10px); border: 1.5px solid rgba(255, 255, 255, 0.25); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-briefcase" style="color: rgba(255, 255, 255, 0.8); font-size: 1.8rem; margin-bottom: 8px;"></i>
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.75rem; font-weight: 600; margin: 8px 0 0 0; text-transform: uppercase; letter-spacing: 0.4px;">DEPARTMENTS</p>
                    <h2 style="color: #ffffff; font-weight: 800; margin: 10px 0 0 0; font-size: 2.2rem; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">{{ $staff->pluck('department')->unique()->count() ?? 0 }}</h2>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div style="padding: 20px; background: rgba(255, 255, 255, 0.15); border-radius: 12px; text-align: center; backdrop-filter: blur(10px); border: 1.5px solid rgba(255, 255, 255, 0.25); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-id-badge" style="color: rgba(255, 255, 255, 0.8); font-size: 1.8rem; margin-bottom: 8px;"></i>
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.75rem; font-weight: 600; margin: 8px 0 0 0; text-transform: uppercase; letter-spacing: 0.4px;">ROLES</p>
                    <h2 style="color: #ffffff; font-weight: 800; margin: 10px 0 0 0; font-size: 2.2rem; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">{{ $staff->pluck('role')->unique()->count() ?? 0 }}</h2>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div style="padding: 20px; background: rgba(255, 255, 255, 0.15); border-radius: 12px; text-align: center; backdrop-filter: blur(10px); border: 1.5px solid rgba(255, 255, 255, 0.25); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-calendar-plus" style="color: rgba(255, 255, 255, 0.8); font-size: 1.8rem; margin-bottom: 8px;"></i>
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.75rem; font-weight: 600; margin: 8px 0 0 0; text-transform: uppercase; letter-spacing: 0.4px;">THIS MONTH</p>
                    <h2 style="color: #ffffff; font-weight: 800; margin: 10px 0 0 0; font-size: 2.2rem; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                        @php
                            $thisMonth = 0;
                            foreach($staff as $s) {
                                if($s->created_at?->isCurrentMonth()) $thisMonth++;
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
            <a href="{{ route('admin.staff.create') }}" class="btn btn-light" style="font-weight: 700; padding: 12px 28px; font-size: 1rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); transition: all 0.3s ease; border: none;">
                <i class="fas fa-plus" style="margin-right: 8px;"></i> Add New Staff
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">All Staff ({{ $staff->total() }})</h5>
        </div>
        <div class="card-body">
            @if($staff->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Department</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staff as $member)
                                <tr>
                                    <td>#{{ $member->id }}</td>
                                    <td><strong>{{ $member->name }}</strong></td>
                                    <td>{{ $member->email }}</td>
                                    <td>{{ $member->phone }}</td>
                                    <td><span class="badge bg-info">{{ $member->role }}</span></td>
                                    <td><span class="badge bg-secondary">{{ $member->department }}</span></td>
                                    <td>{{ $member->created_at->format('d M Y') }}</td>
                                    <td class="d-flex gap-1">
                                        <a href="{{ route('admin.staff.edit', $member->id) }}" class="btn btn-primary btn-sm" title="Edit">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" title="Delete Staff" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $member->id }}">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>

                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade" id="deleteModal{{ $member->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form method="POST" action="{{ route('admin.staff.destroy', $member->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to delete <strong>{{ $member->name }}</strong>?</p>
                                                            <p class="text-muted small">This action cannot be undone.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Delete Staff</button>
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

                <div class="d-flex justify-content-center mt-4">
                    {{ $staff->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No staff found. <a href="{{ route('admin.staff.create') }}">Add a new staff member</a>
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
@endsection
