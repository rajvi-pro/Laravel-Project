# 🏥 HMS Admin Dashboard - Quick Reference Guide

## 🚀 Getting Started

### Access the Admin Dashboard
```
URL: http://localhost/admin/dashboard (after login)
Route Prefix: /admin/
```

### Login Credentials
Check the admin seeder for test credentials, or use your registered admin account.

---

## 📊 Dashboard Overview

### Main Sections

#### 1. Summary Cards (Top Row)
```
┌──────────────────────────────────────────────┐
│ Total Patients │ Total Doctors │ Staff │ Revenue │
└──────────────────────────────────────────────┘
```
- **Total Patients**: Count of all registered patients
- **Total Doctors**: Active practitioners in system
- **Total Staff**: Support team members
- **Total Revenue**: Sum of paid billings

#### 2. Analytics Charts
```
┌─────────────────────────┬─────────────────────────┐
│  Revenue Trend          │  Appointment Status     │
│  (Line Chart)           │  (Bar Chart)            │
└─────────────────────────┴─────────────────────────┘
```

#### 3. Operational Insights
```
┌─────────────────────────┬─────────────────────────┐
│ Staff Distribution      │     4 Metrics           │
│ (Pie/Doughnut Chart)    │  - Completion Rate      │
│                         │  - Doctor Utilization   │
│                         │  - New Patients/Month   │
│                         │  - Today's Appointments │
└─────────────────────────┴─────────────────────────┘
```

#### 4. Recent Appointments Table
- Last 5 appointments with status
- Quick view and reschedule options

---

## 👥 Patient Management

### List Patients
```
Click: Sidebar → Patients
Shows: All patients with pagination
```

**Available Actions:**
- **View**: See complete patient details
- **Edit**: Update patient information
- **Delete**: Remove patient from system
  - Requires confirmation
  - Shows patient name and ID

### Add New Patient
```
Click: "Add New Patient" button → Fill Form → Register
```

**Required Fields:**
- Name
- Email
- Password

**Optional Fields:**
- Phone
- Date of Birth
- Gender
- Blood Group
- Address (Street, City, State, Pincode)
- Emergency Contact

### Edit Patient
```
Click: Edit button on patient row → Update info → Save
```

---

## 👨‍⚕️ Doctor Management

### List Doctors
```
Click: Sidebar → Doctors
Shows: All registered doctors with specialization
```

**Information Displayed:**
- Name, Email, Phone
- Specialization, Experience (years)
- Consultation Fee (₹)
- Total Appointments

### Register New Doctor
```
Click: "Register New Doctor" → Fill comprehensive form
```

**Form Fields:**
- Full Name (required)
- Email (required, unique)
- Phone (required)
- Specialization (e.g., Cardiology, Neurology)
- Qualification (e.g., MBBS, MD)
- Experience Years
- Consultation Fee
- Password (required)

### Edit/Delete Doctor
```
Edit: Similar to patient management
Delete: Confirmation modal with doctor name
```

---

## 👔 Staff Management

### List Staff
```
Click: Sidebar → Staff
Shows: All team members with role and department
```

**Information Displayed:**
- Name, Email, Phone
- Role (badge)
- Department (badge)
- Joined Date

### Add New Staff
```
Click: "Add New Staff" → Fill details
```

**Form Fields:**
- Name, Email, Phone
- Role (Nurse, Doctor, Admin, Support, etc.)
- Department
- Password

### Manage Staff
- Edit: Update details
- Delete: Remove with confirmation

---

## 📅 Appointment Management

### View Appointments
```
Click: Sidebar → Appointments
Shows: List with status, date, time
Pagination: Yes (20 per page)
```

**Status Indicators:**
- ✓ Scheduled (Blue)
- ↻ Rescheduled (Yellow)
- ✓ Completed (Green)
- ✗ Cancelled (Red)

### View Full Details
```
Click: "View" button → See all appointment details
```

### Reschedule Appointment
```
Click: "Reschedule" button → Select new date/time → Save
```

**Note:** Admin can reschedule appointments, status changes to "Rescheduled"

---

## 💰 Billing Management

### View Billing Records
```
Click: Sidebar → Billing
Shows: All billing records with summary cards
```

**Summary Cards:**
- Total Billings (count)
- Total Revenue (paid)
- Pending Amount
- Paid Records (count)

### Create Billing Record
```
Click: "Create Billing" → Fill details → Save
```

**Required Information:**
- Patient (select from dropdown)
- Description
- Amount
- Payment Status (pending/paid/cancelled)
- Payment Method (cash/card/online/cheque)
- Billing Date

**Optional:**
- Appointment Link
- Staff Assignment
- Due Date
- Notes

