# Form Validation Documentation

## Doctor Panel

### 1. Prescription Creation
**Route:** `doctor.prescriptions.create`
**Form Request:** `CreatePrescriptionRequest`
**Validation Rules:**
- patient_id: required|exists:patients,id
- medicine_name: required|string|max:255
- dosage: required|string|max:255
- frequency: required|string|max:255
- duration: required|string|max:255
- instructions: nullable|string

**Validation Messages:** Custom error messages included

---

### 2. Medical Report Creation
**Route:** `doctor.medical-reports.create`
**Form Request:** `CreateMedicalReportRequest`
**Validation Rules:**
- patient_id: required|exists:patients,id|integer
- diagnosis: required|string|min:5|max:255
- symptoms: required|string|min:5|max:2000
- treatment: required|string|min:5|max:2000
- notes: nullable|string|max:2000

**Validation Messages:** Custom error messages included

---

### 3. Lab Result Creation
**Route:** `doctor.lab-results.create`
**Form Request:** `CreateLabResultRequest`
**Validation Rules:**
- patient_id: required|exists:patients,id|integer
- test_name: required|string|min:3|max:255
- result: required|string|min:3|max:2000
- normal_range: nullable|string|max:255
- status: required|in:pending,completed
- notes: nullable|string|max:2000

**Validation Messages:** Custom error messages included

---

## Admin Panel

### 4. Doctor Creation
**Route:** `admin.doctors.store`
**Form Request:** `CreateDoctorRequest`
**Validation Rules:**
- name: required|string|min:3|max:255
- email: required|email|unique:doctors,email
- phone: required|string|regex:/^[0-9\s\-\+\(\)]+$/|min:10|max:20
- specialization: required|string|min:3|max:255
- qualification: required|string|min:3|max:255
- experience_years: required|integer|min:0|max:70
- consultation_fee: nullable|numeric|min:0|max:999999
- password: required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/

**Password Requirements:** 
- Minimum 8 characters
- Must contain at least one uppercase letter
- Must contain at least one lowercase letter
- Must contain at least one digit

---

### 5. Doctor Update
**Route:** `admin.doctors.update`
**Form Request:** `UpdateDoctorRequest`
**Validation Rules:** Same as creation but password is removed (optional field not updated)

---

### 6. Staff Creation
**Route:** `admin.staff.store`
**Form Request:** `CreateStaffRequest`
**Validation Rules:**
- name: required|string|min:3|max:255
- email: required|email|unique:staff,email
- phone: required|string|regex:/^[0-9\s\-\+\(\)]+$/|min:10|max:20
- role: required|string|min:3|max:255
- department: required|string|min:3|max:255
- password: required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/

**Password Requirements:** Same as Doctor creation

---

### 7. Staff Update
**Route:** `admin.staff.update`
**Form Request:** `UpdateStaffRequest`
**Validation Rules:** Same as creation but password is removed

---

## Patient Panel

### 8. Patient Appointment Creation
**Route:** `patient.appointments.create`
**Form Request:** `PatientAppointmentRequest`
**Validation Rules:**
- doctor_id: required|exists:doctors,id|integer
- appointment_date: required|date|after_or_equal:today
- appointment_time: required|date_format:H:i
- reason: nullable|string|max:500

**Validation Messages:** Custom error messages included

---

### 9. Patient Profile Update
**Route:** `patient.profile.update`
**Form Request:** `UpdatePatientRequest`
**Validation Rules:**
- name: required|string|min:3|max:255
- email: required|email|unique:patients,email,(id)
- phone: required|string|regex:/^[0-9\s\-\+\(\)]+$/|min:10|max:20
- date_of_birth: required|date|before:today
- gender: required|in:Male,Female,Other
- address: required|string|min:5|max:500
- city: nullable|string|max:100
- state: nullable|string|max:100

---

## Staff Panel

### 10. Patient History Creation
**Route:** `staff.patient.history.store`
**Form Request:** `CreateStaffPatientHistoryRequest`
**Validation Rules:**
- report_date: required|date|before_or_equal:today
- diagnosis: required|string|min:5|max:1000
- symptoms: nullable|string|min:3|max:2000
- treatment: nullable|string|min:3|max:2000
- notes: nullable|string|max:2000

---

### 11. Staff Appointment Creation
**Route:** `staff.appointments.store`
**Form Request:** `CreateAppointmentRequest`
**Validation Rules:**
- patient_id: required|exists:patients,id|integer
- doctor_id: required|exists:doctors,id|integer
- appointment_date: required|date
- appointment_time: required|date_format:H:i
- reason: nullable|string|max:500

---

### 12. Staff Appointment Update
**Route:** `staff.appointments.update`
**Form Request:** `UpdateAppointmentRequest`
**Validation Rules:** Same as Staff Appointment Creation

---

## Frontend Implementation

All forms should display validation errors using:

```blade
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Validation Errors:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
```

For field-specific errors:

```blade
<div class="form-group">
    <label for="field_name">Field Name</label>
    <input type="text" class="form-control @error('field_name') is-invalid @enderror" 
           name="field_name" value="{{ old('field_name') }}">
    @error('field_name')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>
```

---

## General Validation Rules Applied Across All Forms

1. **Email:** Must be valid email format and unique for each user type
2. **Phone:** Must contain 10-20 characters with valid phone format
3. **Dates:** Must be valid dates, with future/past constraints as applicable
4. **Passwords:** 
   - Minimum 8 characters
   - Must contain uppercase, lowercase, and digit
5. **Required Fields:** Clearly marked as required
6. **Character Limits:** Client-side max attributes + server-side max rules

---

## Custom Error Messages

All Form Requests include custom, user-friendly error messages that replace the default Laravel messages. These messages are tailored to each specific field and scenario.

---

## Migration Guide

To update existing forms to use validation:

1. Ensure the corresponding Form Request class exists
2. Update the controller method parameter from `Request $request` to the specific `FormRequest` class
3. Replace `$request->validate([...])` with `$request->validated()`
4. Ensure the import statement is added to the controller
5. Test the form thoroughly
