# Billing Feature Architecture & Flow Diagrams

## System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    STAFF PANEL - BILLING SYSTEM                 │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                         PRESENTATION LAYER                       │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │ create-billing.blade.php (5-Step Form)                   │   │
│  │ - Appointment Selection                                  │   │
│  │ - Patient/Doctor Information (readonly)                  │   │
│  │ - Billing Items Selection (consultation + lab items)     │   │
│  │ - Extra Charges (optional)                               │   │
│  │ - Summary with Real-Time Calculations                    │   │
│  └──────────────────────────────────────────────────────────┘   │
└──────────────────────┬──────────────────────────────────────────┘
                       │ JavaScript AJAX
                       ↓
┌─────────────────────────────────────────────────────────────────┐
│                      APPLICATION LAYER                           │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │ StaffDashboardController                                 │   │
│  │ ┌────────────────────────────────────────────────────┐   │   │
│  │ │ createBilling()                                    │   │   │
│  │ │ - GET /staff/billings/create                       │   │   │
│  │ │ - Returns view with completed appointments         │   │   │
│  │ └────────────────────────────────────────────────────┘   │   │
│  │ ┌────────────────────────────────────────────────────┐   │   │
│  │ │ getAppointmentDetails() [AJAX]                     │   │   │
│  │ │ - GET /staff/billing/appointment/{id}/details      │   │   │
│  │ │ - Returns: patient, doctor, consultation_fee,      │   │   │
│  │ │           lab_reports (JSON)                       │   │   │
│  │ └────────────────────────────────────────────────────┘   │   │
│  │ ┌────────────────────────────────────────────────────┐   │   │
│  │ │ createBillingFromAppointment() [AJAX]              │   │   │
│  │ │ - POST /staff/billing/create-from-appointment      │   │   │
│  │ │ - Validates & calls BillingService                 │   │   │
│  │ │ - Returns billing_id and redirect URL              │   │   │
│  │ └────────────────────────────────────────────────────┘   │   │
│  └──────────────────────────────────────────────────────────┘   │
│                           │ Validation                           │
│                           ↓                                      │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │ StoreBillingFromAppointmentRequest                       │   │
│  │ - appointment_id (required, exists, no duplicate)        │   │
│  │ - lab_items[].id, charge (required, numeric, >= 0)       │   │
│  │ - extra_charges (optional, numeric, >= 0)                │   │
│  │ - payment_status (in: pending, paid)                     │   │
│  └──────────────────────────────────────────────────────────┘   │
│                           │ Business Logic                       │
│                           ↓                                      │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │ BillingService                                           │   │
│  │ ┌────────────────────────────────────────────────────┐   │   │
│  │ │ createBillingFromAppointment()                     │   │   │
│  │ │ - Validates appointment eligibility                │   │   │
│  │ │ - Creates Billing record                           │   │   │
│  │ │ - Creates BillItem for each charge                 │   │   │
│  │ │ - Calculates: subtotal, cgst, sgst, total_tax      │   │   │
│  │ └────────────────────────────────────────────────────┘   │   │
│  │ ┌────────────────────────────────────────────────────┐   │   │
│  │ │ calculateTaxes()                                   │   │   │
│  │ │ - CGST = subtotal × 9%                             │   │   │
│  │ │ - SGST = subtotal × 9%                             │   │   │
│  │ │ - Total = subtotal + cgst + sgst                   │   │   │
│  │ └────────────────────────────────────────────────────┘   │   │
│  └──────────────────────────────────────────────────────────┘   │
└──────────────────────┬──────────────────────────────────────────┘
                       │ Eloquent ORM
                       ↓
┌─────────────────────────────────────────────────────────────────┐
│                      DATA ACCESS LAYER                           │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │ Models (Eloquent)                                        │   │
│  │ ┌────────────────────────────────────────────────────┐   │   │
│  │ │ Appointment                                        │   │   │
│  │ │ - with: patient, doctor, billing                   │   │   │
│  │ └────────────────────────────────────────────────────┘   │   │
│  │ ┌────────────────────────────────────────────────────┐   │   │
│  │ │ Patient                                            │   │   │
│  │ │ - has: appointments, billings, prescriptions       │   │   │
│  │ └────────────────────────────────────────────────────┘   │   │
│  │ ┌────────────────────────────────────────────────────┐   │   │
│  │ │ Doctor                                             │   │   │
│  │ │ - consultation_fee (decimal)                       │   │   │
│  │ └────────────────────────────────────────────────────┘   │   │
│  │ ┌────────────────────────────────────────────────────┐   │   │
│  │ │ LabResult                                          │   │   │
│  │ │ - charge (decimal)                                 │   │   │
│  │ │ - status (completed)                               │   │   │
│  │ └────────────────────────────────────────────────────┘   │   │
│  │ ┌────────────────────────────────────────────────────┐   │   │
│  │ │ Billing ★ NEW RELATIONSHIP                         │   │   │
│  │ │ - billItems() → hasMany(BillItem)                  │   │   │
│  │ └────────────────────────────────────────────────────┘   │   │
│  │ ┌────────────────────────────────────────────────────┐   │   │
│  │ │ BillItem ★ NEW MODEL                               │   │   │
│  │ │ - billing() → belongsTo(Billing)                   │   │   │
│  │ │ - type: consultation, lab_test, extra_charge       │   │   │
│  │ └────────────────────────────────────────────────────┘   │   │
│  └──────────────────────────────────────────────────────────┘   │
└──────────────────────┬──────────────────────────────────────────┘
                       │ SQL Queries
                       ↓