### Manage Billing
- **View**: See full billing details
- **Edit**: Update billing information
- **Delete**: Remove with detailed confirmation
  - Shows patient name and amount

---

## 📊 Chart Interactions

### Revenue Trend Chart
- **Type**: Line Chart
- **Data**: Last 12 months
- **Y-axis**: Amount in ₹
- **Hover**: Shows exact amount

### Appointment Status Chart
- **Type**: Bar Chart
- **Data**: Completed, Scheduled, Cancelled, Rescheduled
- **Colors**: Green, Blue, Red, Orange

### Staff Distribution Chart
- **Type**: Doughnut Chart
- **Data**: By position/role
- **Legend**: Bottom

---

## 🔄 Common Workflows

### Register New Patient & Create Appointment
1. Go to Patients → Add New Patient
2. Fill details → Register
3. System creates patient profile
4. Can then create appointment for this patient

### Create Billing for Appointment
1. Go to Appointments → Find appointment
2. Note appointment ID
3. Go to Billing → Create Billing
4. Link to appointment
5. Enter amount and payment details
6. Save

### Track Doctor Performance
1. Dashboard → See total revenue
2. Check doctors list → See appointments per doctor
3. View appointments → Filter by doctor
4. Monitor utilization rate in metrics

---

## ⌨️ Keyboard Shortcuts & Tips

### Form Shortcuts
- **Tab**: Move between fields
- **Enter**: Submit form (if focused on submit button)
- **Escape**: Close modal dialog

### Navigation
- **Sidebar Active State**: Shows current page in lighter color
- **Breadcrumb**: Shows current location (if available)

### Quick Access
- **Dashboard**: Click "HMS Admin" logo (in future)
- **Logout**: Always visible at bottom of sidebar

---

## 🛡️ Safety Features

### Confirmation Dialogs
All deletions require confirmation modal showing:
- Item being deleted (name/ID)
- Confirmation message
- Cancel and Confirm buttons

### Input Validation
- Email format validation
- Required field checking
- Unique constraint checking (email, etc.)
- Date range validation

### Session Security
- Admin middleware checks authentication
- Session timeout (configurable)
- CSRF token in all forms

---

## 📱 Mobile Responsiveness

### Desktop (1200px+)
- Full sidebar visible
- 4 columns for summary cards
- Side-by-side charts

### Tablet (768px - 1199px)
- Sidebar collapses to menu
- 2 columns for cards
- Stacked charts

### Mobile (< 768px)
- Hamburger menu for sidebar
- 1 column for cards
- Full-width tables with horizontal scroll
- Stacked layout

---

## 🎨 UI Elements Guide

### Buttons
- **Green (Success)**: Add/Create/Register
- **Blue (Primary)**: Edit/Action
- **Red (Danger)**: Delete
- **Secondary**: Cancel/Back

### Badges
- **Blue**: Info (gender, role)
- **Red**: Blood group, danger status
- **Green**: Success, completed
- **Yellow**: Warning, pending
- **Gray**: Secondary info

### Cards
- **Colored Top Border**: Indicates category
- **Shadow**: Elevation on hover
- **Icons**: Quick visual reference

---

## 🔧 Troubleshooting

### Charts Not Displaying
- Ensure Chart.js library is loaded
- Check browser console for errors
- Verify data is populated in database

### Forms Not Submitting
- Check for validation errors (displayed in red)
- Ensure email is unique (if creating new)
- Verify all required fields are filled

### Delete Not Working
- Confirm JavaScript is enabled
- Check browser console for errors
- Verify user has admin privileges

### Data Not Updated
- Refresh page (Ctrl+F5 hard refresh)
- Check database for changes
- Verify relationship data exists

---

## 📞 Support Tips

1. **Check Console**: Open browser DevTools (F12) → Console tab
2. **Read Validation Errors**: They clearly state what's wrong
3. **Verify Data**: Check if patient/doctor exists before operations
4. **Session Issues**: Clear cookies and login again

---

## ✨ Latest Features

### Enhanced in Current Version:
✅ Patient CRUD (Create, Read, Update, Delete)
✅ Better Dashboard with modern UI
✅ Modal confirmation dialogs
✅ Success notifications
✅ Improved charts display
✅ Gradient cards for metrics
✅ Progress bars for utilization
✅ Responsive design

---

## 🎯 Quick Checklist

- [ ] Dashboard loads successfully
- [ ] Summary cards show correct counts
- [ ] Charts display data
- [ ] Can create patient
- [ ] Can edit patient details
- [ ] Can delete with confirmation
- [ ] Doctor management works
- [ ] Staff management works
- [ ] Appointments visible
- [ ] Billing records display
- [ ] Mobile layout responds

---

**Version**: 1.0 (Complete)
**Last Updated**: March 28, 2026
**Status**: Production Ready ✅
