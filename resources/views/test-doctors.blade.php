<!DOCTYPE html>
<html>
<head>
    <title>Doctor Database Test</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-5">
    <div class="container">
        <h1>Doctor Database Records</h1>
        <p>Total Doctors: <strong>{{ $count }}</strong></p>
        
        @if($count > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Specialization</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($doctors as $doctor)
                        <tr>
                            <td>{{ $doctor->id }}</td>
                            <td>{{ $doctor->name }}</td>
                            <td>{{ $doctor->email }}</td>
                            <td>{{ $doctor->specialization }}</td>
                            <td>{{ $doctor->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="alert alert-info mt-3">
                <strong>Test Login Credentials:</strong><br>
                Email: {{ $doctors[0]->email ?? 'N/A' }}<br>
                Password: password
            </div>
        @else
            <div class="alert alert-warning">No doctors in database. Run <code>php artisan db:seed --class=DoctorSeeder</code></div>
        @endif
        
        <a href="/doctor/login" class="btn btn-primary mt-3">Go to Doctor Login</a>
    </div>
</body>
</html>