┌─────────────────────────────────────────────────────────────────┐
│                      DATABASE LAYER                              │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │ appointments TABLE                                       │   │
│  │ ├─ id, patient_id, doctor_id, status (completed)        │   │
│  │ └─ Key: Only completed appointments eligible            │   │
│  ├──────────────────────────────────────────────────────────┤   │
│  │ patients TABLE                                           │   │
│  │ ├─ id, name, email, phone                               │   │
│  │ └─ Linked via appointment.patient_id                    │   │
│  ├──────────────────────────────────────────────────────────┤   │
│  │ doctors TABLE                                            │   │
│  │ ├─ id, name, specialization, consultation_fee ★         │   │
│  │ └─ Linked via appointment.doctor_id                     │   │
│  ├──────────────────────────────────────────────────────────┤   │
│  │ lab_results TABLE                                        │   │
│  │ ├─ id, patient_id, test_name, status, charge ★          │   │
│  │ └─ Only "completed" with charge > 0 included            │   │
│  ├──────────────────────────────────────────────────────────┤   │
│  │ billings TABLE (existing)                               │   │
│  │ ├─ id, appointment_id, patient_id, staff_id             │   │
│  │ ├─ amount, subtotal, cgst, sgst, total_tax              │   │
│  │ ├─ payment_status, billing_date, due_date               │   │
│  │ └─ FK: appointments(id)                                 │   │
│  ├──────────────────────────────────────────────────────────┤   │
│  │ bill_items TABLE ★ NEW                                   │   │
│  │ ├─ id, billing_id, type, description                    │   │
│  │ ├─ amount, quantity, total (line items)                 │   │
│  │ ├─ timestamps, deleted_at (soft deletes)                │   │
│  │ └─ FK: billings(id) CASCADE DELETE                       │   │
│  └──────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
```

---

## Request Flow - Creating a Billing

```
┌─────────────────────────────────────────────────────────────────┐
│                    USER ACTION SEQUENCE                          │
└─────────────────────────────────────────────────────────────────┘

Step 1: Select Appointment
┌─────────────────────────┐
│ User selects appointment│
│ from dropdown           │
└──────────┬──────────────┘
           │ onChange event
           ↓
┌──────────────────────────────────────────────────────────────────┐
│ AJAX Request 1: Get Appointment Details                          │
├──────────────────────────────────────────────────────────────────┤
│ GET /staff/billing/appointment/{id}/details                      │
│                                                                  │
│ Response (JSON):                                                 │
│ {                                                                │
│   "success": true,                                               │
│   "patient": { "id": 1, "name": "John", ... },                   │
│   "doctor": { "id": 1, "name": "Dr. Smith", ... },               │
│   "consultation_fee": 500,                                       │
│   "lab_reports": [                                               │
│     { "id": 1, "test_name": "CBC", "charge": 300 },              │
│     { "id": 2, "test_name": "ECG", "charge": 400 }               │
│   ]                                                              │
│ }                                                                │
└──────────┬───────────────────────────────────────────────────────┘
           │ JavaScript renders data
           ↓
┌─────────────────────────────────────────────────────────────────┐
│ Step 2-4: User Input                                            │
├─────────────────────────────────────────────────────────────────┤
│ - Displays patient info (readonly)                              │
│ - Displays doctor info (readonly)                               │
│ - Shows consultation fee (₹500)                                 │
│ - Shows lab items as selectable cards                           │
│ - Allows extra charges input                                    │
└──────────┬────────────────────────────────────────────────────────┘
           │ Each field change triggers
           ↓
┌─────────────────────────────────────────────────────────────────┐
│ Real-Time Calculation (JavaScript)                              │
├─────────────────────────────────────────────────────────────────┤
│ Calculate on every change:                                      │
│   Subtotal = consultation + selected labs + extra                │
│   CGST = Subtotal × 0.09                                         │
│   SGST = Subtotal × 0.09                                         │
│   Total Tax = CGST + SGST                                        │
│   Total = Subtotal + Total Tax                                   │
│                                                                  │
│ Update UI with new totals                                       │
│ Update summary section                                          │
└──────────┬────────────────────────────────────────────────────────┘
           │ User clicks "Create Billing"
           ↓
