<img width="1920" height="1080" alt="Screenshot (536)" src="https://github.com/user-attachments/assets/40cb57f6-0ecf-4650-b885-09d54cf4284a" />
# 🏥 Hospital Management System

A full-stack Hospital Management System developed using Laravel and MySQL, featuring patient management, doctor scheduling, appointment booking, and secure authentication


## 📋 Features

👨‍⚕️ Patient Management – Register, update, and manage patient records
🩺 Doctor Scheduling – Manage doctor availability and working schedules
📅 Appointment Booking – Book, approve, and track patient appointments
🔐 Secure Authentication – Role-based login system (Admin, Doctor, Staff, Patient)
📊 Dashboard – Real-time overview of hospital activities and statistics

## 🛠️ Roles & Access
👨‍💼 Admin
Manage doctors, patients, and staff
View system reports and analytics
Full system control
🧑‍⚕️ Doctor
View appointments
Manage patient records & prescriptions
🧑‍💻 Staff
Assist in patient registration
Manage appointments and basic operations
🧑‍🤝‍🧑 Patient
Book appointments
View medical history and prescriptions

## 🛠️ Tech Stack

- **Backend:** Laravel 11.x
- **Database:** MySQL
- **Frontend:** Blade Templates, Tailwind CSS
- **Authentication:** Laravel Breeze/Sanctum

## 📦 Installation

### Prerequisites

Make sure you have the following installed:
- PHP >= 8.1
- Composer
- MySQL or MariaDB  
- Node.js & NPM

### Setup Steps

1. **Clone the repository**
```bash
git clone https://github.com/rajvi-pro/Laravel-Project.git
cd Laravel-Project
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node dependencies**
```bash
npm install
```

4. **Environment configuration**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure your database**

Edit the `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wellcare
DB_USERNAME=root
DB_PASSWORD=your_password
```

6. **Create database**
```sql
CREATE DATABASE wellcare;
```

7. **Run migrations**
```bash
php artisan migrate
```

8. **Seed database (optional)**
```bash
php artisan db:seed
```

9. **Create storage link**
```bash
php artisan storage:link
```

10. **Start development server**
```bash
php artisan serve
```

In another terminal, compile assets:
```bash
npm run dev
```

11. **Access the application**

Open your browser and visit: `http://localhost:8000`

12. **Admin password**

username: admin@hms.local 
password : Admin@123

## 🚀 Quick Start (For Beginners)

If you're new to Laravel:

1. Install [XAMPP](https://www.apachefriends.org/) (includes PHP & MySQL)
2. Install [Composer](https://getcomposer.org/)
3. Install [Node.js](https://nodejs.org/)
4. Follow the installation steps above

## 📁 Project Structure
