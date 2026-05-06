# Appointment-Based Billing Feature - Setup & Usage Guide

## Quick Start

### Step 1: Run Database Migrations

The new `bill_items` table needs to be created:

```bash
php artisan migrate
```

This will create the `bill_items` table with proper relationships.

### Step 2: Access the Billing Feature

**For Staff:**
1. Login to Staff Portal
2. Go to "Billing Management → Billings"
3. Click "New Billing (Appointment)" button (NEW)
4. OR Continue using "New Billing (Patient)" for legacy flow

### Step 3: Create Your First Bill

**Step-by-Step:**
1. **Select Appointment**: Choose a completed appointment
2. **Review Info**: Patient and doctor details auto-load
3. **Choose Items**: 
   - Consultation fee (auto-included if > 0)
   - Lab tests (click to select)
   - Extra charges (optional)
4. **Review Totals**: See real-time calculations
5. **Submit**: Click "Create Billing"

## Features Overview

### Real-Time Features
- ✅ Appointment details load via AJAX
- ✅ Consultation fee auto-fetched from doctor record
- ✅ Lab reports auto-fetched and displayed
- ✅ Total calculation updates in real-time
- ✅ Tax calculation (CGST 9% + SGST 9%)

### Smart Validation
- ✅ Only completed appointments shown
- ✅ Prevents duplicate bills per appointment
- ✅ Validates all amounts are numeric and >= 0
- ✅ Lab items must be "completed" status
- ✅ CSRF protection on all forms

### Database Structure
```
Billings Table (existing)
├── appointment_id (FK)
├── patient_id (FK)
├── staff_id (FK)
├── amount (total)
├── subtotal
├── cgst
├── sgst
└── total_tax

Bill_Items Table (new)
├── billing_id (FK → billings)
├── type (consultation, lab_test, extra_charge)
├── description
├── amount
├── quantity
└── total
```

## API Endpoints

### Get Appointment Details (AJAX)
```http
GET /staff/billing/appointment/{appointmentId}/details
```

**Response:**
```json
{
  "success": true,
  "appointment_id": 1,
  "patient": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1-234-567-8900"
  },
  "doctor": {
    "id": 1,
    "name": "Dr. Smith",
    "specialization": "Cardiology"
  },
  "consultation_fee": 500.00,
  "lab_reports": [
    {
      "id": 1,
      "test_name": "Complete Blood Count",
      "result": "Normal",
      "charge": 300.00
    }
  ]
}
```

### Create Billing from Appointment
```http
POST /staff/billing/create-from-appointment
Content-Type: application/json

{
  "appointment_id": 1,
  "lab_items": [
    {"id": 1, "charge": 300.00},
    {"id": 2, "charge": 500.00}
  ],
  "extra_charges": 100.00,
  "extra_charges_description": "Administration Fee",
  "payment_status": "pending",
  "notes": "Follow-up bills pending"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Billing record created successfully with all items.",
  "billing_id": 1,
  "redirect": "/staff/billings/1"
}
```

## Calculation Example

### Input
- Appointment: #123 (Dr. Smith to Patient John Doe)
- Doctor Consultation Fee: ₹500
- Lab Test 1 (CBC): ₹300 (selected)
- Lab Test 2 (X-Ray): ₹400 (selected)
- Extra Charges: ₹100 (Administration Fee)

### Bill Items Created
1. **Consultation Fee - Dr. Smith**: ₹500
2. **Lab Test - Complete Blood Count**: ₹300
3. **Lab Test - X-Ray**: ₹400
4. **Additional Charges**: ₹100

### Automatic Calculation
```
Subtotal:           ₹1,300
CGST (9%):          ₹117
SGST (9%):          ₹117
─────────────────
Total Tax:          ₹234
═════════════════
FINAL TOTAL:        ₹1,534
```

### Database Entries
**Billings Table:**
```
id=1, appointment_id=123, patient_id=1, amount=1534, 
subtotal=1300, cgst=117, sgst=117, total_tax=234, 
payment_status='pending', staff_id=1
```

**Bill_Items Table:**
```
1: type=consultation, description=Consultation Fee - Dr. Smith, amount=500, total=500
2: type=lab_test, description=Lab Test - Complete Blood Count, amount=300, total=300
3: type=lab_test, description=Lab Test - X-Ray, amount=400, total=400
4: type=extra_charge, description=Administration Fee, amount=100, total=100
```

## Form Validation

### Client-Side (JavaScript)
- Appointment must be selected
- All amounts must be numeric
- Real-time calculation feedback

### Server-Side (Laravel Validation)
```php
'appointment_id' => 'required|integer|exists:appointments,id'
'lab_items.*.id' => 'integer|exists:lab_results,id'
'lab_items.*.charge' => 'numeric|min:0|max:999999.99'
'extra_charges' => 'numeric|min:0|max:999999.99'
'payment_status' => 'in:pending,paid'
'notes' => 'max:1000'
```