┌──────────────────────────────────────────────────────────────────┐
│ AJAX Request 2: Create Billing                                  │
├──────────────────────────────────────────────────────────────────┤
│ POST /staff/billing/create-from-appointment                      │
│                                                                  │
│ Request Body:                                                    │
│ {                                                                │
│   "appointment_id": 1,                                           │
│   "lab_items": [                                                 │
│     { "id": 1, "charge": 300 },                                  │
│     { "id": 2, "charge": 400 }                                   │
│   ],                                                             │
│   "extra_charges": 100,                                          │
│   "payment_status": "pending",                                   │
│   "notes": "..."                                                 │
│ }                                                                │
│                                                                  │
│ Server-Side Processing:                                         │
│ 1. Validation (all rules checked)                               │
│ 2. BillingService::createBillingFromAppointment()               │
│    ├─ Start transaction                                         │
│    ├─ Create Billing record                                    │
│    │  └─ subtotal = 800, cgst = 72, sgst = 72,                  │
│    │     total_tax = 144, amount = 944                           │
│    ├─ Create BillItem: Consultation (500)                       │
│    ├─ Create BillItem: Lab Test 1 (300)                         │
│    ├─ Create BillItem: Lab Test 2 (400)                         │
│    ├─ Create BillItem: Extra Charges (100)                      │
│    └─ Commit transaction                                        │
│                                                                  │
│ Response (JSON):                                                │
│ {                                                                │
│   "success": true,                                               │
│   "message": "Billing created successfully",                     │
│   "billing_id": 5,                                               │
│   "redirect": "/staff/billings/5"                                │
│ }                                                                │
└──────────┬────────────────────────────────────────────────────────┘
           │ JavaScript redirects
           ↓
┌──────────────────────────────────────────────────┐
│ Billing Detail Page (/staff/billings/5)          │
│ - Shows all created bill items                   │
│ - Displays totals                                │
│ - Confirms success                               │
└──────────────────────────────────────────────────┘
```

---

## Database Transaction Flow

```
POST /staff/billing/create-from-appointment
│
└─→ Validation
    │
    ├─ appointment_id exists? YES
    │
    ├─ Billing already exists? NO
    │
    ├─ Lab items exist & completed? YES
    │
    ├─ All amounts >= 0? YES
    │
    └─ PASSED ✓
       │
       └─→ START DATABASE TRANSACTION
           │
           ├─→ Create Billing
           │   INSERT INTO billings (
           │     appointment_id: 1,
           │     patient_id: 1,
           │     staff_id: current_user,
           │     subtotal: 800,
           │     cgst: 72,
           │     sgst: 72,
           │     total_tax: 144,
           │     amount: 944,
           │     payment_status: 'pending'
           │   )
           │   Result: Billing ID = 5
           │
           ├─→ Create BillItem #1 (Consultation)
           │   INSERT INTO bill_items (
           │     billing_id: 5,
           │     type: 'consultation',
           │     description: 'Consultation Fee - Dr. Smith',
           │     amount: 500,
           │     quantity: 1,
           │     total: 500
           │   )
           │
           ├─→ Create BillItem #2 (Lab Test 1)
           │   INSERT INTO bill_items (
           │     billing_id: 5,
           │     type: 'lab_test',
           │     description: 'Lab Test - CBC',
           │     amount: 300,
           │     quantity: 1,
           │     total: 300
           │   )
           │
           ├─→ Create BillItem #3 (Lab Test 2)
           │   INSERT INTO bill_items (
           │     billing_id: 5,
           │     type: 'lab_test',
           │     description: 'Lab Test - ECG',
           │     amount: 400,
           │     quantity: 1,
           │     total: 400
           │   )
           │
           ├─→ Create BillItem #4 (Extra Charges)
           │   INSERT INTO bill_items (
           │     billing_id: 5,
           │     type: 'extra_charge',
           │     description: 'Administration Fee',
           │     amount: 100,
           │     quantity: 1,
           │     total: 100
           │   )
           │
           └─→ COMMIT TRANSACTION
               │
               └─ All-or-nothing guarantee
                  All inserts succeed or all rollback
