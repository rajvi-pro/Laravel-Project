@extends('admin-layout')

@section('title', 'Manage Staff')

@section('sidebar')
<nav>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('admin.patients.index') }}"><i class="bi bi-people"></i> Patients</a>
    <a href="{{ route('admin.doctors.index') }}"><i class="bi bi-person-check"></i> Doctors</a>
    <a href="{{ route('admin.staff.index') }}" class="active"><i class="bi bi-person-badge"></i> Staff</a>
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
        <h1><i class="fas fa-people-carry"></i> Manage Staff</h1>
        <a href="{{ route('admin.staff.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Staff
        </a>
    </div>

    <div class="table-container">
        @if(!empty($staff) && count($staff) > 0)
            <div class="table-header"><i class="fas fa-list"></i> All Staff Members</div>
            <div style="overflow-x: auto;">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th><i class="fas fa-id-card"></i> ID</th>
                            <th><i class="fas fa-user"></i> Name</th>
                            <th><i class="fas fa-envelope"></i> Email</th>
                            <th><i class="fas fa-phone"></i> Phone</th>
                            <th><i class="fas fa-briefcase"></i> Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($staff as $staffMember)
                            <tr>
                                <td><strong>{{ $staffMember->id }}</strong></td>
                                <td>{{ $staffMember->name }}</td>
                                <td>{{ $staffMember->email }}</td>
                                <td>{{ $staffMember->phone ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-success">{{ $staffMember->role ?? 'N/A' }}</span>
                                </td>
                                <td style="white-space: nowrap;">
                                    <a href="{{ route('admin.staff.edit', $staffMember->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.staff.destroy', $staffMember->id) }}" method="POST" style="display:inline;">
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
                    <i class="fas fa-people-carry"></i>
                </div>
                <p style="margin: 0;">No staff found</p>
            </div>
        @endif
    </div>
</div>
@endsection
