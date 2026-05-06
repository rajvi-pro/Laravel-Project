@extends('admin-layout')

@section('title', 'Manage Patients')

@section('sidebar')
<nav>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('admin.patients.index') }}" class="active"><i class="bi bi-people"></i> Patients</a>
    <a href="{{ route('admin.doctors.index') }}"><i class="bi bi-person-check"></i> Doctors</a>
    <a href="{{ route('admin.staff.index') }}"><i class="bi bi-person-badge"></i> Staff</a>
    <hr class="bg-light">
    <form method="POST" action="{{ route('admin.logout') }}">
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
                    <h2 style="color: #ffffff; font-weight: 800; margin: 10px 0 0 0; font-size: 2.2rem; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">{{ count($patients ?? []) }}</h2>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div style="padding: 20px; background: rgba(255, 255, 255, 0.15); border-radius: 12px; text-align: center; backdrop-filter: blur(10px); border: 1.5px solid rgba(255, 255, 255, 0.25); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-user-check" style="color: rgba(255, 255, 255, 0.8); font-size: 1.8rem; margin-bottom: 8px;"></i>
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.75rem; font-weight: 600; margin: 8px 0 0 0; text-transform: uppercase; letter-spacing: 0.4px;">ACTIVE TODAY</p>
                    <h2 style="color: #ffffff; font-weight: 800; margin: 10px 0 0 0; font-size: 2.2rem; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">{{ count(array_filter($patients ?? [], fn($p) => $p->created_at?->isToday())) }}</h2>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div style="padding: 20px; background: rgba(255, 255, 255, 0.15); border-radius: 12px; text-align: center; backdrop-filter: blur(10px); border: 1.5px solid rgba(255, 255, 255, 0.25); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-envelope" style="color: rgba(255, 255, 255, 0.8); font-size: 1.8rem; margin-bottom: 8px;"></i>
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.75rem; font-weight: 600; margin: 8px 0 0 0; text-transform: uppercase; letter-spacing: 0.4px;">VERIFIED EMAIL</p>
                    <h2 style="color: #ffffff; font-weight: 800; margin: 10px 0 0 0; font-size: 2.2rem; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">{{ count(array_filter($patients ?? [], fn($p) => $p->email_verified_at)) }}</h2>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div style="padding: 20px; background: rgba(255, 255, 255, 0.15); border-radius: 12px; text-align: center; backdrop-filter: blur(10px); border: 1.5px solid rgba(255, 255, 255, 0.25); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-calendar-check" style="color: rgba(255, 255, 255, 0.8); font-size: 1.8rem; margin-bottom: 8px;"></i>
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.75rem; font-weight: 600; margin: 8px 0 0 0; text-transform: uppercase; letter-spacing: 0.4px;">REGISTERED THIS MONTH</p>
                    <h2 style="color: #ffffff; font-weight: 800; margin: 10px 0 0 0; font-size: 2.2rem; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">{{ count(array_filter($patients ?? [], fn($p) => $p->created_at?->isCurrentMonth())) }}</h2>
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

    <div class="table-container">
        @if(!empty($patients) && count($patients) > 0)
            <div class="table-header"><i class="fas fa-list"></i> All Patients</div>
            <div style="overflow-x: auto;">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th><i class="fas fa-id-card"></i> ID</th>
                            <th><i class="fas fa-user"></i> Name</th>
                            <th><i class="fas fa-envelope"></i> Email</th>
                            <th><i class="fas fa-phone"></i> Phone</th>
                            <th><i class="fas fa-birthday-cake"></i> Date of Birth</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $patient)
                            <tr>
                                <td><strong>{{ $patient->id }}</strong></td>
                                <td>{{ $patient->name }}</td>
                                <td>{{ $patient->email }}</td>
                                <td>{{ $patient->phone ?? 'N/A' }}</td>
                                <td>{{ $patient->date_of_birth ?? 'N/A' }}</td>
                                <td style="white-space: nowrap;">
                                    <a href="{{ route('admin.patients.show', $patient->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('admin.patients.edit', $patient->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-users"></i>
                </div>
                <p style="margin: 0;">No patients found</p>
            </div>
        @endif
    </div>
</div>
@endsection
