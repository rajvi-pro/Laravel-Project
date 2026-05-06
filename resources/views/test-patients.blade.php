<!DOCTYPE html>
<html>
<head>
    <title>Patient Database Test</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-5">
    <div class="container">
        <h1>Patient Database Records</h1>
        <p>Total Patients: <strong>{{ $count }}</strong></p>
        
        @if($count > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $patient)
                        <tr>
                            <td>{{ $patient->id }}</td>
                            <td>{{ $patient->name }}</td>
                            <td>{{ $patient->email }}</td>
                            <td>{{ $patient->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-warning">No patients in database. <a href="/patient/register">Register now</a></div>
        @endif
        
        <a href="/patient/login" class="btn btn-primary mt-3">Go to Login</a>
    </div>
</body>
</html>
