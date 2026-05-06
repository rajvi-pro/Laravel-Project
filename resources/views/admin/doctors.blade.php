@extends('admin-layout')

@section('title', 'Manage Doctors')

@section('sidebar')
<nav>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('admin.patients.index') }}"><i class="bi bi-people"></i> Patients</a>
    <a href="{{ route('admin.doctors.index') }}" class="active"><i class="bi bi-person-check"></i> Doctors</a>
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
    <div class="page-header">
        <h1><i class="fas fa-stethoscope"></i> Manage Doctors</h1>
        <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Doctor
        </a>
    </div>

    <div class="table-container">
        @if(!empty($doctors) && count($doctors) > 0)
            <div class="table-header"><i class="fas fa-list"></i> All Doctors</div>
            <div style="overflow-x: auto;">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th><i class="fas fa-id-card"></i> ID</th>
                            <th><i class="fas fa-user"></i> Name</th>
                            <th><i class="fas fa-envelope"></i> Email</th>
                            <th><i class="fas fa-phone"></i> Phone</th>
                            <th><i class="fas fa-certificate"></i> Specialization</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($doctors as $doctor)
                            <tr>
                                <td><strong>{{ $doctor->id }}</strong></td>
                                <td>{{ $doctor->name }}</td>
                                <td>{{ $doctor->email }}</td>
                                <td>{{ $doctor->phone ?? 'N/A' }}</td>
                                <td>{{ $doctor->specialization ?? 'N/A' }}</td>
                                <td style="white-space: nowrap;">
                                    <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-stethoscope"></i>
                </div>
                <p style="margin: 0;">No doctors found</p>
            </div>
        @endif
    </div>
</div>
@endsection
