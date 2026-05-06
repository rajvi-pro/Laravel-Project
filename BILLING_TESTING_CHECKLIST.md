# Billing Feature - Testing Checklist

## Pre-Testing Setup

- [ ] Run migrations: `php artisan migrate`
- [ ] Clear cache: `php artisan cache:clear`
- [ ] Clear routes: `php artisan route:clear`
- [ ] Ensure staff user is logged in
- [ ] Have sample data: appointments, patients, doctors, lab results

## Database Tests

### Bill_Items Table
- [ ] Table exists in database
- [ ] Columns: id, billing_id, type, description, amount, quantity, total, timestamps, deleted_at
- [ ] Foreign key constraint on billing_id
- [ ] Soft deletes working (created_at, updated_at, deleted_at)

### Billing Model
- [ ] Model has billItems() relationship
- [ ] billItems() returns HasMany relationship
- [ ] Relationship works: `$billing->billItems`
- [ ] Eager loading: `Billing::with('billItems')`

### BillItem Model
- [ ] Model exists at `app/Models/BillItem.php`
- [ ] Has billing() relationship
- [ ] Fillable properties: billing_id, type, description, amount, quantity, total
- [ ] Type enum validates: consultation, lab_test, prescription, extra_charge

## Route Tests

### New Routes Registered
- [ ] GET `/staff/billings/create` → createBilling (view form)
- [ ] GET `/staff/billing/appointment/{id}/details` → getAppointmentDetails (AJAX)
- [ ] POST `/staff/billing/create-from-appointment` → createBillingFromAppointment (AJAX)

### Verify Routes
```bash
php artisan route:list | grep billing
```
- [ ] All 3 new routes appear in list
- [ ] Routes are protected by `staff` middleware
- [ ] Routes return correct HTTP methods

## UI/UX Tests

### Navigation
- [ ] "Billings" link in staff sidebar works
- [ ] "New Billing (Appointment)" button visible on billings page
- [ ] "New Billing (Patient)" button still visible (backward compatibility)
- [ ] Back button on create page returns to billings

### Create Billing Form
- [ ] Page loads without errors
- [ ] 5-step form sections visible
- [ ] Appointment dropdown shows completed appointments only
- [ ] No billings without existing billings appear

### Form Responsiveness
- [ ] Form works on desktop (1920x1080)
- [ ] Form works on tablet (768x1024)
- [ ] Form works on mobile (375x667)
- [ ] All buttons clickable on mobile
- [ ] Text readable on all devices

## Functionality Tests

### Step 1: Appointment Selection
- [ ] Dropdown shows only completed appointments
- [ ] Dropdown excludes appointments with existing billings
- [ ] Selecting appointment triggers AJAX call
- [ ] Loading state shows while fetching

### Step 2: Auto-Populated Data
- [ ] Patient name appears (readonly field)
- [ ] Patient ID appears (readonly field)
- [ ] Patient email appears (readonly field)
- [ ] Patient phone appears (readonly field)
- [ ] Doctor name appears (readonly field)
- [ ] Doctor specialization appears (readonly field)

### Step 3: Lab Items Display
- [ ] Lab reports appear as cards
- [ ] Each lab shows: test name, result, charge
- [ ] Lab items marked with "LAB TEST" badge
- [ ] No lab items shows "No lab reports available" message
- [ ] Clicking card toggles selection (visual feedback)
- [ ] Total updates when selecting/deselecting labs

### Consultation Fee
- [ ] Consultation fee fetches from doctor record
- [ ] Amount displays correctly formatted (₹X.XX)
- [ ] Shows as readonly field
- [ ] Included in calculations automatically

### Step 4: Extra Charges
- [ ] Extra charges field is optional
- [ ] Description field is optional
- [ ] Entering amount updates total immediately
- [ ] Negative amounts prevented (validation)
- [ ] Non-numeric values rejected (validation)

