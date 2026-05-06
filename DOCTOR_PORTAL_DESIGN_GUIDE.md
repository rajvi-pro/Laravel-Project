# 🏥 Doctor Portal - Visual Design Guide

## Color Palette

### Primary Colors
```
Indigo:   #667eea    Gradient Primary
Purple:   #764ba2    Gradient Secondary
Light:    #f9fafb    Page backgrounds
Gray:     #6b7280    Secondary text
Dark:     #1f2937    Primary text
```

### Status Colors
```
Success:  #dcfce7 (bg) / #166534 (text)  ✅ Active/Normal
Warning:  #fef3c7 (bg) / #92400e (text)  ⚠️ Pending
Error:    #fee2e2 (bg) / #991b1b (text)  ❌ Expired/Abnormal
Info:     #dbeafe (bg) / #1e40af (text)  ℹ️ Information
```

---

## Component Styles

### Header Section
```
Background:     Linear gradient (Indigo → Purple)
Padding:        30px
Border-radius:  12px
Color:          White
Font-size:      2rem (h1)
Box-shadow:     0 10px 30px rgba(102, 126, 234, 0.3)
```

### Stat Cards
```
Background:     White
Padding:        25px
Border-top:     4px solid (varies by card)
Box-shadow:     0 2px 10px rgba(0, 0, 0, 0.08)
Font-size:      2.5rem (value)
Hover:          translateY(-5px) + shadow increase
```

### Cards
```
Background:     White
Border-radius:  10px
Padding:        20-25px
Box-shadow:     0 2px 10px rgba(0, 0, 0, 0.08)
Border-left:    5px solid #667eea
Hover:          translateY(-5px) + enhanced shadow
```

### Buttons
```
Padding:        8px 16px (small) / 12px 20px (large)
Border-radius:  6-8px
Font-weight:    600
Transition:     all 0.2s / 0.3s
Hover:          translateY(-2px) + box-shadow
```

### Input Fields
```
Padding:        12px 15-20px
Border:         2px solid #e5e7eb
Border-radius:  6-8px
Focus:          border-color #667eea + shadow
Background:     White
```

### Badges
```
Display:        Inline-block
Padding:        6px 12px
Border-radius:  20px (status) / 6px (date)
Font-size:      0.85rem
Font-weight:    600
```

---

## Typography

### Headers
```
h1:  2rem / 2.5rem,  font-weight: 700,  margin-bottom: 10px
h2:  1.5rem,         font-weight: 700
h5:  1.1rem,         font-weight: 700,  margin: 0
h6:  0.9rem,         font-weight: 600,  text-transform: uppercase
```

### Body Text
```
Default:     0.95rem,  color: #6b7280,  line-height: 1.6
Label:       0.85rem,  font-weight: 600, text-transform: uppercase
Small:       0.9rem,   color: #6b7280
Value:       1.2rem,   font-weight: 700, color: #1f2937
```

---

## Layout Patterns

### Dashboard Layout
```
┌─────────────────────────────────┐
│     Gradient Welcome Header     │
└─────────────────────────────────┘
┌──────────┬──────────┬──────────┬─────────┐
│  Stat 1  │  Stat 2  │  Stat 3  │ Stat 4  │
└──────────┴──────────┴──────────┴─────────┘
┌─────────────────────────────┬──────────────┐
│   Today's Schedule          │   Quick      │
│                             │   Actions    │
│                             │              │
│                             ├──────────────┤
│                             │   Profile    │
│                             │   Card       │
└─────────────────────────────┴──────────────┘
```

### Patients Layout
```
┌────────────────────────────┐
│  Gradient Header & Search  │
└────────────────────────────┘
┌─────────┬──────────┬──────────┐
│ Card 1  │ Card 2   │ Card 3   │
├─────────┼──────────┼──────────┤
│ Card 4  │ Card 5   │ Card 6   │
└─────────┴──────────┴──────────┘
```

### Prescriptions Layout
```
┌────────────────────────────────┐
│    Header with Create Button   │
└────────────────────────────────┘
┌────────────────────────────────┐
│      Statistics Cards          │
└────────────────────────────────┘
┌────────────────────────────────┐
│                                │
│    Professional Table View     │
│                                │
└────────────────────────────────┘
```

---

## Interactive States

### Hover Effects
```css
Cards:      transform: translateY(-5px), box-shadow increase
Buttons:    transform: translateY(-2px), box-shadow increase
Links:      color change, underline appear
Search:     focus ring, border color change
```

### Focus States
```css
Input:      border-color: #667eea, box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1)
Button:     background brightness increase
Link:       underline appear, color change
```

### Active States
```css
Sidebar Link:  background: rgba(255,255,255,0.1)
Filter Button: full gradient fill
Tab:           bottom border color
```

