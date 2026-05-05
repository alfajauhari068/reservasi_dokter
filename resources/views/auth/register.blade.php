<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar - Reservasi Dokter</title>
    
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
                            600: '#1D9BF0',
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
                            600: '#22C55E',
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
        body { font-family: 'Inter', sans-serif; }
        .register-gradient { background: linear-gradient(135deg, #EBF5FF 0%, #ECFDF5 50%, #EBF5FF 100%); }
        .transition-smooth { transition: all 0.3s ease; }
        .input-focus:focus { box-shadow: 0 0 0 3px rgba(29, 155, 240, 0.2); }
        .btn-hover:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(29, 155, 240, 0.3); }
        .card-shadow { box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08); }
        .illustration-bg { background: linear-gradient(135deg, #1D9BF0 0%, #22C55E 100%); }
    </style>
</head>
<body class="register-gradient min-h-screen flex items-center justify-center p-4 md:p-8">
    
    <!-- Main Card -->
    <div class="w-full max-w-5xl card-shadow rounded-3xl overflow-hidden bg-white">
        <div class="flex flex-col lg:flex-row">
            
            <!-- Left Side - Register Form -->
            <div class="w-full lg:w-1/2 p-8 md:p-12">
                
                <!-- Logo & Header -->
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-user-plus text-white text-xl"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-800">Reservasi Dokter</span>
                    </div>
                    
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                        Daftar Akun Pasien
                    </h1>
                    <p class="text-gray-500 text-sm">
                        Buat akun untuk mulai reservasi dokter
                    </p>
                </div>
                
                <!-- Error Alert -->
                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3">
                        <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                        <span class="text-red-700 text-sm">{{ session('error') }}</span>
                    </div>
                @endif
                
                <!-- Register Form -->
                <form method="POST" action="{{ route('register.perform') }}" class="space-y-5">
                    @csrf
                    
                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-user text-gray-400"></i>
                            </div>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Nama lengkap sesuai KTP"
                                class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl input-focus transition-smooth focus:bg-white focus:border-primary-500 @error('name') border-red-500 @enderror"
                                required>
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-500 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-regular fa-envelope text-gray-400"></i>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com"
                                class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl input-focus transition-smooth focus:bg-white focus:border-primary-500 @error('email') border-red-500 @enderror"
                                required>
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-500 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Password Fields -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-400"></i>
                            </div>
                            <input id="password" type="password" name="password" placeholder="Minimal 8 karakter"
                                class="w-full pl-12 pr-14 py-3.5 bg-gray-50 border border-gray-200 rounded-xl input-focus transition-smooth focus:bg-white focus:border-primary-500 @error('password') border-red-500 @enderror"
                                required>
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                                <i class="fa-regular fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-500 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Kata Sandi</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Ulangi kata sandi"
                            class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl input-focus transition-smooth focus:bg-white focus:border-primary-500 @error('password_confirmation') border-red-500 @enderror"
                            required>
                    </div>
                    
                    <!-- Phone Field -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-phone text-gray-400"></i>
                            </div>
                            <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" placeholder="081234567890"
                                class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl input-focus transition-smooth focus:bg-white focus:border-primary-500 @error('phone') border-red-500 @enderror"
                                required>
                        </div>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-500 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Date of Birth -->
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-cake-candles text-gray-400"></i>
                            </div>
                            <input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                                class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl input-focus transition-smooth focus:bg-white focus:border-primary-500 @error('date_of_birth') border-red-500 @enderror"
                                required>
                        </div>
                        @error('date_of_birth')
                            <p class="mt-1 text-sm text-red-500 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Gender -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                        <div class="flex gap-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="gender" value="male" {{ old('gender') == 'male' ? 'checked' : '' }} class="w-4 h-4 text-primary-600 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Laki-laki</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="gender" value="female" {{ old('gender') == 'female' ? 'checked' : '' }} class="w-4 h-4 text-primary-600 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Perempuan</span>
                            </label>
                        </div>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-500 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Address (Optional) -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat (Opsional)</label>
                        <textarea id="address" name="address" rows="2" placeholder="Alamat lengkap domisili"
                            class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl input-focus transition-smooth focus:bg-white focus:border-primary-500 @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-500 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full py-3.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl btn-hover transition-smooth flex items-center justify-center gap-2">
                        <i class="fa-solid fa-user-plus"></i>
                        Daftar Akun
                    </button>
                </form>
                
                <!-- Login Link -->
                <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                    <p class="text-gray-500 text-sm">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-primary-600 font-medium hover:text-primary-700 transition-smooth">
                            Masuk sekarang
                        </a>
                    </p>
                </div>
            </div>
            
            <!-- Right Side - Illustration (Desktop Only) -->
            <div class="hidden lg:flex lg:w-1/2 illustration-bg p-12 flex-col justify-between text-white">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold mb-4">Mulai Reservasi Mudah</h2>
                    <p class="text-white/90 text-lg leading-relaxed">
                        Daftarkan diri Anda sekarang dan nikmati layanan reservasi dokter 
                        tanpa antrian panjang, kapan saja dan di mana saja.
                    </p>
                </div>
                
                <!-- Features -->
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-calendar-check text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">Booking Instan</h3>
                            <p class="text-white/80 text-sm">Pilih dokter & jadwal favorit</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-sms text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">Notifikasi Otomatis</h3>
                            <p class="text-white/80 text-sm">Ingatkan jadwal periksa Anda</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-file-medical-alt text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">Riwayat Lengkap</h3>
                            <p class="text-white/80 text-sm">Rekam medis digital terjaga</p>
                        </div>
                    </div>
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
                    eyeIcon.classList.toggle('fa-eye');
                    eyeIcon.classList.toggle('fa-eye-slash');
                });
            }
        });
    </script>
</body>
</html>