### Step 5: Summary & Totals
- [ ] Summary shows all selected items
- [ ] Each item shows in summary cards
- [ ] Subtotal calculated correctly
- [ ] CGST calculation correct (9%)
- [ ] SGST calculation correct (9%)
- [ ] Total tax = CGST + SGST
- [ ] Final total = Subtotal + Total Tax

### Calculation Verification
Create billing with:
- Consultation: ₹500
- Lab 1: ₹300
- Lab 2: ₹200
- Extra: ₹0

Expected:
- [ ] Subtotal: ₹1,000
- [ ] CGST: ₹90
- [ ] SGST: ₹90
- [ ] Total Tax: ₹180
- [ ] Final Total: ₹1,180

## Validation Tests

### Mandatory Fields
- [ ] Cannot submit without selecting appointment
- [ ] Error message appears for empty appointment
- [ ] Form scrolls to error field
- [ ] Error dismisses after 5 seconds (auto-dismiss)

### Numeric Validation
- [ ] Cannot enter letters in extra charges
- [ ] Cannot enter negative charges
- [ ] Decimal values accepted (.00, .50, etc.)
- [ ] Maximum 2 decimal places enforced

### Duplicate Prevention
- [ ] Cannot create billing for same appointment twice
- [ ] Error message: "A billing record already exists"
- [ ] Can create billing for different appointments

### Required Amounts
- [ ] All numeric fields must be >= 0
- [ ] Cannot submit with negative values
- [ ] Validation error shown with clear message

## AJAX Tests

### Appointment Details Endpoint
```bash
GET /staff/billing/appointment/1/details
```

- [ ] Returns 200 OK for valid appointment
- [ ] Returns 404 for invalid appointment ID
- [ ] Returns 422 if billing already exists
- [ ] Response includes patient object
- [ ] Response includes doctor object
- [ ] Response includes consultation_fee
- [ ] Response includes lab_reports array
- [ ] All numeric values are floats/decimals

### Create Billing Endpoint
```bash
POST /staff/billing/create-from-appointment
```

**Success Case:**
- [ ] Returns 201 Created
- [ ] Response has success: true
- [ ] Response includes billing_id
- [ ] Response includes redirect URL
- [ ] Redirects to billing detail page

**Validation Failure:**
- [ ] Returns 422 Unprocessable Entity
- [ ] Response has success: false
- [ ] Response includes error messages
- [ ] Form can be resubmitted after error

**Duplicate Prevention:**
- [ ] Returns 422 for duplicate appointment
- [ ] Message: "A billing record already exists"

## Database Persistence Tests

### Billing Record Created
- [ ] New record appears in billings table
- [ ] appointment_id is set correctly
- [ ] patient_id is set correctly
- [ ] staff_id is set correctly
- [ ] subtotal calculated correctly
- [ ] cgst calculated correctly (subtotal × 0.09)
- [ ] sgst calculated correctly (subtotal × 0.09)
- [ ] total_tax = cgst + sgst
- [ ] amount = subtotal + total_tax
- [ ] payment_status is "pending" or "paid" as selected
- [ ] billing_date is today's date

### Bill Items Created
For each selected item:
- [ ] Record appears in bill_items table
- [ ] billing_id matches created billing
- [ ] type matches item type (consultation, lab_test, extra_charge)
- [ ] description is accurate
- [ ] amount matches selected price
- [ ] quantity is 1
- [ ] total = amount × quantity

### Example - 3 Items
Creating billing with:
1. Consultation: ₹500
2. Lab Test 1: ₹300  
3. Extra: ₹100

Result in bill_items:
- [ ] Row 1: type=consultation, amount=500, total=500
- [ ] Row 2: type=lab_test, amount=300, total=300
- [ ] Row 3: type=extra_charge, amount=100, total=100

Result in billings:
- [ ] subtotal = 900
- [ ] cgst = 81
- [ ] sgst = 81
- [ ] total_tax = 162
- [ ] amount = 1062

## Security Tests

### CSRF Protection
- [ ] Form includes @csrf token
- [ ] Missing token returns 419 error
- [ ] Invalid token returns 419 error
- [ ] Valid token allows form submission