---

## Responsive Breakpoints

### Mobile (< 768px)
```
Grid:             1 column
Header:           Flex column, gap: 15px
Cards:            Full width, reduced padding
Sidebar:          Collapse (parent handling)
Font-size:        Reduced 10-15%
```

### Tablet (768px - 1024px)
```
Grid:             2 columns
Cards:            1-2 per row
Spacing:          Balanced padding
Sidebar:          Visible, normal layout
```

### Desktop (> 1024px)
```
Grid:             3-4 columns
Sidebar:          Fixed, full height
Spacing:          Full padding
Max-width:        Full container
```

---

## Animation Effects

### Smooth Transitions
```css
Duration:       0.2s - 0.3s
Timing:         ease
Properties:     transform, box-shadow, color, background
```

### Hover Animations
```css
Cards:          translateY(-5px) 0.3s
Buttons:        translateY(-2px) 0.2s + shadow
Search:         focus border 0.3s
```

### Page Transitions
```css
Fade:           opacity 0.3s
Slide:          translateX/Y 0.3s
Scale:          scale 0.2s
```

---

## Icon Design

### Emoji Icons Used
```
📊 Dashboard/Statistics
📅 Calendar/Appointments
👥 Patients/Users
💊 Prescriptions/Medicine
📋 Reports/Documents
🧪 Lab/Tests
⚡ Quick Actions/Lightning
🕐 Time/Clock
📞 Phone/Contact
📧 Email/Message
👤 User/Profile
✅ Success/Check
⚠️ Warning/Alert
❌ Error/Cancel
```

### Icon Placement
```
Headers:        Before title (emoji)
Buttons:        Before text (emoji)
Items:          Left side (optional)
Status:         Inline with text
Search:         Inside field (🔍)
```

---

## Spacing System

### Padding
```
Compact:        8px - 12px
Normal:         15px - 20px
Large:          25px - 30px
Extra Large:    40px - 60px
```

### Margins
```
Tight:          5px - 10px
Normal:         15px - 20px
Large:          25px - 30px
Paragraph:      20px bottom
```

### Gaps
```
Grid Gap:       15px - 20px
Flex Gap:       10px - 20px
List Gap:       12px - 15px
Button Gap:     10px
```

---

## Shadow System

### Card Shadows
```
Subtle:         0 2px 10px rgba(0,0,0,0.08)
Medium:         0 10px 25px rgba(0,0,0,0.15)
Heavy:          0 15px 35px rgba(102,126,234,0.2)
Header:         0 10px 30px rgba(102,126,234,0.3)
```

### Hover Shadows
```
Cards:          0 10px 25px rgba(0,0,0,0.15)
Buttons:        0 5px 15px rgba(102,126,234,0.3)
Inputs:         0 0 0 3px rgba(102,126,234,0.1)
```

---

## Form Elements

### Input Fields
```
Height:         40-42px
Padding:        12px 15px
Border:         2px solid #e5e7eb
Border-radius:  6px
Focus:          border #667eea, shadow ring
```

### Select Boxes
```
Styling:        Same as inputs
Icon:           Default browser dropdown
Hover:          Border color change
```

### Textareas
```
Min-height:     100px (3-4 rows)
Padding:        12px 15px
Resize:         vertical
Placeholder:    Lighter gray
```

### Buttons
```
Height:         40-44px (standard)
Padding:        10px 20px
Border:         None (solid color)
Cursor:         pointer
Active:         Darker shade
Disabled:       Opacity 50%
```

---

## Modal Design

### Modal Window
```
Width:          90% mobile, 500px+ desktop
Max-width:      800px
Background:     White
Border-radius:  12px
Box-shadow:     0 20px 60px rgba(0,0,0,0.3)
```

### Modal Header
```
Background:     Gradient (primary colors)
Color:          White
Padding:        20px
Border-radius:  12px 12px 0 0
Display:        flex, justify-between
```

### Modal Body
```
Padding:        30px
Max-height:     80vh
Overflow-y:     auto
Background:     White
```

### Modal Footer
```
Padding:        20px 30px
Border-top:     1px solid #e5e7eb
Display:        flex
Gap:            10px
Justify:        flex-end
```

---

## Summary

**Design Philosophy:**
- Modern & Professional
- Accessible & Responsive
- Smooth & Intuitive
- Gradient Headers
- Card-based Layouts
- Emoji Icons
- Beautiful Hover Effects
- Clear Visual Hierarchy

**Color Focus:**
- Indigo/Purple Gradients
- White & Light Gray
- Status-based Colors
- High Contrast

**Typography Focus:**
- Clear Hierarchy
- Bold Headers
- Readable Body Text
- Consistent Spacing

---

**This design system ensures consistency across all doctor portal pages!**
