@extends('layouts.staff-layout')

@section('page-title', 'Add Medicine')
@section('title', 'Add New Medicine - Staff Portal')

@section('content')
    <div style="max-width: 900px; margin: 0 auto;">
        <div style="display: flex; gap: 10px; margin-bottom: 20px;">
            <a href="{{ route('staff.medicines') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <!-- Form Card -->
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-top: 4px solid #0d47a1;">
            <h4 style="margin-bottom: 25px; color: #1a1a1a; font-weight: 700;">
                <i class="fas fa-plus"></i> Add New Medicine
            </h4>

            @if($errors->any())
                <div style="background-color: #fee; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #e53935;">
                    <strong style="color: #e53935;">Validation Errors:</strong>
                    <ul style="margin: 10px 0 0 20px; padding-left: 0;">
                        @foreach($errors->all() as $error)
                            <li style="color: #e53935;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('staff.medicine.store') }}" method="POST">
                @csrf

                <!-- Row 1: Medicine Name & Generic Name -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label for="name" class="form-label" style="font-weight: 600; color: #1a1a1a; margin-bottom: 8px; display: block;">
                            Medicine Name<span style="color: #e53935;"> *</span>
                        </label>
                        <select class="form-control @error('name') is-invalid @enderror" 
                                name="name"
                                required
                                style="padding: 10px 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
                            <option value="">-- Select Medicine --</option>
                            @foreach($medicineNames as $med)
                                <option value="{{ $med }}" {{ old('name') == $med ? 'selected' : '' }}>{{ $med }}</option>
                            @endforeach
                        </select>
                        @error('name')
                            <div style="color: #e53935; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="generic_name" class="form-label" style="font-weight: 600; color: #1a1a1a; margin-bottom: 8px; display: block;">
                            Generic Name
                        </label>
                        <input type="text" class="form-control" 
                               name="generic_name" 
                               placeholder="e.g., Acetylsalicylic Acid"
                               value="{{ old('generic_name') }}"
                               style="padding: 10px 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
                    </div>
                </div>

                <!-- Row 2: Category & Manufacturer -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label for="category" class="form-label" style="font-weight: 600; color: #1a1a1a; margin-bottom: 8px; display: block;">
                            Category<span style="color: #e53935;"> *</span>
                        </label>
                        <select class="form-control @error('category') is-invalid @enderror" 
                                name="category"
                                required
                                style="padding: 10px 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <div style="color: #e53935; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="manufacturer" class="form-label" style="font-weight: 600; color: #1a1a1a; margin-bottom: 8px; display: block;">
                            Manufacturer<span style="color: #e53935;"> *</span>
                        </label>
                        <select class="form-control @error('manufacturer') is-invalid @enderror" 
                                name="manufacturer"
                                required
                                style="padding: 10px 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
                            <option value="">-- Select Manufacturer --</option>
                            @foreach($manufacturers as $mfg)
                                <option value="{{ $mfg }}" {{ old('manufacturer') == $mfg ? 'selected' : '' }}>{{ $mfg }}</option>
                            @endforeach
                        </select>
                        @error('manufacturer')
                            <div style="color: #e53935; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Row 3: Stock Quantity & Unit Price -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label for="stock_quantity" class="form-label" style="font-weight: 600; color: #1a1a1a; margin-bottom: 8px; display: block;">
                            Stock Quantity<span style="color: #e53935;"> *</span>
                        </label>
                        <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" 
                               name="stock_quantity" 
                               min="0" 
                               placeholder="0"
                               value="{{ old('stock_quantity') }}"
                               required
                               style="padding: 10px 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
                        @error('stock_quantity')
                            <div style="color: #e53935; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="unit_price" class="form-label" style="font-weight: 600; color: #1a1a1a; margin-bottom: 8px; display: block;">
                            Unit Price (₹)<span style="color: #e53935;"> *</span>
                        </label>
                        <input type="number" step="0.01" class="form-control @error('unit_price') is-invalid @enderror" 
                               name="unit_price" 
                               min="0" 
                               placeholder="0.00"
                               value="{{ old('unit_price') }}"
                               required
                               style="padding: 10px 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
                        @error('unit_price')
                            <div style="color: #e53935; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Row 4: Expiry Date & Description -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label for="expiry_date" class="form-label" style="font-weight: 600; color: #1a1a1a; margin-bottom: 8px; display: block;">
                            Expiry Date<span style="color: #e53935;"> *</span>
                        </label>
                        <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" 
                               name="expiry_date"
                               value="{{ old('expiry_date') }}"
                               required
                               style="padding: 10px 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
                        @error('expiry_date')
                            <div style="color: #e53935; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="description" class="form-label" style="font-weight: 600; color: #1a1a1a; margin-bottom: 8px; display: block;">
                            Description
                        </label>
                        <input type="text" class="form-control" 
                               name="description" 
                               placeholder="Optional description"
                               value="{{ old('description') }}"
                               style="padding: 10px 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
                    </div>
                </div>

                <!-- Form Actions -->
                <div style="display: flex; gap: 10px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                    <a href="{{ route('staff.medicines') }}" class="btn btn-secondary" style="padding: 10px 20px;">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">
                        <i class="fas fa-plus"></i> Add Medicine
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