```

---

## Data Relationships

```
Appointment
│
├─→ Patient
│   │
│   ├─ name: string
│   ├─ email: string
│   └─ phone: string
│
├─→ Doctor
│   │
│   ├─ name: string
│   ├─ specialization: string
│   └─ consultation_fee: decimal ★
│
└─→ Billing
    │
    ├─ subtotal: decimal
    ├─ cgst: decimal (9%)
    ├─ sgst: decimal (9%)
    ├─ total_tax: decimal
    ├─ amount: decimal (total)
    │
    └─→ BillItems (1-to-Many)
        │
        ├─ BillItem #1
        │  ├─ type: 'consultation'
        │  ├─ description: 'Consultation Fee - Dr. Smith'
        │  └─ amount: 500
        │
        ├─ BillItem #2
        │  ├─ type: 'lab_test'
        │  ├─ description: 'Lab Test - CBC'
        │  └─ amount: 300
        │
        ├─ BillItem #3
        │  ├─ type: 'lab_test'
        │  ├─ description: 'Lab Test - ECG'
        │  └─ amount: 400
        │
        └─ BillItem #4
           ├─ type: 'extra_charge'
           ├─ description: 'Administration Fee'
           └─ amount: 100
```

---

## Calculation Flow

```
INPUT:
├─ Appointment #1
├─ Doctor consultation_fee: ₹500
├─ Selected Lab #1 charge: ₹300
├─ Selected Lab #2 charge: ₹400
└─ Extra charges: ₹100

CALCULATION:
├─ Subtotal = 500 + 300 + 400 + 100 = ₹1,300
├─ CGST = 1,300 × 0.09 = ₹117
├─ SGST = 1,300 × 0.09 = ₹117
├─ Total Tax = 117 + 117 = ₹234
└─ Total = 1,300 + 234 = ₹1,534

STORAGE:
├─ Billing record
│  ├─ subtotal: 1300
│  ├─ cgst: 117
│  ├─ sgst: 117
│  ├─ total_tax: 234
│  └─ amount: 1534
│
└─ BillItem records (4)
   ├─ Item 1: consultation, 500
   ├─ Item 2: lab_test, 300
   ├─ Item 3: lab_test, 400
   └─ Item 4: extra_charge, 100
```

---

## Error Handling Flow

```
User Action
│
├─→ VALIDATION ERRORS
│   │
│   ├─ No appointment selected
│   │  └─ Show: "Please select an appointment"
│   │
│   ├─ Invalid amounts
│   │  └─ Show: "Charges must be numeric and >= 0"
│   │
│   ├─ Negative values
│   │  └─ Show: "Charges cannot be negative"
│   │
│   └─ Non-numeric input
│      └─ Show: Validation error from server
│
├─→ BUSINESS LOGIC ERRORS
│   │
│   ├─ Duplicate billing
│   │  └─ Show: "Billing already exists for this appointment"
│   │
│   ├─ Appointment not found
│   │  └─ Show: "Invalid appointment selected"
│   │
│   ├─ Lab test not completed
│   │  └─ Show: "Lab test must be completed"
│   │
│   └─ Appointment not completed
│      └─ Show: "Appointment must be completed"
│
├─→ SECURITY ERRORS
│   │
│   ├─ CSRF token invalid
│   │  └─ HTTP 419 - Retry with valid token
│   │
│   ├─ User not authenticated
│   │  └─ Redirect to staff login
│   │
│   └─ User not staff
│      └─ HTTP 403 Forbidden
│
└─→ SERVER ERRORS
    │
    ├─ Database connection error
    │  └─ Show: "An error occurred while creating billing"
    │
    ├─ Transaction rollback
    │  └─ Show: "Failed to create billing items"
    │
    └─ Unexpected exception
       └─ Show: Generic error + debug info (if enabled)
```

---

## Middleware Stack

```
Request
│
├─→ staff middleware <-- Only authenticated staff pass
│   │
│   └─→ StaffDashboardController method
│       │
│       ├─→ createBilling() - Returns form view
│       │
│       ├─→ getAppointmentDetails() [AJAX]
│       │   └─→ Return JSON response
│       │
│       └─→ createBillingFromAppointment() [AJAX]
│           │
│           ├─→ Validation
│           ├─→ BillingService call
│           ├─→ Database transaction
│           └─→ Return JSON response
│
└─→ Response to Client
```

---

## Security Layers

```
REQUEST
│
├─ Layer 1: Middleware Authentication
│  └─ User must be staff (app('auth')->check() && role === 'staff')
│
├─ Layer 2: CSRF Token
│  └─ Form must include valid @csrf token
│
├─ Layer 3: Input Validation
│  └─ All inputs validated via StoreBillingFromAppointmentRequest
│
├─ Layer 4: Business Logic Validation
│  └─ BillingService validates appointment eligibility
│
├─ Layer 5: Database Constraints
│  └─ Foreign keys enforce referential integrity
│
├─ Layer 6: Authorization
│  └─ Staff ID set to authenticated user (cannot override)
│
└─ Layer 7: Exception Handling
   └─ All exceptions caught and logged safely
```

---

This documentation provides complete visual understanding of the billing system architecture.
