<header class="w-full bg-white/95 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-50 transition-all duration-300">
    <div class="px-4 sm:px-6 md:px-8 lg:px-10 py-0 h-20 flex items-center justify-between">

        <!-- BAGIAN KIRI: BRANDING & IDENTITAS KLINIK -->
        <a href="{{ route('pasien.dashboard') }}" class="flex items-center gap-3 decoration-none text-none">
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 w-10 h-10 rounded-xl flex items-center justify-center shadow-md shadow-blue-200/50">
                <i class="fas fa-heartbeat text-white text-base"></i>
            </div>
            <div class="text-left">
                <span class="text-sm font-black text-slate-900 tracking-tight block leading-tight">MediKlinik</span>
                <span class="text-[9px] font-bold text-slate-400 tracking-widest font-mono uppercase block mt-0.5">
                    Portal JKN Pasien
                </span>
            </div>
        </a>

        <!-- BAGIAN TENGAH: NAVIGASI MENU UTAMA (DESKTOP) -->
        <nav class="hidden md:flex items-center gap-6 text-sm font-semibold">
            <a href="{{ route('pasien.dashboard') }}" class="relative group transition-all duration-200 ease-in-out {{ request()->routeIs('pasien.dashboard') ? 'text-blue-600 font-semibold' : 'text-slate-600 hover:text-blue-600' }}">
                Beranda
                <span class="absolute left-0 -bottom-1 h-0.5 w-full bg-blue-600 opacity-0 transition-all duration-200 ease-in-out group-hover:opacity-100 {{ request()->routeIs('pasien.dashboard') ? 'opacity-100' : '' }}"></span>
            </a>
            <a href="{{ route('pasien.reservasi.create') }}" class="relative group transition-all duration-200 ease-in-out {{ request()->routeIs('pasien.reservasi.create') ? 'text-blue-600 font-semibold' : 'text-slate-600 hover:text-blue-600' }}">
                Jadwal Praktik
                <span class="absolute left-0 -bottom-1 h-0.5 w-full bg-blue-600 opacity-0 transition-all duration-200 ease-in-out group-hover:opacity-100 {{ request()->routeIs('pasien.reservasi.create') ? 'opacity-100' : '' }}"></span>
            </a>
            <a href="{{ route('pasien.reservasi.history') }}" class="relative group transition-all duration-200 ease-in-out {{ request()->routeIs('pasien.reservasi.history') ? 'text-blue-600 font-semibold' : 'text-slate-600 hover:text-blue-600' }}">
                Riwayat Konsultasi
                <span class="absolute left-0 -bottom-1 h-0.5 w-full bg-blue-600 opacity-0 transition-all duration-200 ease-in-out group-hover:opacity-100 {{ request()->routeIs('pasien.reservasi.history') ? 'opacity-100' : '' }}"></span>
            </a>
        </nav>

        <!-- BAGIAN KANAN: DYNAMIC WIDGETS (NOTIFIKASI + DROP PROFILE) -->
        <div class="flex items-center gap-4">

            <!-- WAKTU LOKAL REALTIME (ESTETIKA ADMIN) -->
            <div class="hidden lg:flex items-center gap-2 bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-full text-xs font-medium text-slate-500 font-mono">
                <i class="far fa-clock text-slate-400"></i>
                <span id="nav-realtime-clock">{{ now()->format('H:i') }} WIB</span>
            </div>

            <!-- BELL NOTIFIKASI ELEGAN -->
            <div class="relative">
                <button type="button" id="bellBtn" aria-haspopup="true" aria-expanded="false"
                    class="group relative w-11 h-11 bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-slate-900 rounded-full flex items-center justify-center border border-slate-100 transition-all duration-200 ease-in-out transform hover:scale-105 active:scale-95 cursor-pointer">
                    <i class="far fa-bell text-sm transition-all duration-200 ease-in-out group-hover:-translate-y-0.5"></i>
                    <span id="notificationBadge" class="absolute top-1.5 right-1.5 w-4 h-4 bg-rose-500 border-2 border-white rounded-full flex items-center justify-center text-[8px] font-black text-white transition-all duration-200 ease-in-out">
                        2
                    </span>
                </button>

                <div id="bellDropdown" class="fixed mt-2 bg-white border border-slate-100 rounded-2xl shadow-xl p-3 hidden opacity-0 scale-95 transition-all duration-200 ease-in-out z-50" style="display: none; top: 64px; right: 16px; width: min(320px, calc(100vw - 32px));" role="menu" aria-label="Notifikasi">

                    <div class="flex items-start justify-between gap-3 mb-3">

                        <div>
                            <h4 class="text-xs font-black text-slate-700 uppercase tracking-wider">Notifikasi Terbaru</h4>
                            <p class="text-[11px] text-slate-500 mt-1">Ringkasan aktivitas terbaru Anda.</p>
                        </div>
                        <button type="button" id="markAsReadBtn" class="text-[11px] font-semibold text-slate-500 hover:text-slate-700 transition-all duration-200 ease-in-out">Tandai dibaca</button>
                    </div>
                    <div class="space-y-3">
                        <a href="{{ route('pasien.reservasi.history') }}" class="block rounded-2xl border border-slate-100 bg-slate-50/80 p-3 hover:bg-slate-100 transition-all duration-200 ease-in-out">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <p class="text-[12px] font-semibold text-slate-800">Reservasi #RES-88092 disetujui</p>
                                <span class="inline-flex h-2.5 w-2.5 rounded-full bg-rose-500"></span>
                            </div>
                            <p class="text-[11px] text-slate-500">Reservasi dengan dr. Yoga Firmansyah, Sp.PD untuk 28-05-2026.</p>
                        </a>
                        <div class="rounded-2xl border border-slate-100 p-3 hover:bg-slate-50 transition-all duration-200 ease-in-out">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <p class="text-[12px] font-semibold text-slate-800">Jadwal konsultasi besok</p>
                                <span class="text-[10px] text-slate-400">1 hari lalu</span>
                            </div>
                            <p class="text-[11px] text-slate-500">Konsultasi pukul 08:30 WIB di poliklinik rawat jalan.</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 p-3 hover:bg-slate-50 transition-all duration-200 ease-in-out">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <p class="text-[12px] font-semibold text-slate-800">Integrasi BPJS aktif</p>
                                <span class="text-[10px] text-slate-400">3 hari lalu</span>
                            </div>
                            <p class="text-[11px] text-slate-500">Integrasi rujukan BPJS Kesehatan aktif secara daring.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DROPDOWN USER PROFILE (VANILLA JS) -->
            <div class="relative">
                <button type="button" id="profileBtn" aria-haspopup="true" aria-expanded="false"
                    class="group flex items-center gap-2.5 bg-slate-50 border border-slate-200 hover:border-slate-300 px-3 py-2 rounded-xl transition-all duration-200 ease-in-out cursor-pointer hover:bg-slate-50 active:shadow-sm">

                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 font-bold text-xs flex items-center justify-center border border-blue-100 uppercase">
                        {{ substr(Auth::user()->name ?? 'AJ', 0, 2) }}
                    </div>

                    <div class="text-left hidden sm:block">
                        <span class="text-xs font-extrabold text-slate-800 block leading-tight">{{ Auth::user()->name ?? 'Alfa Jauhari' }}</span>
                        <span class="text-[9px] text-slate-400 font-bold uppercase block mt-0.5 tracking-wider">
                            ID: {{ Auth::user()->id ?? 'PAS-0014' }}
                        </span>
                    </div>

                    <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-all duration-200 ease-in-out group-hover:text-slate-600"></i>
                </button>

                <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white border border-slate-100 rounded-2xl shadow-xl p-3 opacity-0 scale-95 transition-all duration-200 ease-in-out origin-top-right z-50" style="display: none;" aria-label="Menu Profil">
                    <a href="{{ route('pasien.profile.edit') }}" class="flex items-center gap-3 rounded-2xl px-3 py-2 text-slate-700 hover:bg-slate-50 transition-all duration-200 ease-in-out">
                        <i class="far fa-user text-slate-400"></i>
                        <span class="text-sm">Atur Profil & BPJS</span>
                    </a>
                    <a href="{{ route('pasien.reservasi.history') }}" class="flex items-center gap-3 rounded-2xl px-3 py-2 text-slate-700 hover:bg-slate-50 transition-all duration-200 ease-in-out">
                        <i class="fas fa-notes-medical text-slate-400"></i>
                        <span class="text-sm">Riwayat Diagnosis</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 rounded-2xl px-3 py-2 text-slate-700 hover:bg-slate-50 transition-all duration-200 ease-in-out">
                        <i class="fas fa-life-ring text-slate-400"></i>
                        <span class="text-sm">Pusat Bantuan</span>
                    </a>
                    <hr class="border-slate-100 my-2">
                    <form action="/logout" method="POST" class="m-0">
                        @csrf
                        <button class="w-full flex items-center gap-2 rounded-2xl px-3 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50 transition-all duration-200 ease-in-out" type="submit">
                            <i class="fas fa-sign-out-alt text-rose-500"></i>
                            Keluar / Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- BUTTON BURGER UNTUK LAYAR MOBILE -->
            <button class="md:hidden w-10 h-10 bg-slate-50 text-slate-600 rounded-xl flex items-center justify-center border border-slate-100 transition-all duration-200 ease-in-out hover:bg-slate-100" id="mobileMenuBtn" type="button">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- PANEL NAVIGASI SELULER -->
    <div class="md:hidden border-t border-slate-100 bg-white px-4 py-3 hidden" id="mobileMenuPanel" style="display: none;">
        <div class="flex flex-col gap-2">
            <a href="{{ route('pasien.dashboard') }}" class="bg-blue-50 text-blue-600 px-3 py-2.5 rounded-lg text-xs font-bold">Beranda</a>
            <a href="{{ route('pasien.reservasi.create') }}" class="text-slate-600 hover:bg-slate-50 px-3 py-2.5 rounded-lg text-xs transition-all duration-200 ease-in-out">Jadwal Praktik</a>
            <a href="{{ route('pasien.reservasi.history') }}" class="text-slate-600 hover:bg-slate-50 px-3 py-2.5 rounded-lg text-xs transition-all duration-200 ease-in-out">Riwayat Konsultasi</a>
            <hr class="my-1 border-slate-100">
            <span class="text-[9px] text-slate-400 uppercase font-mono px-3">Simulasi Jam Sistem</span>
            <div class="px-3 text-xs font-semibold text-slate-500" id="mobileClock">{{ now()->format('H:i') }} WIB</div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bellBtn = document.getElementById('bellBtn');
        const bellDropdown = document.getElementById('bellDropdown');
        const profileBtn = document.getElementById('profileBtn');
        const profileDropdown = document.getElementById('profileDropdown');
        const mobileBtn = document.getElementById('mobileMenuBtn');
        const mobilePanel = document.getElementById('mobileMenuPanel');
        const notificationBadge = document.getElementById('notificationBadge');
        const markAsReadBtn = document.getElementById('markAsReadBtn');

        function showDropdown(el) {
            if (!el) return;
            el.style.display = 'block';
            el.classList.remove('hidden', 'opacity-0', 'scale-95');
            el.classList.add('opacity-100', 'scale-100');
        }

        function hideDropdown(el) {
            if (!el) return;
            el.classList.add('hidden', 'opacity-0', 'scale-95');
            el.classList.remove('opacity-100', 'scale-100');
            el.style.display = 'none';
        }

        function closeAll() {
            hideDropdown(bellDropdown);
            hideDropdown(profileDropdown);
            if (bellBtn) bellBtn.setAttribute('aria-expanded', 'false');
            if (profileBtn) profileBtn.setAttribute('aria-expanded', 'false');
        }

        function toggleDropdown(button, dropdown) {
            if (!button || !dropdown) return;
            const isHidden = dropdown.classList.contains('hidden');
            closeAll();
            if (isHidden) {
                showDropdown(dropdown);
                button.setAttribute('aria-expanded', 'true');
            }
        }

        if (bellBtn) {
            bellBtn.addEventListener('click', function (event) {
                event.stopPropagation();
                toggleDropdown(bellBtn, bellDropdown);
            });
        }

        if (profileBtn) {
            profileBtn.addEventListener('click', function (event) {
                event.stopPropagation();
                toggleDropdown(profileBtn, profileDropdown);
            });
        }

        if (markAsReadBtn) {
            markAsReadBtn.addEventListener('click', function (event) {
                event.stopPropagation();
                if (notificationBadge) {
                    notificationBadge.style.opacity = '0.3';
                    notificationBadge.style.transform = 'scale(0.75)';
                }
            });
        }

        document.addEventListener('click', function (event) {
            if (!event.target.closest('#bellBtn') && !event.target.closest('#bellDropdown') && !event.target.closest('#profileBtn') && !event.target.closest('#profileDropdown')) {
                closeAll();
            }
        });

        [bellDropdown, profileDropdown].forEach(function (dropdown) {
            if (dropdown) {
                dropdown.addEventListener('click', function (event) {
                    event.stopPropagation();
                });
            }
        });

        if (mobileBtn && mobilePanel) {
            mobileBtn.addEventListener('click', function (event) {
                event.stopPropagation();
                if (mobilePanel.classList.contains('hidden')) {
                    mobilePanel.classList.remove('hidden');
                    mobilePanel.style.display = 'block';
                } else {
                    mobilePanel.classList.add('hidden');
                    mobilePanel.style.display = 'none';
                }
            });

            mobilePanel.addEventListener('click', function (event) {
                event.stopPropagation();
            });
        }
    });
</script>

