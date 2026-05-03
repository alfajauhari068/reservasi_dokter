<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk - Reservasi Dokter</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#EBF5FF',
                            100: '#D6EBFF',
                            200: '#ADD8FF',
                            300: '#85C4FF',
                            400: '#5CB1FF',
                            500: '#339DFF',
                            600: '#1D9BF0', // Primary blue
                            700: '#187FCA',
                            800: '#1363A3',
                            900: '#0E477D',
                        },
                        success: {
                            50: '#ECFDF5',
                            100: '#D1FAE5',
                            200: '#A7F3D0',
                            300: '#6EE7B7',
                            400: '#34D399',
                            500: '#10B981',
                            600: '#22C55E', // Success green
                            700: '#16A34A',
                            800: '#15803D',
                            900: '#166534',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Custom gradient background */
        .login-gradient {
            background: linear-gradient(135deg, #EBF5FF 0%, #ECFDF5 50%, #EBF5FF 100%);
        }
        
        /* Smooth transitions */
        .transition-smooth {
            transition: all 0.3s ease;
        }
        
        /* Input focus styles */
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(29, 155, 240, 0.2);
        }
        
        /* Button hover effect */
        .btn-hover:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(29, 155, 240, 0.3);
        }
        
        /* Card shadow */
        .card-shadow {
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }
        
        /* Illustration area gradient */
        .illustration-bg {
            background: linear-gradient(135deg, #1D9BF0 0%, #22C55E 100%);
        }
    </style>
</head>
<body class="login-gradient min-h-screen flex items-center justify-center p-4 md:p-8">
    
    <!-- Main Card -->
    <div class="w-full max-w-5xl card-shadow rounded-3xl overflow-hidden bg-white">
        <div class="flex flex-col lg:flex-row">
            
            <!-- Left Side - Login Form -->
            <div class="w-full lg:w-1/2 p-8 md:p-12">
                
                <!-- Logo & Header -->
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-staff-snake text-white text-xl"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-800">Reservasi Dokter</span>
                    </div>
                    
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                        Masuk ke Reservasi Dokter
                    </h1>
                    <p class="text-gray-500 text-sm">
                        Silakan login untuk melanjutkan
                    </p>
                </div>
                
                <!-- Error Alert -->
                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3">
                        <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                        <span class="text-red-700 text-sm">{{ session('error') }}</span>
                    </div>
                @endif
                
                <!-- Login Form -->
                <form method="POST" action="{{ route('login.perform') }}" class="space-y-5">
                    @csrf
                    
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-regular fa-envelope text-gray-400"></i>
                            </div>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                placeholder="masukkan email terdaftar"
                                class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl input-focus transition-smooth focus:bg-white focus:border-primary-500 focus:outline-none @error('email') border-red-500 @else @enderror"
                                required 
                                autofocus
                            >
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-500 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-400"></i>
                            </div>
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                placeholder="masukkan kata sandi"
                                class="w-full pl-12 pr-14 py-3.5 bg-gray-50 border border-gray-200 rounded-xl input-focus transition-smooth focus:bg-white focus:border-primary-500 focus:outline-none @error('password') border-red-500 @else @enderror"
                                required
                            >
                            <button 
                                type="button" 
                                id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-smooth"
                            >
                                <i class="fa-regular fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-500 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                id="remember"
                                class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                            >
                            <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                        </label>
                        <a href="#" class="text-sm text-primary-600 hover:text-primary-700 transition-smooth">
                            Lupa kata sandi?
                        </a>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full py-3.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl btn-hover transition-smooth flex items-center justify-center gap-2"
                    >
                        <i class="fa-solid fa-arrow-right-to-bracket"></i>
                        Masuk
                    </button>
                </form>
                
                <!-- Register Link -->
                <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                    <p class="text-gray-500 text-sm">
                        Belum punya akun? 
                        <a href="" class="text-primary-600 font-medium hover:text-primary-700 transition-smooth">
                            Daftar sebagai pasien
                        </a>
                    </p>
                </div>
            </div>
            
            <!-- Right Side - Illustration (Desktop Only) -->
            <div class="hidden lg:flex lg:w-1/2 illustration-bg p-12 flex-col justify-between text-white">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold mb-4">
                        Reservasi Dokter Online
                    </h2>
                    <p class="text-white/90 text-lg leading-relaxed">
                        Buat janji dengan dokter pilihan Anda بسهولة tanpa antre lama. 
                        Layanan kesehatan yang lebih mudah, cepat, dan praktis.”
                    </p>
                </div>
                
                <!-- Feature Icons -->
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-calendar-check text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">Booking Mudah</h3>
                            <p class="text-white/80 text-sm">Jadwalkan kunjungan dalam hitungan menit</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-clock text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">Tidak Perlu Antri</h3>
                            <p class="text-white/80 text-sm">Datang sesuai waktu yang ditentukan</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-file-medical text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">Rekam Medis Digital</h3>
                            <p class="text-white/80 text-sm">Riwayat kesehatan tersimpan aman</p>
                        </div>
                    </div>
                </div>
                
                <!-- Footer Quote -->
                <div class="mt-8 pt-6 border-t border-white/20">
                    <p class="text-white/80 italic text-sm">
                        "Kesehatan adalah kekayaan yang sebenarnya."
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- JavaScript for Password Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (togglePassword && passwordInput && eyeIcon) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    if (type === 'text') {
                        eyeIcon.classList.remove('fa-eye');
                        eyeIcon.classList.add('fa-eye-slash');
                    } else {
                        eyeIcon.classList.remove('fa-eye-slash');
                        eyeIcon.classList.add('fa-eye');
                    }
                });
            }
        });
    </script>
</body>
</html>
