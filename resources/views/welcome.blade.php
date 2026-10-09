<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaboRa — Sign In</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        body { -webkit-font-smoothing: antialiased; }
        .grid-bg {
            background-image:
                linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
            background-size: 32px 32px;
        }
        .fade-in { animation: fadeIn .5s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 sm:p-6 antialiased">

    <div class="w-full max-w-5xl bg-white rounded-2xl border border-slate-200 overflow-hidden grid md:grid-cols-2 fade-in">

        <!-- LEFT: Branding -->
        <div class="relative bg-slate-900 p-10 sm:p-14 flex flex-col justify-between overflow-hidden">
            <div class="absolute inset-0 grid-bg"></div>
            <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-indigo-500/10 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full bg-slate-500/10 blur-3xl"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-2.5 mb-10">
                    <div class="w-8 h-8 rounded-lg bg-white/10 border border-white/15 flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <span class="text-white font-semibold tracking-tight text-[15px]">LaboRa</span>
                </div>

                <h1 class="text-white text-3xl sm:text-4xl font-semibold tracking-tight leading-[1.15] mb-4">
                    Kelola inventaris lab<br>tanpa ribet.
                </h1>
                <p class="text-slate-400 text-sm leading-relaxed max-w-sm">
                    Platform terpadu untuk pemantauan alat, peminjaman, denda keterlambatan, dan pelaporan kerusakan perangkat laboratorium.
                </p>
            </div>

            <div class="relative z-10 mt-12 flex items-center gap-4 text-xs text-slate-500">
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    Sistem aktif
                </span>
                <span class="text-slate-700">•</span>
                <span>v1.0.0</span>
            </div>
        </div>

        <!-- RIGHT: Login Form -->
        <div class="p-10 sm:p-14 flex flex-col justify-center bg-white">
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-slate-900 tracking-tight">Masuk ke akun</h2>
                <p class="text-sm text-slate-500 mt-1.5">Gunakan kredensial institusi Anda untuk melanjutkan.</p>
            </div>

            <form id="loginForm" class="space-y-5">
                <div>
                    <label for="email" class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Email</label>
                    <div class="relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <input type="email" id="email" value="admin@lab.com" required
                            class="w-full pl-10 pr-4 py-2.5 text-sm text-slate-900 bg-white border border-slate-200 rounded-lg placeholder-slate-400 focus:outline-none focus:border-slate-900 focus:ring-2 focus:ring-slate-900/10 transition-all">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Kata Sandi</label>
                    <div class="relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input type="password" id="password" value="LabIoT2026!" required autocomplete="off"
                            class="w-full pl-10 pr-4 py-2.5 text-sm text-slate-900 bg-white border border-slate-200 rounded-lg placeholder-slate-400 focus:outline-none focus:border-slate-900 focus:ring-2 focus:ring-slate-900/10 transition-all">
                    </div>
                </div>

                <div id="errorMessage" class="hidden bg-red-50 text-red-700 rounded-lg p-3 text-sm border border-red-100 flex items-start gap-2">
                    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span id="errorText">Kredensial tidak valid.</span>
                </div>

                <button type="submit" id="submitBtn"
                    class="w-full bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg py-2.5 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2">
                    Masuk
                </button>
            </form>

            <details class="mt-8 text-xs text-slate-500 group">
                <summary class="cursor-pointer select-none hover:text-slate-700 transition-colors inline-flex items-center gap-1.5">
                    <svg class="w-3 h-3 transition-transform group-open:rotate-90" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                    Kredensial demo
                </summary>
                <div class="mt-3 pl-4 border-l border-slate-200 space-y-1.5 font-mono text-[11px]">
                    <div class="flex justify-between gap-4"><span class="text-slate-400">Admin</span><span class="text-slate-700">admin@lab.com</span></div>
                    <div class="flex justify-between gap-4"><span class="text-slate-400">Mahasiswa</span><span class="text-slate-700">mhs1@lab.com</span></div>
                    <div class="flex justify-between gap-4"><span class="text-slate-400">Password</span><span class="text-slate-700">LabIoT2026!</span></div>
                </div>
            </details>
        </div>
    </div>

    <script>
        const formLogin = document.getElementById('loginForm');
        const inputEmail = document.getElementById('email');
        const inputPassword = document.getElementById('password');
        const alertError = document.getElementById('errorMessage');
        const errorText = document.getElementById('errorText');
        const btnSubmit = document.getElementById('submitBtn');

        formLogin.addEventListener('submit', async function(event) {
            event.preventDefault();

            alertError.classList.add('hidden');
            btnSubmit.innerHTML = 'Memverifikasi...';
            btnSubmit.disabled = true;
            btnSubmit.classList.add('opacity-70', 'cursor-not-allowed');

            try {
                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ email: inputEmail.value, password: inputPassword.value })
                });

                const data = await response.json();

                if (response.ok) {
                    localStorage.setItem('auth_token', data.access_token);
                    localStorage.setItem('user', JSON.stringify(data.data));
                    window.location.href = '/dashboard';
                } else {
                    errorText.textContent = data.message || 'Kredensial tidak valid. Silakan coba lagi.';
                    alertError.classList.remove('hidden');
                }
            } catch (error) {
                errorText.textContent = 'Terjadi malfungsi pada koneksi ke peladen utama.';
                alertError.classList.remove('hidden');
            } finally {
                btnSubmit.innerHTML = 'Masuk';
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-70', 'cursor-not-allowed');
            }
        });
    </script>
</body>
</html>