<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Management System - Welcome</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-teal-50 to-cyan-100 min-h-screen" style="pointer-events: auto;">
    <style>
        * { pointer-events: auto !important; }
        .modal-backdrop { display: none !important; }
        body, html { overflow: visible; position: relative; }
    </style>
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-center items-center h-20">
                <div class="flex items-center">
                    <svg class="h-12 w-12 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span class="ml-4 text-4xl font-extrabold bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">HealthCare Plus Hospital</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-5xl font-extrabold text-gray-900 mb-4">
                    Welcome to <span class="text-teal-600">HealthCare Plus</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Your Complete Hospital Management Solution - Streamlining patient care, doctor consultations, and administrative operations for better healthcare delivery.
                </p>
                <a href="{{ route('patient.register') }}" class="inline-block bg-gradient-to-r from-teal-600 to-cyan-600 text-white font-bold py-4 px-8 rounded-full hover:from-teal-700 hover:to-cyan-700 transform hover:scale-105 transition-all duration-300 shadow-lg" style="pointer-events: auto; cursor: pointer;">
                    Register as Patient
                </a>
            </div>
        </div>
    </section>

    <!-- Portal Selection Buttons -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Select Your Portal</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Patient Portal -->
                <a href="{{ route('patient.login') }}" class="group" style="pointer-events: auto; cursor: pointer;">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl shadow-xl p-8 text-center transform hover:scale-105 transition-all duration-300 hover:shadow-2xl" style="pointer-events: none;">
                        <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                            <svg class="h-10 w-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">Patient</h3>
                        <p class="text-blue-100 mb-4">Book appointments, view reports, and manage prescriptions</p>
                        <span class="inline-block bg-white text-blue-600 font-semibold py-2 px-6 rounded-full group-hover:bg-blue-50 transition-all duration-300">Login →</span>
                    </div>
                </a>

                <!-- Doctor Portal -->
                <a href="{{ route('doctor.login') }}" class="group" style="pointer-events: auto; cursor: pointer;">
                    <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl shadow-xl p-8 text-center transform hover:scale-105 transition-all duration-300 hover:shadow-2xl" style="pointer-events: none;">
                        <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                            <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">Doctor</h3>
                        <p class="text-green-100 mb-4">Manage patients, write prescriptions, and maintain records</p>
                        <span class="inline-block bg-white text-green-600 font-semibold py-2 px-6 rounded-full group-hover:bg-green-50 transition-all duration-300">Login →</span>
                    </div>
                </a>

                <!-- Admin Portal -->
                <a href="{{ route('admin.login') }}" class="group" style="pointer-events: auto; cursor: pointer;">
                    <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl shadow-xl p-8 text-center transform hover:scale-105 transition-all duration-300 hover:shadow-2xl" style="pointer-events: none;">
                        <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                            <svg class="h-10 w-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">Admin</h3>
                        <p class="text-purple-100 mb-4">Oversee operations, manage doctors, patients, and staff</p>
                        <span class="inline-block bg-white text-purple-600 font-semibold py-2 px-6 rounded-full group-hover:bg-purple-50 transition-all duration-300">Login →</span>
                    </div>
                </a>

                <!-- Staff Portal -->
                <a href="{{ route('staff.login') }}" class="group" style="pointer-events: auto; cursor: pointer;">
                    <div class="bg-gradient-to-br from-orange-500 to-orange-700 rounded-2xl shadow-xl p-8 text-center transform hover:scale-105 transition-all duration-300 hover:shadow-2xl" style="pointer-events: none;">
                        <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                            <svg class="h-10 w-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">Staff</h3>
                        <p class="text-orange-100 mb-4">Access patient records, medicines, and lab results</p>
                        <span class="inline-block bg-white text-orange-600 font-semibold py-2 px-6 rounded-full group-hover:bg-orange-50 transition-all duration-300">Login →</span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Our Healthcare Services</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300">
                    <div class="text-teal-600 mb-4">
                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Easy Appointment Booking</h3>
                    <p class="text-gray-600">Schedule appointments with doctors instantly. View available time slots and book consultations online 24/7.</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300">
                    <div class="text-teal-600 mb-4">
                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Medical Records Access</h3>
                    <p class="text-gray-600">Access your complete medical history, lab results, prescriptions, and diagnostic reports anytime, anywhere.</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300">
                    <div class="text-teal-600 mb-4">
                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Digital Prescriptions</h3>
                    <p class="text-gray-600">Receive digital prescriptions from doctors with detailed medicine information, dosage, and usage instructions.</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300">
                    <div class="text-teal-600 mb-4">
                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Lab Results Online</h3>
                    <p class="text-gray-600">Get instant access to laboratory test results with detailed analysis and normal range comparisons.</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300">
                    <div class="text-teal-600 mb-4">
                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Expert Doctors</h3>
                    <p class="text-gray-600">Consult with highly qualified and experienced doctors across various specializations for comprehensive care.</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition-all duration-300">
                    <div class="text-teal-600 mb-4">
                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Secure & Confidential</h3>
                    <p class="text-gray-600">Your medical data is protected with advanced encryption ensuring complete privacy and confidentiality.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">About HealthCare Plus</h2>
                    <p class="text-gray-600 mb-4">
                        HealthCare Plus is a comprehensive hospital management system designed to streamline healthcare delivery and improve patient experiences. Our platform connects patients, doctors, administrative staff, and support personnel in one unified system.
                    </p>
                    <p class="text-gray-600 mb-4">
                        With advanced features for appointment scheduling, medical records management, prescription tracking, and laboratory results, we ensure efficient healthcare operations and better patient outcomes.
                    </p>
                    <div class="grid grid-cols-2 gap-6 mt-8">
                        <div>
                            <div class="text-3xl font-bold text-teal-600 mb-2">24/7</div>
                            <div class="text-gray-600">Online Access</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-teal-600 mb-2">100%</div>
                            <div class="text-gray-600">Data Security</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-teal-600 mb-2">50+</div>
                            <div class="text-gray-600">Specializations</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-teal-600 mb-2">10K+</div>
                            <div class="text-gray-600">Happy Patients</div>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-teal-100 to-cyan-100 rounded-2xl p-8">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 300'%3E%3Crect fill='%2314b8a6' width='400' height='300' rx='20'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='Arial' font-size='24' fill='white'%3EHealthcare Excellence%3C/text%3E%3C/svg%3E" alt="Healthcare" class="rounded-xl shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4">HealthCare Plus</h3>
                <p class="text-gray-400">Comprehensive hospital management solution for modern healthcare delivery.</p>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('patient.register') }}" class="text-gray-400 hover:text-white transition-colors">Patient Registration</a></li>
                    <li><a href="{{ route('patient.login') }}" class="text-gray-400 hover:text-white transition-colors">Patient Login</a></li>
                    <li><a href="{{ route('doctor.login') }}" class="text-gray-400 hover:text-white transition-colors">Doctor Portal</a></li>
                    <li><a href="{{ route('admin.login') }}" class="text-gray-400 hover:text-white transition-colors">Admin Panel</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Services</h4>
                <ul class="space-y-2">
                    <li class="text-gray-400">Online Appointments</li>
                    <li class="text-gray-400">Medical Records</li>
                    <li class="text-gray-400">Digital Prescriptions</li>
                    <li class="text-gray-400">Lab Results</li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Contact</h4>
                <ul class="space-y-2">
                    <li class="text-gray-400">Email: info@healthcareplus.com</li>
                    <li class="text-gray-400">Phone: +1 (555) 123-4567</li>
                    <li class="text-gray-400">Address: 123 Medical Center, Health City</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-700 py-6 text-center text-sm">
            <p>© {{ date('Y') }} HealthCare Plus Hospital Management System. All rights reserved.</p>
            <p class="mt-2">Made with ❤️ by <a href="" target="_blank" class="hover:underline text-teal-400">HealthCare Plus Hospital</a></p>
        </div>
    </footer>
</body>
</html>
