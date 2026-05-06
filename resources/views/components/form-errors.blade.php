@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-left: 4px solid #dc3545; margin-bottom: 20px;">
        <strong><i class="fas fa-exclamation-circle"></i> Validation Error!</strong>
        <hr>
        <ul style="margin-bottom: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li style="margin-bottom: 8px;">{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