### Staff Middleware
- [ ] Unauthenticated user redirected to login
- [ ] Non-staff user gets 403 Forbidden
- [ ] Only staff can access /staff/billing routes
- [ ] Doctor/Admin cannot access staff billing

### Input Sanitization
- [ ] HTML tags in description don't execute
- [ ] Special characters properly escaped
- [ ] SQL injection attempts fail safely
- [ ] Scripts cannot execute via form inputs

### Authorization
- [ ] Staff can only create their own billings
- [ ] staff_id correctly set to logged-in user
- [ ] Cannot set staff_id to different user
- [ ] Billing shows correct staff member

## Integration Tests

### Billing-Patient Relationship
- [ ] Billing retrieves correct patient
- [ ] Patient name matches appointment patient
- [ ] Patient can view their billing

### Billing-Doctor Relationship
- [ ] Consultation fee fetches from doctor
- [ ] Doctor specialization displays correctly
- [ ] Doctor change updates consultation fee

### Billing-Appointment Relationship
- [ ] Billing linked to correct appointment
- [ ] Appointment details match billing
- [ ] Cannot delete appointment if billing exists

### Bill Items-Billing Relationship
- [ ] BillItems load with billing
- [ ] BillItems delete when billing deleted
- [ ] Can retrieve items from billing: `$billing->billItems`

## Performance Tests

### Page Load Time
- [ ] Create billing form loads in < 2 seconds
- [ ] No console errors or warnings
- [ ] All images/CSS load properly

### AJAX Performance
- [ ] Appointment details load in < 500ms
- [ ] No loading spinner needed (instant)
- [ ] Multiple requests don't conflict

### Large Dataset
- [ ] Form works with 100+ completed appointments
- [ ] Lab items list loads quickly with 50+ items
- [ ] No "out of memory" errors
- [ ] Calculations complete instantly

## Error Recovery Tests

### Network Failures
- [ ] AJAX timeout shows error message
- [ ] Can retry request after network error
- [ ] Form data preserved during error

### Validation Errors
- [ ] Can correct error and resubmit
- [ ] Form clear button clears all fields
- [ ] Back button doesn't create duplicate bill

### Browser Issues
- [ ] Works in Chrome
- [ ] Works in Firefox
- [ ] Works in Safari
- [ ] Works in Edge

## Backward Compatibility Tests

### Old Billing Creation
- [ ] "New Billing (Patient)" button still works
- [ ] Old billing form functions normally
- [ ] Legacy and new billings show in list
- [ ] Both methods can be used together

### Existing Billings
- [ ] Old billings still display correctly
- [ ] Can edit old billings
- [ ] Can view old billing details
- [ ] No data corruption from new feature

## Documentation Tests

- [ ] BILLING_FEATURE_GUIDE.md exists
- [ ] Setup instructions are clear
- [ ] Code examples are accurate
- [ ] Troubleshooting section helpful
- [ ] API documentation complete

## Final Checklist

### Code Quality
- [ ] No console errors
- [ ] No warning messages
- [ ] No SQL errors in logs
- [ ] Code follows PSR-12 standards
- [ ] Comments explain complex logic

### User Experience
- [ ] Form is intuitive
- [ ] Error messages are clear
- [ ] Success messages confirm action
- [ ] No confusing UI elements

### Data Integrity
- [ ] No duplicate billings created
- [ ] Foreign key constraints enforced
- [ ] Calculations always accurate
- [ ] No orphaned bill items

### Deployment Ready
- [ ] All files created/modified
- [ ] Migrations ready to run
- [ ] No hardcoded values
- [ ] Environment-specific configs used
- [ ] .env variables properly referenced

## Sign-Off

- **Tester Name**: _________________
- **Date**: _________________
- **Result**: ✅ PASS / ❌ FAIL
- **Issues Found**: _________________________________________
- **Notes**: _________________________________________

---

**QA Approval**: _________________  **Date**: _________________