**Custom Validations:**
- Appointment must be "completed" status
- Appointment must not have existing billing
- Lab result must be "completed" status
- No negative amounts allowed

## Error Handling

### Common Errors & Solutions

**Error: "A billing record already exists for this appointment"**
- Solution: Each appointment can only have ONE billing
- Check if billing was already created
- Cannot create duplicate billings

**Error: "Invalid or incomplete lab result selected"**
- Solution: Lab tests must be marked as "completed"
- Staff needs to complete lab tests first
- Check lab test status in Lab Results

**Error: "All amounts must be numeric and >= 0"**
- Solution: Enter valid decimal numbers
- Negative charges not allowed
- Leave optional fields blank if not needed

**Error: "The selected appointment does not exist"**
- Solution: Refresh page and select valid appointment
- Ensure appointment is still active

## Security Features

✅ **CSRF Protection**
- All forms include @csrf token
- Invalid or missing tokens rejected

✅ **Staff Middleware**
- Only authenticated staff users can access
- Routes protected with staff middleware

✅ **Database Validation**
- Foreign key constraints enforced
- Cascade deletes on bill items

✅ **Input Sanitization**
- All inputs validated server-side
- HTML special characters escaped
- SQL injection prevention

## Testing the Feature

### Manual Testing Steps

**Test 1: Basic Billing Creation**
1. Go to Billings page
2. Click "New Billing (Appointment)"
3. Select first completed appointment
4. Verify patient/doctor info loads
5. Select at least one lab item
6. Click "Create Billing"
7. Verify success message and redirect

**Test 2: Duplicate Prevention**
1. Create a billing for appointment #5
2. Try creating another billing for same appointment #5
3. Verify error message appears

**Test 3: Calculation Accuracy**
1. Create billing with known values
2. Verify: Subtotal = Sum of all items
3. Verify: Tax = Subtotal × 0.18
4. Verify: Total = Subtotal + Tax

**Test 4: Form Validation**
1. Try submitting empty form → Error
2. Try entering negative charges → Error
3. Try entering non-numeric values → Error
4. Verify all validations work

**Test 5: Mobile Responsiveness**
1. Open form on mobile device
2. Verify layout is responsive
3. Test on tablet and desktop
4. Verify all buttons are clickable

## Database Notes

### Relationships
```php
Appointment → Billing → BillItem
Patient → Billing → BillItem
Doctor → Appointment → Billing
LabResult → BillItem (via appointment)
```

### Cascading Deletes
- Delete Billing → Deletes all BillItems
- Keep appointments intact for history

### Soft Deletes
- Bill records use soft deletes (SoftDeletes trait)
- bill_items also support soft deletes
- Allows recovery of deleted bills

## Performance Considerations

### Queries Used
- `Appointment::with(['patient', 'doctor'])` - Eager loading
- `LabResult::where('patient_id', ...)->get()` - Filtered results
- Single transaction for atomic operations

### Optimization Tips
- Lab results fetched only for selected patient
- Prescription data fetched but optional display
- AJAX calls only when appointment selected
- No N+1 queries (proper eager loading)

## Troubleshooting

### Issue: "404 Not Found" on Create Billing Page
**Solution:**
```bash
# Clear route cache
php artisan route:clear

# Verify route exists
php artisan route:list | grep "billing"
```

### Issue: AJAX calls fail silently
**Solution:**
1. Check browser console for errors (F12)
2. Verify CSRF token in HTML
3. Check network tab for actual errors
4. Enable debug mode in .env

### Issue: Calculations incorrect
**Solution:**
```php
// Verify tax rates in BillingService
const CGST_RATE = 0.09; // 9%
const SGST_RATE = 0.09; // 9%

// Check bill_items table has correct amounts
```

## Migration Reference

```bash
# Run new migration
php artisan migrate

# Rollback if needed
php artisan migrate:rollback

# Check migration status
php artisan migrate:status

# Fresh migration (CAUTION: Drops all tables)
php artisan migrate:fresh
```

## Key Files Modified/Created

- ✅ `app/Models/BillItem.php` - New model
- ✅ `app/Models/Billing.php` - Updated with relationship
- ✅ `app/Services/BillingService.php` - New service
- ✅ `app/Http/Controllers/Staff/StaffDashboardController.php` - +2 methods
- ✅ `resources/views/staff/create-billing.blade.php` - New view
- ✅ `resources/views/staff/billings.blade.php` - Updated buttons
- ✅ `routes/staff.php` - +3 new routes
- ✅ `database/migrations/2026_04_12_create_bill_items_table.php` - New migration
