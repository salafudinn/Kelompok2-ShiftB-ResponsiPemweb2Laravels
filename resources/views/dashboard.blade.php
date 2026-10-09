<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — LaboRa</title>
    <script>
        // Terapkan tema sebelum body di-render untuk hindari flash
        (function() {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'dark' || (!saved && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .mono { font-family: 'JetBrains Mono', ui-monospace, monospace; font-feature-settings: 'tnum'; }
        body {
            -webkit-font-smoothing: antialiased;
            background-color: #FAFAF9;
            background-image:
                radial-gradient(at 0% 0%, rgba(99,102,241,0.04) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(20,184,166,0.03) 0px, transparent 50%);
            background-attachment: fixed;
            transition: background-color .35s ease, color .35s ease;
        }

        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: none;
        }
        select::-ms-expand { display: none; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translate3d(0, 10px, 0); }
            to   { opacity: 1; transform: translate3d(0, 0, 0); }
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideDown {
            from { opacity: 0; transform: translate3d(0, -6px, 0); }
            to   { opacity: 1; transform: translate3d(0, 0, 0); }
        }
        @keyframes shimmer {
            0%   { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .anim-fade-up   { animation: fadeUp .45s cubic-bezier(.16,1,.3,1) both; }
        .anim-fade-in   { animation: fadeIn .35s ease-out both; }
        .anim-slide-down{ animation: slideDown .3s ease-out both; }

        @keyframes headerIn {
            from { opacity: 0; transform: translate3d(0, 14px, 0); }
            to   { opacity: 1; transform: translate3d(0, 0, 0); }
        }
        .header-in-1 { animation: headerIn .7s cubic-bezier(.16,1,.3,1) .05s both; }
        .header-in-2 { animation: headerIn .7s cubic-bezier(.16,1,.3,1) .18s both; }
        .header-in-3 { animation: headerIn .7s cubic-bezier(.16,1,.3,1) .32s both; }
        .header-in-4 { animation: headerIn .7s cubic-bezier(.16,1,.3,1) .46s both; }

        .stagger > * { animation: fadeUp .55s cubic-bezier(.16,1,.3,1) both; }
        .stagger > *:nth-child(1) { animation-delay: .60s; }
        .stagger > *:nth-child(2) { animation-delay: .66s; }
        .stagger > *:nth-child(3) { animation-delay: .72s; }
        .stagger > *:nth-child(4) { animation-delay: .78s; }

        .tab-btn {
            position: relative;
            padding: 7px 16px;
            font-size: 13px;
            font-weight: 500;
            color: #64748B;
            border-radius: 7px;
            transition: color .18s ease, background-color .18s ease;
            white-space: nowrap;
        }
        .tab-btn:hover { color: #0F172A; }
        .tab-btn.active {
            background: #0F172A;
            color: #FFFFFF;
            box-shadow: 0 2px 8px -2px rgba(15,23,42,.35), inset 0 1px 0 rgba(255,255,255,.08);
        }

        .status-dot {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 12px; font-weight: 500; letter-spacing: .01em;
            padding: 3px 9px 3px 8px;
            border-radius: 999px;
            background: rgba(241,245,249,.7);
            border: 1px solid rgba(226,232,240,.8);
            transition: background-color .25s ease, border-color .25s ease;
        }
        .status-dot::before {
            content: ''; width: 6px; height: 6px; border-radius: 999px; flex-shrink: 0;
        }
        .badge-PENDING     { color: #B45309; } .badge-PENDING::before     { background: #F59E0B; box-shadow: 0 0 0 3px rgba(245,158,11,.15); }
        .badge-APPROVED    { color: #047857; } .badge-APPROVED::before    { background: #10B981; box-shadow: 0 0 0 3px rgba(16,185,129,.15); }
        .badge-REJECTED    { color: #B91C1C; } .badge-REJECTED::before    { background: #EF4444; box-shadow: 0 0 0 3px rgba(239,68,68,.15); }
        .badge-ON_LOAN     { color: #1D4ED8; } .badge-ON_LOAN::before     { background: #3B82F6; box-shadow: 0 0 0 3px rgba(59,130,246,.15); }
        .badge-OVERDUE     { color: #B91C1C; } .badge-OVERDUE::before     { background: #EF4444; box-shadow: 0 0 0 3px rgba(239,68,68,.2); }
        .badge-RETURNED    { color: #475569; } .badge-RETURNED::before    { background: #94A3B8; }
        .badge-REPORTED    { color: #C2410C; } .badge-REPORTED::before    { background: #F97316; box-shadow: 0 0 0 3px rgba(249,115,22,.15); }
        .badge-IN_REPAIR   { color: #1D4ED8; } .badge-IN_REPAIR::before   { background: #3B82F6; box-shadow: 0 0 0 3px rgba(59,130,246,.15); }
        .badge-RESOLVED    { color: #047857; } .badge-RESOLVED::before    { background: #10B981; box-shadow: 0 0 0 3px rgba(16,185,129,.15); }
        .badge-DISCARDED   { color: #B91C1C; } .badge-DISCARDED::before   { background: #EF4444; box-shadow: 0 0 0 3px rgba(239,68,68,.15); }
        .badge-AVAILABLE   { color: #047857; } .badge-AVAILABLE::before   { background: #10B981; box-shadow: 0 0 0 3px rgba(16,185,129,.15); }
        .badge-MAINTENANCE { color: #B91C1C; } .badge-MAINTENANCE::before { background: #EF4444; box-shadow: 0 0 0 3px rgba(239,68,68,.15); }

        .skeleton {
            background: linear-gradient(90deg, #F1F5F9 25%, #E2E8F0 50%, #F1F5F9 75%);
            background-size: 200% 100%;
            animation: shimmer 1.6s infinite linear;
        }

        .card-hover {
            transition: transform .2s cubic-bezier(.16,1,.3,1), border-color .2s ease, box-shadow .2s ease, background-color .25s ease;
            will-change: transform;
        }
        .card-hover:hover {
            transform: translate3d(0, -2px, 0);
            border-color: #CBD5E1;
            box-shadow: 0 12px 24px -12px rgba(15,23,42,.10), 0 4px 8px -4px rgba(15,23,42,.05);
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #0F172A;
            box-shadow: 0 0 0 4px rgba(15,23,42,.08);
        }

        .btn-ajukan {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .02em;
            color: #FFFFFF;
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
            border-radius: 9px;
            overflow: hidden;
            transition: transform .2s cubic-bezier(.16,1,.3,1), box-shadow .25s ease, background .3s ease;
            box-shadow: 0 2px 4px -1px rgba(15,23,42,.15), 0 4px 12px -4px rgba(15,23,42,.25), inset 0 1px 0 rgba(255,255,255,.06);
            will-change: transform;
            cursor: pointer;
            border: none;
        }
        .btn-ajukan:hover {
            transform: translate3d(0, -1px, 0);
            background: linear-gradient(135deg, #1E293B 0%, #334155 100%);
            box-shadow: 0 4px 8px -2px rgba(15,23,42,.2), 0 12px 24px -8px rgba(15,23,42,.35), inset 0 1px 0 rgba(255,255,255,.08);
        }
        .btn-ajukan:active { transform: translate3d(0, 0, 0) scale(.98); }
        .btn-ajukan::before {
            content: '';
            position: absolute; top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.18), transparent);
            transition: left .6s ease;
        }
        .btn-ajukan:hover::before { left: 100%; }

        .btn-edit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 600;
            color: #B45309;
            background: #FFFBEB;
            border: 1px solid #FDE68A;
            border-radius: 7px;
            transition: all .2s cubic-bezier(.16,1,.3,1);
            cursor: pointer;
        }
        .btn-edit:hover {
            color: #92400E;
            background: #FEF3C7;
            border-color: #FCD34D;
            transform: translate3d(0, -1px, 0);
            box-shadow: 0 4px 12px -6px rgba(245,158,11,.45);
        }
        .btn-edit:active { transform: scale(.97); }

        .btn-hapus {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 600;
            color: #B91C1C;
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 7px;
            transition: all .2s cubic-bezier(.16,1,.3,1);
            cursor: pointer;
        }
        .btn-hapus:hover {
            color: #991B1B;
            background: #FEE2E2;
            border-color: #FCA5A5;
            transform: translate3d(0, -1px, 0);
            box-shadow: 0 4px 12px -6px rgba(220,38,38,.45);
        }
        .btn-hapus:active { transform: scale(.97); }

        /* ===== Theme toggle button ===== */
        .theme-toggle {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            color: #64748B;
            background: transparent;
            border: 1px solid transparent;
            transition: all .2s ease;
            cursor: pointer;
        }
        .theme-toggle:hover {
            color: #0F172A;
            background: #F1F5F9;
        }
        .theme-toggle:active { transform: scale(.94); }

        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; border: 2px solid transparent; background-clip: padding-box; }
        ::-webkit-scrollbar-thumb:hover { background: #94A3B8; background-clip: padding-box; border: 2px solid transparent; }

        /* ======================================================
           DARK MODE
           ====================================================== */
        html.dark body {
            background-color: #070B14;
            background-image:
                radial-gradient(at 0% 0%, rgba(99,102,241,0.08) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(20,184,166,0.05) 0px, transparent 50%);
            color: #CBD5E1;
        }
        html.dark nav {
            background-color: rgba(11,17,28,.78) !important;
            border-bottom-color: rgba(30,41,59,.7) !important;
        }
        html.dark .theme-toggle:hover {
            color: #F1F5F9;
            background: #1E293B;
        }

        /* Surfaces */
        html.dark .bg-white { background-color: #101827 !important; }
        html.dark .bg-white\/70 { background-color: rgba(16,24,39,.72) !important; }
        html.dark .bg-white\/80 { background-color: rgba(16,24,39,.82) !important; }
        html.dark .bg-slate-50 { background-color: #0B111E !important; }
        html.dark .bg-slate-100 { background-color: #1E293B !important; }
        html.dark .bg-slate-100\/80 { background-color: rgba(30,41,59,.8) !important; }
        html.dark .bg-slate-50\/40 { background-color: rgba(11,17,30,.5) !important; }
        html.dark .bg-slate-50\/50 { background-color: rgba(11,17,30,.5) !important; }
        html.dark .bg-slate-50\/60 { background-color: rgba(11,17,30,.6) !important; }
        html.dark .bg-slate-900 { background-color: #4F46E5 !important; }
        html.dark .bg-slate-900:hover,
        html.dark .hover\:bg-slate-800:hover { background-color: #4338CA !important; }

        /* Borders */
        html.dark .border-slate-200 { border-color: #1E293B !important; }
        html.dark .border-slate-200\/60 { border-color: rgba(30,41,59,.6) !important; }
        html.dark .border-slate-200\/70 { border-color: rgba(30,41,59,.7) !important; }
        html.dark .border-slate-100 { border-color: #1E293B !important; }
        html.dark .border-dashed { border-color: #1E293B !important; }
        html.dark .divide-slate-100 > * + * { border-color: #1E293B !important; }

        /* Text */
        html.dark .text-slate-900 { color: #F1F5F9 !important; }
        html.dark .text-slate-800 { color: #E2E8F0 !important; }
        html.dark .text-slate-700 { color: #CBD5E1 !important; }
        html.dark .text-slate-600 { color: #94A3B8 !important; }
        html.dark .text-slate-500 { color: #94A3B8 !important; }
        html.dark .text-slate-400 { color: #64748B !important; }
        html.dark .text-slate-300 { color: #475569 !important; }
        html.dark .text-blue-900 { color: #93C5FD !important; }

        /* Inputs */
        html.dark input, html.dark select, html.dark textarea {
            background-color: #0B111E !important;
            border-color: #1E293B !important;
            color: #E2E8F0 !important;
        }
        html.dark input::placeholder, html.dark textarea::placeholder { color: #475569 !important; }
        html.dark input:focus, html.dark select:focus, html.dark textarea:focus {
            border-color: #4F46E5 !important;
            box-shadow: 0 0 0 4px rgba(79,70,229,.18) !important;
        }

        /* Skeleton */
        html.dark .skeleton {
            background: linear-gradient(90deg, #1E293B 25%, #334155 50%, #1E293B 75%);
            background-size: 200% 100%;
        }

        /* CTA Button */
        html.dark .btn-ajukan {
            background: linear-gradient(135deg, #4F46E5 0%, #6366F1 100%);
            box-shadow: 0 2px 4px -1px rgba(79,70,229,.35), 0 4px 12px -4px rgba(79,70,229,.4), inset 0 1px 0 rgba(255,255,255,.1);
        }
        html.dark .btn-ajukan:hover {
            background: linear-gradient(135deg, #6366F1 0%, #818CF8 100%);
            box-shadow: 0 4px 8px -2px rgba(79,70,229,.45), 0 12px 24px -8px rgba(79,70,229,.5);
        }

        /* Edit button (kuning) */
        html.dark .btn-edit {
            color: #FCD34D;
            background: rgba(245,158,11,.1);
            border-color: rgba(245,158,11,.28);
        }
        html.dark .btn-edit:hover {
            color: #FDE68A;
            background: rgba(245,158,11,.18);
            border-color: rgba(245,158,11,.45);
        }

        /* Delete button (merah) */
        html.dark .btn-hapus {
            color: #FCA5A5;
            background: rgba(239,68,68,.1);
            border-color: rgba(239,68,68,.28);
        }
        html.dark .btn-hapus:hover {
            color: #FECACA;
            background: rgba(239,68,68,.18);
            border-color: rgba(239,68,68,.45);
        }

        /* Tabs */
        html.dark .tab-btn { color: #94A3B8; }
        html.dark .tab-btn:hover { color: #F1F5F9; }
        html.dark .tab-btn.active {
            background: #4F46E5;
            color: #FFFFFF;
            box-shadow: 0 2px 8px -2px rgba(79,70,229,.5), inset 0 1px 0 rgba(255,255,255,.1);
        }

        /* Status dots */
        html.dark .status-dot {
            background: rgba(30,41,59,.65);
            border-color: rgba(51,65,85,.8);
        }

        /* Colored icon backgrounds */
        html.dark .bg-indigo-50 { background-color: rgba(79,70,229,.15) !important; }
        html.dark .bg-blue-50 { background-color: rgba(59,130,246,.15) !important; }
        html.dark .bg-red-50 { background-color: rgba(239,68,68,.15) !important; }
        html.dark .bg-amber-50 { background-color: rgba(245,158,11,.15) !important; }
        html.dark .bg-emerald-50 { background-color: rgba(16,185,129,.15) !important; }

        /* Card hover */
        html.dark .card-hover:hover {
            border-color: #334155;
            box-shadow: 0 12px 24px -12px rgba(0,0,0,.6), 0 4px 8px -4px rgba(0,0,0,.3);
        }

        /* Navbar specific */
        html.dark .nav-active-dot { background-color: #F1F5F9; }

        /* Logo & avatar contrast in dark */
        html.dark #userAvatar {
            background: linear-gradient(135deg, #4F46E5 0%, #6366F1 100%);
            color: #fff;
        }
        html.dark .ring-white { --tw-ring-color: #1E293B; }

        /* Hover background */
        html.dark .hover\:bg-slate-50:hover { background-color: #1E293B !important; }
        html.dark .hover\:bg-slate-100:hover { background-color: #1E293B !important; }
        html.dark .hover\:bg-red-50:hover { background-color: rgba(239,68,68,.15) !important; }
        html.dark .hover\:text-slate-700:hover { color: #E2E8F0 !important; }
        html.dark .hover\:text-slate-800:hover { color: #F1F5F9 !important; }
        html.dark .hover\:text-slate-900:hover { color: #F1F5F9 !important; }

        /* Modal */
        html.dark #modalPinjam > div, html.dark #modalKit > div,
        html.dark #modalAssistantAccounts > div, html.dark #modalAssistantAccountForm > div,
        html.dark #modalDamageImages > div {
            border-color: #1E293B !important;
        }

        /* Scrollbar dark */
        html.dark ::-webkit-scrollbar-thumb { background: #334155; }
        html.dark ::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* Decorative blobs */
        html.dark .fixed.inset-0.-z-10 > div:first-child { background-color: rgba(79,70,229,.08); }
        html.dark .fixed.inset-0.-z-10 > div:last-child { background-color: rgba(20,184,166,.05); }

        /* Toast */
        html.dark #toastContainer > div {
            background-color: #101827 !important;
            border-color: #1E293B !important;
        }
        html.dark #toastContainer > div span:last-child { color: #E2E8F0 !important; }
    </style>
</head>
<body class="text-slate-800 hidden" id="appBody">

    <div class="fixed inset-0 -z-10 pointer-events-none overflow-hidden">
        <div class="absolute -top-40 -right-40 w-[500px] h-[500px] rounded-full bg-indigo-500/5 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-[500px] h-[500px] rounded-full bg-teal-500/5 blur-3xl"></div>
    </div>

    <nav class="bg-white/70 backdrop-blur-xl border-b border-slate-200/70 sticky top-0 z-40 anim-slide-down">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-slate-900 flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div class="leading-tight">
                        <p class="text-slate-900 font-semibold tracking-tight text-[14px]">LaboRa</p>
                        <p class="text-[10px] text-slate-400 tracking-wide uppercase">Laboratory Inventory</p>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    <button id="btnKelolaAsisten" onclick="bukaKelolaAsisten()"
                        class="hidden items-center gap-2 px-3 py-2 text-xs font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors"
                        title="Kelola akun asisten lab">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m12-13a4 4 0 11-8 0 4 4 0 018 0zm2 4h6m-3-3v6"/>
                        </svg>
                        <span class="hidden sm:inline">Kelola Asisten</span>
                    </button>
                    <div class="text-right hidden sm:block leading-tight mr-1">
                        <p id="userName" class="text-[13px] font-medium text-slate-900"></p>
                        <p id="userRole" class="text-[11px] text-slate-500 mt-0.5"></p>
                    </div>
                    <div id="userAvatar" class="w-9 h-9 rounded-full bg-gradient-to-br from-slate-800 to-slate-900 text-white flex items-center justify-center font-medium text-xs ring-2 ring-white shadow-sm"></div>

                    <div class="w-px h-5 bg-slate-200 mx-0.5 hidden sm:block"></div>

                    <!-- THEME TOGGLE -->
                    <button onclick="toggleTheme()" class="theme-toggle" title="Ganti tema" aria-label="Ganti tema">
                        <!-- Sun (muncul di dark mode) -->
                        <svg id="iconSun" class="w-[18px] h-[18px] hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <!-- Moon (muncul di light mode) -->
                        <svg id="iconMoon" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>

                    <button onclick="logout()" class="text-slate-500 hover:text-red-600 transition-colors p-2 rounded-lg hover:bg-red-50" title="Logout" aria-label="Logout">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-7">

        <header>
            <p class="text-[11px] text-slate-400 font-semibold tracking-[.14em] uppercase mb-3 header-in-1">Dashboard</p>
            <h1 class="text-3xl sm:text-4xl lg:text-[42px] font-semibold text-slate-900 tracking-tight leading-[1.1] header-in-2">
                Selamat datang, <span id="greetName" class="text-blue-900">—</span>
            </h1>
            <p class="text-sm sm:text-[15px] text-slate-500 mt-3 header-in-3">
                Kelola inventaris, peminjaman, dan pelaporan insiden laboratorium.
            </p>
        </header>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 stagger">
            <div class="bg-white rounded-xl border border-slate-200 p-4 sm:p-5 relative overflow-hidden">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
                <p class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Total Perangkat</p>
                <p id="statTotalKits" class="text-2xl font-semibold text-slate-900 mono mt-1 tabular-nums">—</p>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-4 sm:p-5 relative overflow-hidden">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Sedang Dipinjam</p>
                <p id="statActiveLoans" class="text-2xl font-semibold text-slate-900 mono mt-1 tabular-nums">—</p>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-4 sm:p-5 relative overflow-hidden">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-9 h-9 rounded-lg bg-red-50 text-red-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Terlambat</p>
                <p id="statOverdue" class="text-2xl font-semibold text-slate-900 mono mt-1 tabular-nums">—</p>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-4 sm:p-5 relative overflow-hidden">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Perlu Perbaikan</p>
                <p id="statMaintenance" class="text-2xl font-semibold text-slate-900 mono mt-1 tabular-nums">—</p>
            </div>
        </div>

        <div class="header-in-4 flex justify-center">
            <div class="inline-flex bg-slate-100/80 backdrop-blur-sm rounded-lg p-1 gap-1 border border-slate-200/60">
                <button onclick="bukaTab('tab-katalog')" class="tab-btn active" id="btn-katalog">Katalog</button>
                <button onclick="bukaTab('tab-riwayat')" class="tab-btn" id="btn-riwayat">Riwayat</button>
                <button onclick="bukaTab('tab-kerusakan')" class="tab-btn" id="btn-kerusakan">Insiden</button>
                <button onclick="bukaTab('tab-kelola')" class="tab-btn hidden" id="btn-kelola">Otorisasi</button>
            </div>
        </div>

        <section id="tab-katalog" class="anim-fade-in">
            <div class="flex flex-col sm:flex-row gap-2.5 mb-6">
                <div class="relative flex-1">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input id="searchInput" type="text" placeholder="Cari kode atau nama perangkat..." oninput="cariKit()"
                        class="w-full pl-10 pr-4 py-2.5 text-sm bg-white border border-slate-200 rounded-lg placeholder-slate-400 transition-all">
                </div>

                <div class="relative sm:w-48">
                    <select id="filterKategori" onchange="cariKit()"
                        class="w-full appearance-none py-2.5 pl-3.5 pr-10 text-sm bg-white border border-slate-200 rounded-lg text-slate-700 transition-all cursor-pointer">
                        <option value="">Semua Kategori</option>
                        <option value="Microcontroller">Microcontroller</option>
                        <option value="Microcomputer">Microcomputer</option>
                        <option value="Sensor Pack">Sensor Pack</option>
                        <option value="Actuator Pack">Actuator Pack</option>
                    </select>
                    <svg class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>

                <button id="btnTambahKit" onclick="bukaModalKit()"
                    class="hidden bg-slate-900 hover:bg-slate-800 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-all whitespace-nowrap shadow-sm hover:shadow-md active:scale-[.98]">
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Perangkat Baru
                    </span>
                </button>
            </div>

            <div id="kitsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div class="skeleton rounded-xl h-44"></div>
                <div class="skeleton rounded-xl h-44"></div>
                <div class="skeleton rounded-xl h-44"></div>
            </div>
            <div id="paginasiKit" class="flex justify-end gap-1.5 mt-6"></div>
        </section>

        <section id="tab-riwayat" class="hidden anim-fade-in">
            <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="border-b border-slate-200 bg-slate-50/40">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em]">Perangkat</th>
                            <th class="px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em]">Lokasi</th>
                            <th class="px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em]">Qty</th>
                            <th class="px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em]">Periode</th>
                            <th class="px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em]">Status</th>
                            <th class="px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em] text-right">Denda</th>
                        </tr>
                    </thead>
                    <tbody id="riwayatList" class="divide-y divide-slate-100">
                        <tr><td colspan="6" class="p-10 text-center text-slate-400 text-sm">
                            <div class="inline-flex items-center gap-2">
                                <span class="w-4 h-4 border-2 border-slate-300 border-t-slate-700 rounded-full animate-spin"></span>
                                Memuat riwayat...
                            </div>
                        </td></tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="tab-kerusakan" class="hidden space-y-6 anim-fade-in">
            <div class="bg-white rounded-xl border border-slate-200 p-6 sm:p-7 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-amber-400 to-red-400 opacity-70"></div>
                <h3 class="text-base font-semibold text-slate-900 tracking-tight mb-1">Pelaporan Insiden</h3>
                <p class="text-xs text-slate-500 mb-6">Catat kerusakan atau malfungsi perangkat laboratorium.</p>
                <form id="formKerusakan" onsubmit="kirimLaporanKerusakan(event)" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em] mb-2">ID Perangkat</label>
                        <input type="number" id="damageKitId" required placeholder="Contoh: 1"
                            class="w-full bg-white border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm placeholder-slate-400 transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em] mb-2">Klasifikasi Insiden</label>
                        <div class="relative">
                            <select id="damageTipe" required
                                class="w-full appearance-none bg-white border border-slate-200 rounded-lg pl-3.5 pr-10 py-2.5 text-sm text-slate-700 transition-all cursor-pointer">
                                <option value="">Pilih klasifikasi...</option>
                                <option value="MINOR_COMPONENT">Komponen Minor Bermasalah</option>
                                <option value="BROKEN_BOARD">Kerusakan Fisik Utama</option>
                                <option value="MISSING_PARTS">Kehilangan Komponen</option>
                            </select>
                            <svg class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em] mb-2">Uraian Teknis</label>
                        <textarea id="damageDesc" required rows="3" placeholder="Uraikan kondisi malfungsi perangkat..."
                            class="w-full bg-white border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm placeholder-slate-400 transition-all resize-none"></textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="damageImage" class="block text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em] mb-2">Foto Insiden <span class="normal-case tracking-normal text-slate-300">(opsional, maks. 5 foto, 5MB per foto)</span></label>
                        <input type="file" id="damageImage" accept="image/png,image/jpeg,image/webp" multiple onchange="validasiGambarLaporan(event)"
                            class="w-full text-xs text-slate-600 file:mr-3 file:rounded-lg file:border file:border-slate-200 file:bg-white file:px-3 file:py-2 file:text-xs file:font-medium file:text-slate-700 hover:file:bg-slate-50">
                        <p id="damageImageSelection" class="text-[10px] text-slate-400 mt-1" aria-live="polite">Belum ada gambar dipilih.</p>
                    </div>
                    <div id="formKerusakanFeedback" class="sm:col-span-2 hidden text-xs rounded-lg p-3"></div>
                    <div class="sm:col-span-2 flex justify-end">
                        <button type="submit"
                            class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-all shadow-sm hover:shadow-md active:scale-[.98]">
                            Submit Laporan
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="border-b border-slate-200 bg-slate-50/40">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em]">Perangkat</th>
                            <th class="px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em]">Klasifikasi</th>
                            <th class="px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em]">Foto</th>
                            <th class="px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em]">Uraian</th>
                            <th class="px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em]">Diperbaiki oleh</th>
                            <th class="px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em]">Status</th>
                            <th id="thAksiKerusakan" class="px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em] hidden">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody id="kerusakanList" class="divide-y divide-slate-100">
                        <tr><td colspan="7" class="p-10 text-center text-slate-400 text-sm">
                            <div class="inline-flex items-center gap-2">
                                <span class="w-4 h-4 border-2 border-slate-300 border-t-slate-700 rounded-full animate-spin"></span>
                                Memuat data...
                            </div>
                        </td></tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="tab-kelola" class="hidden anim-fade-in">
            <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="border-b border-slate-200 bg-slate-50/40">
                        <tr>
                            <th class="px-5 py-4 text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em]">Peminjam</th>
                            <th class="px-5 py-4 text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em]">Perangkat</th>
                            <th class="px-5 py-4 text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em]">Qty</th>
                            <th class="px-5 py-4 text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em]">Durasi</th>
                            <th class="px-5 py-4 text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em]">Status</th>
                            <th class="px-5 py-4 text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em] text-right">Denda</th>
                            <th class="px-5 py-4 text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em]">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody id="kelolaList" class="divide-y divide-slate-100">
                        <tr><td colspan="7" class="p-10 text-center text-slate-400 text-sm">
                            <div class="inline-flex items-center gap-2">
                                <span class="w-4 h-4 border-2 border-slate-300 border-t-slate-700 rounded-full animate-spin"></span>
                                Memuat data...
                            </div>
                        </td></tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <div id="toastContainer" class="fixed top-20 right-4 sm:right-6 z-[60] space-y-2 pointer-events-none"></div>

    <div id="modalDamageImages" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-[70] hidden items-center justify-center p-4"
        onclick="if (event.target === this) tutupModal('modalDamageImages')">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] border border-slate-200 overflow-hidden anim-fade-up flex flex-col">
            <div class="flex justify-between items-center gap-4 px-6 py-4 border-b border-slate-200">
                <div>
                    <h3 class="font-semibold text-slate-900 tracking-tight text-base">Foto Laporan Insiden</h3>
                    <p id="damageImagesCount" class="text-xs text-slate-500 mt-0.5"></p>
                </div>
                <button onclick="tutupModal('modalDamageImages')" class="text-slate-400 hover:text-slate-700 transition-colors p-1.5 rounded-md hover:bg-slate-100" aria-label="Tutup galeri foto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div id="damageImagesGallery" class="p-5 sm:p-6 overflow-y-auto flex flex-col items-center gap-5"></div>
        </div>
    </div>

    <div id="modalAssistantAccounts" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl border border-slate-200 overflow-hidden anim-fade-up">
            <div class="flex justify-between items-start px-6 pt-6 pb-4">
                <div>
                    <h3 class="font-semibold text-slate-900 tracking-tight text-base">Kelola Akun Asisten Lab</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Buat, ubah, dan hapus akun asisten laboratorium.</p>
                </div>
                <button onclick="tutupModal('modalAssistantAccounts')" class="text-slate-400 hover:text-slate-700 transition-colors p-1.5 rounded-md hover:bg-slate-100" aria-label="Tutup">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="px-6 pb-6">
                <div class="flex justify-end mb-4">
                    <button onclick="bukaFormAsisten()" class="bg-slate-900 hover:bg-slate-800 text-white rounded-lg px-3.5 py-2 text-xs font-medium transition-colors">
                        Tambah Asisten
                    </button>
                </div>
                <div class="overflow-x-auto border border-slate-200 rounded-xl">
                    <table class="w-full text-sm text-left">
                        <thead class="border-b border-slate-200 bg-slate-50/60">
                            <tr>
                                <th class="px-4 py-3 text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em]">Nama</th>
                                <th class="px-4 py-3 text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em]">Email</th>
                                <th class="px-4 py-3 text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em] text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody id="assistantAccountsList" class="divide-y divide-slate-100">
                            <tr><td colspan="3" class="p-8 text-center text-slate-400 text-sm">Memuat akun asisten...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="modalAssistantAccountForm" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[55] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md border border-slate-200 overflow-hidden anim-fade-up">
            <div class="flex justify-between items-start px-6 pt-6 pb-4">
                <div>
                    <h3 id="assistantFormTitle" class="font-semibold text-slate-900 tracking-tight text-base">Tambah Asisten Lab</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Role akun ditetapkan sebagai asisten lab.</p>
                </div>
                <button onclick="tutupModal('modalAssistantAccountForm')" class="text-slate-400 hover:text-slate-700 transition-colors p-1.5 rounded-md hover:bg-slate-100" aria-label="Tutup">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="assistantAccountForm" onsubmit="simpanAsisten(event)" class="px-6 pb-6 pt-2 space-y-4">
                <div>
                    <label for="assistantName" class="block text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em] mb-2">Nama lengkap</label>
                    <input type="text" id="assistantName" required maxlength="255" autocomplete="name"
                        class="w-full bg-white border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm transition-all">
                </div>
                <div>
                    <label for="assistantEmail" class="block text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em] mb-2">Email</label>
                    <input type="email" id="assistantEmail" required maxlength="255" autocomplete="email"
                        class="w-full bg-white border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm transition-all">
                    <p class="text-[10px] text-slate-400 mt-1">Gunakan alamat email Gmail (@gmail.com).</p>
                </div>
                <div>
                    <label for="assistantPassword" class="block text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em] mb-2">Kata sandi</label>
                    <input type="password" id="assistantPassword" minlength="8" autocomplete="new-password"
                        class="w-full bg-white border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm transition-all">
                    <p id="assistantPasswordHint" class="text-[10px] text-slate-400 mt-1">Minimal 8 karakter.</p>
                </div>
                <div>
                    <label for="assistantPasswordConfirmation" class="block text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em] mb-2">Konfirmasi kata sandi</label>
                    <input type="password" id="assistantPasswordConfirmation" minlength="8" autocomplete="new-password"
                        class="w-full bg-white border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm transition-all">
                </div>
                <div id="assistantAccountFeedback" class="hidden text-xs rounded-lg p-3" role="alert"></div>
                <div class="flex gap-2 pt-1">
                    <button type="button" onclick="tutupModal('modalAssistantAccountForm')" class="flex-1 border border-slate-200 text-slate-700 rounded-lg py-2.5 text-sm font-medium hover:bg-slate-50 transition-all">Batal</button>
                    <button type="submit" id="assistantAccountSubmit" class="flex-1 bg-slate-900 hover:bg-slate-800 text-white rounded-lg py-2.5 text-sm font-medium transition-all">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalPinjam" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md border border-slate-200 overflow-hidden anim-fade-up">
            <div class="relative flex justify-between items-start px-6 pt-6 pb-4">
                <div>
                    <h3 class="font-semibold text-slate-900 tracking-tight text-base">Formulir Permintaan</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Ajukan peminjaman perangkat laboratorium.</p>
                </div>
                <button onclick="tutupModal('modalPinjam')" class="text-slate-400 hover:text-slate-700 transition-colors p-1.5 rounded-md hover:bg-slate-100" aria-label="Tutup">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="formPinjam" onsubmit="submitPinjam(event)" class="p-6 pt-2 space-y-4">
                <input type="hidden" id="pinjamKitId">
                <div class="bg-slate-50 p-4 rounded-lg border border-slate-100">
                    <p class="text-[10px] text-slate-500 font-semibold uppercase tracking-[.1em] mb-1.5">Target Perangkat</p>
                    <p id="pinjamKitNama" class="font-medium text-slate-900 text-sm"></p>
                    <p id="pinjamKitLokasi" class="text-xs text-slate-500 mt-1 mono"></p>
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em] mb-2">Kuantitas</label>
                    <input type="number" id="pinjamQty" value="1" min="1" required
                        class="w-full bg-white border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm transition-all">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em] mb-2">Mulai</label>
                        <input type="date" id="pinjamTglMulai" required
                            class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-700 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em] mb-2">Tenggat</label>
                        <input type="date" id="pinjamTglAkhir" required
                            class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-700 transition-all">
                    </div>
                </div>
                <div id="pinjamFeedback" class="hidden text-xs rounded-lg p-3"></div>
                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="tutupModal('modalPinjam')"
                        class="flex-1 border border-slate-200 text-slate-700 rounded-lg py-2.5 text-sm font-medium hover:bg-slate-50 transition-all active:scale-[.98]">Batal</button>
                    <button type="submit"
                        class="flex-1 bg-slate-900 hover:bg-slate-800 text-white rounded-lg py-2.5 text-sm font-medium transition-all shadow-sm hover:shadow-md active:scale-[.98]">Ajukan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalKit" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg border border-slate-200 overflow-hidden anim-fade-up">
            <div class="flex justify-between items-start px-6 pt-6 pb-4">
                <div>
                    <h3 id="modalKitTitle" class="font-semibold text-slate-900 tracking-tight text-base">Entri Perangkat Baru</h3>
                    <p id="modalKitSubtitle" class="text-xs text-slate-500 mt-0.5">Daftarkan perangkat ke katalog inventaris.</p>
                </div>
                <button onclick="tutupModal('modalKit')" class="text-slate-400 hover:text-slate-700 transition-colors p-1.5 rounded-md hover:bg-slate-100" aria-label="Tutup">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="formKit" onsubmit="submitTambahKit(event)" class="p-6 pt-2 space-y-4">

                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em] mb-2">Foto Perangkat <span class="text-slate-300 normal-case tracking-normal">(opsional)</span></label>
                    <div class="flex items-center gap-4">
                        <div id="kitImagePreview" class="w-24 h-24 rounded-xl bg-slate-50 border border-slate-200 border-dashed flex items-center justify-center overflow-hidden flex-shrink-0">
                            <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <input type="file" id="kitImage" accept="image/png,image/jpeg,image/jpg,image/webp" class="hidden" onchange="previewKitImage(event)">
                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="document.getElementById('kitImage').click()"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Pilih Foto
                                </button>
                                <button type="button" id="kitImageClear" onclick="clearKitImage()"
                                    class="hidden inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-2">Format: JPG, PNG, WEBP. Maks 2MB.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em] mb-2">Kode</label>
                        <input type="text" id="kitCode" required placeholder="SYS-001"
                            class="w-full bg-white border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm mono placeholder-slate-400 transition-all"></div>
                    <div><label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em] mb-2">Nama</label>
                        <input type="text" id="kitName" required
                            class="w-full bg-white border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm placeholder-slate-400 transition-all"></div>
                    <div><label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em] mb-2">Kategori</label>
                        <input type="text" id="kitCategory" required
                            class="w-full bg-white border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm placeholder-slate-400 transition-all"></div>
                    <div><label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em] mb-2">Lokasi</label>
                        <input type="text" id="kitLocation" placeholder="Ruang A - Rak 2"
                            class="w-full bg-white border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm placeholder-slate-400 transition-all"></div>
                    <div class="col-span-2"><label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-[.1em] mb-2">Kuantitas Awal</label>
                        <input type="number" id="kitStock" required min="0"
                            class="w-full bg-white border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm transition-all"></div>
                </div>
                <div id="kitFeedback" class="hidden text-xs rounded-lg p-3"></div>
                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="tutupModal('modalKit')"
                        class="flex-1 border border-slate-200 text-slate-700 rounded-lg py-2.5 text-sm font-medium hover:bg-slate-50 transition-all active:scale-[.98]">Batal</button>
                    <button type="submit" id="modalKitSubmitBtn"
                        class="flex-1 bg-slate-900 hover:bg-slate-800 text-white rounded-lg py-2.5 text-sm font-medium transition-all shadow-sm hover:shadow-md active:scale-[.98]">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    const token = localStorage.getItem('auth_token');
    const userAuth = JSON.parse(localStorage.getItem('user') || 'null');

    if (!token || !userAuth) {
        window.location.href = '/';
    } else {
        document.getElementById('appBody').classList.remove('hidden');
        document.getElementById('userName').textContent = userAuth.name;
        document.getElementById('userRole').textContent = userAuth.role === 'owner'
            ? 'Owner'
            : (userAuth.role === 'asisten_lab' ? 'Asisten Lab' : 'Akses Mahasiswa');
        document.getElementById('userAvatar').textContent = userAuth.name.charAt(0).toUpperCase();
        document.getElementById('greetName').textContent = userAuth.name.split(' ')[0];

        if (['asisten_lab', 'owner'].includes(userAuth.role)) {
            document.getElementById('btn-kelola').classList.remove('hidden');
            document.getElementById('btnTambahKit').classList.remove('hidden');
            document.getElementById('thAksiKerusakan').classList.remove('hidden');
        }

        if (userAuth.role === 'owner') {
            document.getElementById('btnKelolaAsisten').classList.remove('hidden');
            document.getElementById('btnKelolaAsisten').classList.add('inline-flex');
        }

        document.getElementById('pinjamTglMulai').value = new Date().toISOString().split('T')[0];
        document.getElementById('pinjamTglAkhir').value = new Date(Date.now() + 7*86400000).toISOString().split('T')[0];
        tampilkanKatalogKit();
        refreshStats();
    }

    /* ===== THEME TOGGLE ===== */
    function updateThemeIcon() {
        const isDark = document.documentElement.classList.contains('dark');
        document.getElementById('iconSun').classList.toggle('hidden', !isDark);
        document.getElementById('iconMoon').classList.toggle('hidden', isDark);
    }
    function toggleTheme() {
        const html = document.documentElement;
        html.classList.toggle('dark');
        const isDark = html.classList.contains('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        updateThemeIcon();
    }
    updateThemeIcon();

    let editModeId = null;
    let hasNewImage = false;
    let removeImage = false;
    let assistantEditId = null;
    let assistantAccounts = [];

    /* ===== Toast ===== */
    function showToast(msg, type = 'success') {
        const el = document.createElement('div');
        const bg = type === 'success' ? 'bg-white border-emerald-200' : 'bg-white border-red-200';
        const dot = type === 'success' ? 'bg-emerald-500' : 'bg-red-500';
        el.className = `${bg} border rounded-xl shadow-lg px-4 py-3 flex items-center gap-3 pointer-events-auto anim-fade-up`;
        el.innerHTML = `<span class="w-1.5 h-1.5 rounded-full ${dot}"></span><span class="text-[13px] text-slate-700 font-medium">${msg}</span>`;
        document.getElementById('toastContainer').appendChild(el);
        setTimeout(() => { el.style.transition = 'opacity .3s, transform .3s'; el.style.opacity = '0'; el.style.transform = 'translate3d(0,-4px,0)'; }, 2400);
        setTimeout(() => el.remove(), 2800);
    }

    /* ===== Stats ===== */
    async function refreshStats() {
        try {
            const [rk, rb] = await Promise.all([
                apiFetch('/api/iot-kits?page=1'),
                apiFetch('/api/borrowings')
            ]);
            const jk = await rk.json();
            const jb = await rb.json();
            const kits = jk.data || [];
            const borrowings = jb.data || [];

            const total = jk.meta?.total ?? kits.length;
            document.getElementById('statTotalKits').textContent = total;

            const active = borrowings.filter(b => ['ON_LOAN','OVERDUE','APPROVED'].includes(b.status)).length;
            document.getElementById('statActiveLoans').textContent = active;

            const overdue = borrowings.filter(b => b.status === 'OVERDUE').length;
            document.getElementById('statOverdue').textContent = overdue;

            const maint = kits.filter(k => k.status === 'MAINTENANCE').length;
            document.getElementById('statMaintenance').textContent = maint;
        } catch(e) {}
    }

    async function apiFetch(url, opts = {}) {
        const headers = { 'Authorization':'Bearer '+token, 'Accept':'application/json', ...(opts.headers||{}) };
        if (!(opts.body instanceof FormData)) {
            headers['Content-Type'] = headers['Content-Type'] || 'application/json';
        }
        return fetch(url, { ...opts, headers });
    }

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, character => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;',
        })[character]);
    }

    function showFeedback(id, type, msg) {
        const el = document.getElementById(id);
        el.className = type === 'success'
            ? 'text-xs rounded-lg p-3 bg-emerald-50 text-emerald-700 border border-emerald-100 anim-fade-in'
            : 'text-xs rounded-lg p-3 bg-red-50 text-red-700 border border-red-100 anim-fade-in';
        el.textContent = msg; el.classList.remove('hidden');
    }

    function badge(s) { return `<span class="status-dot badge-${s}">${s.replace(/_/g,' ')}</span>`; }
    function formatRupiah(n) { return 'Rp ' + Number(n).toLocaleString('id-ID'); }
    function tutupModal(id) { document.getElementById(id).classList.replace('flex','hidden'); }

    async function bukaKelolaAsisten() {
        document.getElementById('modalAssistantAccounts').classList.replace('hidden','flex');
        await muatAkunAsisten();
    }

    async function muatAkunAsisten() {
        const tbody = document.getElementById('assistantAccountsList');
        tbody.innerHTML = '<tr><td colspan="3" class="p-8 text-center text-slate-400 text-sm">Memuat akun asisten...</td></tr>';

        try {
            const response = await apiFetch('/api/assistant-accounts');
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Gagal memuat akun asisten lab.');
            }

            assistantAccounts = result.data || [];
            if (assistantAccounts.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="p-8 text-center text-slate-400 text-sm">Belum ada akun asisten lab.</td></tr>';
                return;
            }

            tbody.innerHTML = assistantAccounts.map(assistant => `
                <tr>
                    <td class="px-4 py-3 font-medium text-slate-800">${escapeHtml(assistant.name)}</td>
                    <td class="px-4 py-3 text-slate-600">${escapeHtml(assistant.email)}</td>
                    <td class="px-4 py-3 text-right whitespace-nowrap">
                        <button onclick="bukaFormAsisten(${Number(assistant.id)})" class="text-xs font-medium text-slate-700 hover:text-slate-950 px-2 py-1">Edit</button>
                        <button onclick="hapusAkunAsisten(${Number(assistant.id)})" class="text-xs font-medium text-red-600 hover:text-red-800 px-2 py-1">Hapus</button>
                    </td>
                </tr>
            `).join('');
        } catch (error) {
            tbody.innerHTML = '<tr><td colspan="3" class="p-8 text-center text-red-600 text-sm">Gagal memuat akun. Silakan coba lagi.</td></tr>';
            showToast(error.message || 'Gagal memuat akun asisten lab.', 'error');
        }
    }

    function bukaFormAsisten(id = null) {
        assistantEditId = id;
        const form = document.getElementById('assistantAccountForm');
        const password = document.getElementById('assistantPassword');
        const confirmation = document.getElementById('assistantPasswordConfirmation');
        const assistant = assistantAccounts.find(account => Number(account.id) === Number(id));

        form.reset();
        document.getElementById('assistantAccountFeedback').classList.add('hidden');
        document.getElementById('assistantFormTitle').textContent = id ? 'Edit Akun Asisten' : 'Tambah Asisten Lab';
        document.getElementById('assistantAccountSubmit').textContent = id ? 'Perbarui' : 'Simpan';
        password.required = !id;
        confirmation.required = !id;
        document.getElementById('assistantPasswordHint').textContent = id
            ? 'Kosongkan jika kata sandi tidak ingin diubah.'
            : 'Minimal 8 karakter.';

        if (assistant) {
            document.getElementById('assistantName').value = assistant.name;
            document.getElementById('assistantEmail').value = assistant.email;
        }

        document.getElementById('modalAssistantAccountForm').classList.replace('hidden','flex');
    }

    async function simpanAsisten(event) {
        event.preventDefault();

        const button = document.getElementById('assistantAccountSubmit');
        const payload = {
            name: document.getElementById('assistantName').value,
            email: document.getElementById('assistantEmail').value,
        };
        const password = document.getElementById('assistantPassword').value;
        const confirmation = document.getElementById('assistantPasswordConfirmation').value;

        if (password || assistantEditId === null) {
            payload.password = password;
            payload.password_confirmation = confirmation;
        }

        button.disabled = true;
        button.textContent = 'Menyimpan...';

        try {
            const isEdit = assistantEditId !== null;
            const response = await apiFetch(
                isEdit ? `/api/assistant-accounts/${assistantEditId}` : '/api/assistant-accounts',
                {
                    method: isEdit ? 'PUT' : 'POST',
                    body: JSON.stringify(payload),
                }
            );
            const result = await response.json();

            if (!response.ok) {
                const validationErrors = result.errors ? Object.values(result.errors).flat().join(' ') : '';
                throw new Error(validationErrors || result.message || 'Akun asisten gagal disimpan.');
            }

            tutupModal('modalAssistantAccountForm');
            showToast(result.message || 'Akun asisten berhasil disimpan.');
            await muatAkunAsisten();
        } catch (error) {
            showFeedback('assistantAccountFeedback', 'error', error.message || 'Tidak dapat menyimpan akun asisten.');
        } finally {
            button.disabled = false;
            button.textContent = assistantEditId !== null ? 'Perbarui' : 'Simpan';
        }
    }

    async function hapusAkunAsisten(id) {
        const assistant = assistantAccounts.find(account => Number(account.id) === Number(id));
        if (!assistant || !confirm(`Hapus akun asisten ${assistant.name}?`)) {
            return;
        }

        try {
            const response = await apiFetch(`/api/assistant-accounts/${id}`, { method: 'DELETE' });
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Akun asisten gagal dihapus.');
            }

            showToast(result.message || 'Akun asisten berhasil dihapus.');
            await muatAkunAsisten();
        } catch (error) {
            showToast(error.message || 'Tidak dapat menghapus akun asisten.', 'error');
        }
    }

    const tabIds = ['tab-katalog','tab-riwayat','tab-kerusakan','tab-kelola'];
    let damageReports = [];

    function bukaTab(tabId) {
        tabIds.forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            el.classList.add('hidden');
            el.classList.remove('anim-fade-in');
        });
        const target = document.getElementById(tabId);
        target?.classList.remove('hidden');
        void target?.offsetWidth;
        target?.classList.add('anim-fade-in');

        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        const btn = document.getElementById('btn-' + tabId.replace('tab-',''));
        if (btn) btn.classList.add('active');

        if (tabId === 'tab-riwayat') tampilkanRiwayat();
        if (tabId === 'tab-kerusakan') tampilkanKerusakan();
        if (tabId === 'tab-kelola') tampilkanKelola();
        refreshStats();
    }

    let searchDebounce = null;
    function cariKit() { clearTimeout(searchDebounce); searchDebounce = setTimeout(() => tampilkanKatalogKit(1), 300); }

    async function tampilkanKatalogKit(halaman = 1) {
        const s = document.getElementById('searchInput').value;
        const k = document.getElementById('filterKategori').value;
        let url = `/api/iot-kits?page=${halaman}`;
        if (s) url += `&search=${encodeURIComponent(s)}`;
        if (k) url += `&category=${encodeURIComponent(k)}`;

        const wadah = document.getElementById('kitsGrid');
        wadah.innerHTML = `
            <div class="skeleton rounded-xl h-44"></div>
            <div class="skeleton rounded-xl h-44"></div>
            <div class="skeleton rounded-xl h-44"></div>`;
        try {
            const res = await apiFetch(url); const json = await res.json(); const kits = json.data || [];
            wadah.innerHTML = '';
            if (!kits.length) {
                wadah.innerHTML = `
                <div class="col-span-full text-center py-16 border border-dashed border-slate-200 rounded-xl bg-white">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-slate-50 text-slate-300 mb-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-slate-700">Tidak ada perangkat ditemukan</p>
                    <p class="text-xs text-slate-400 mt-1">Coba ubah kata kunci atau filter kategori.</p>
                </div>`;
                return;
            }

            kits.forEach((kit, idx) => {
                let aksi = '';
                if (userAuth.role === 'mahasiswa') {
                    aksi = kit.stock > 0
                        ? `<button onclick="bukaModalPinjam(${kit.id},'${kit.name}','${kit.storage_location||'-'}')" class="btn-ajukan">Ajukan Peminjaman</button>`
                        : `<div class="w-full text-center py-2.5 text-xs text-red-500 font-medium bg-red-50 border border-red-100 rounded-lg">Stok Habis</div>`;
                } else {
                    aksi = `<div class="flex gap-2">
                        <button onclick="bukaModalEdit(${kit.id})" class="btn-edit flex-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </button>
                        <button onclick="hapusKit(${kit.id})" class="btn-hapus flex-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </div>`;
                }

                const imageBlock = kit.image_url
                    ? `<div class="aspect-[16/10] bg-slate-50 overflow-hidden border-b border-slate-100">
                           <img src="${kit.image_url}" alt="${kit.name}" loading="lazy"
                                class="w-full h-full object-cover"
                                onerror="this.parentElement.style.display='none'">
                       </div>`
                    : '';

                wadah.innerHTML += `
                <div class="card-hover bg-white rounded-xl border border-slate-200 overflow-hidden flex flex-col anim-fade-up" style="animation-delay:${Math.min(idx * 0.03, 0.3)}s">
                    ${imageBlock}
                    <div class="p-6 flex-1">
                        <div class="flex justify-between items-start mb-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-600 uppercase tracking-[.08em]">${kit.category}</span>
                            ${badge(kit.status)}
                        </div>
                        <h3 class="text-[15px] font-semibold text-slate-900 leading-snug line-clamp-2">${kit.name}</h3>
                        <p class="text-[11px] text-slate-400 mono mt-1.5">${kit.code}</p>
                    </div>
                    <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-slate-400 uppercase tracking-[.1em] font-semibold block mb-0.5">Stok</span>
                            <span class="text-lg font-semibold text-slate-900 mono leading-none tabular-nums">${kit.stock}</span>
                        </div>
                        <div class="text-right max-w-[60%]">
                            <span class="text-[10px] text-slate-400 uppercase tracking-[.1em] font-semibold block mb-0.5">Lokasi</span>
                            <span class="text-xs text-slate-700 font-medium">${kit.storage_location || '—'}</span>
                        </div>
                    </div>
                    <div class="px-6 py-3.5 border-t border-slate-100 bg-slate-50/50">
                        ${aksi}
                    </div>
                </div>`;
            });

            const pag = document.getElementById('paginasiKit');
            if (!json.last_page || json.last_page <= 1) { pag.innerHTML = ''; return; }
            pag.innerHTML = Array.from({length:json.last_page},(_,i)=>i+1).map(i =>
                `<button onclick="tampilkanKatalogKit(${i})" class="w-8 h-8 flex items-center justify-center rounded-md text-xs font-medium mono transition-all ${i===halaman?'bg-slate-900 text-white shadow-sm':'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 hover:border-slate-300'}">${i}</button>`
            ).join('');

            if (!s && !k) {
                const total = json.meta?.total ?? kits.length;
                document.getElementById('statTotalKits').textContent = total;
                const maint = kits.filter(x => x.status === 'MAINTENANCE').length;
                document.getElementById('statMaintenance').textContent = maint;
            }
        } catch(e) {
            wadah.innerHTML = '<div class="col-span-full text-center py-12 text-red-500 bg-red-50 border border-red-100 rounded-xl text-sm font-medium">Gagal memuat data perangkat.</div>';
        }
    }

    function bukaModalPinjam(id, nama, lokasi) {
        document.getElementById('pinjamKitId').value = id;
        document.getElementById('pinjamKitNama').textContent = nama;
        document.getElementById('pinjamKitLokasi').textContent = 'Penyimpanan: ' + lokasi;
        document.getElementById('pinjamFeedback').classList.add('hidden');
        document.getElementById('modalPinjam').classList.replace('hidden','flex');
    }

    async function submitPinjam(e) {
        e.preventDefault();
        const body = { iot_kit_id: +document.getElementById('pinjamKitId').value, quantity: +document.getElementById('pinjamQty').value, borrow_date: document.getElementById('pinjamTglMulai').value, expected_return_date: document.getElementById('pinjamTglAkhir').value };
        const btn = e.target.querySelector('button[type="submit"]');
        const originalText = btn.textContent;
        btn.textContent = 'Memproses...'; btn.disabled = true; btn.classList.add('opacity-70');
        try {
            const res = await apiFetch('/api/borrowings', { method:'POST', body:JSON.stringify(body) });
            const json = await res.json();
            if (res.ok) {
                showFeedback('pinjamFeedback','success','Permintaan dicatat. Menunggu otorisasi admin.');
                showToast('Permintaan peminjaman berhasil diajukan.');
                setTimeout(()=>{ tutupModal('modalPinjam'); tampilkanKatalogKit(); refreshStats(); }, 1200);
            } else {
                showFeedback('pinjamFeedback','error',(json.errors?Object.values(json.errors).flat().join(' | '):json.message));
            }
        } catch(err) { showFeedback('pinjamFeedback','error','Gangguan koneksi.'); }
        btn.textContent = originalText; btn.disabled = false; btn.classList.remove('opacity-70');
    }

    async function tampilkanRiwayat() {
        const tbody = document.getElementById('riwayatList');
        tbody.innerHTML = '<tr><td colspan="6" class="p-10 text-center text-slate-400 text-sm"><div class="inline-flex items-center gap-2"><span class="w-4 h-4 border-2 border-slate-300 border-t-slate-700 rounded-full animate-spin"></span>Memuat riwayat...</div></td></tr>';
        try {
            const res = await apiFetch('/api/borrowings'); const json = await res.json(); const data = json.data || [];
            if (!data.length) {
                tbody.innerHTML = `
                <tr><td colspan="6" class="p-16 text-center">
                    <div class="inline-flex flex-col items-center">
                        <div class="w-14 h-14 rounded-full bg-slate-50 text-slate-300 flex items-center justify-center mb-3">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <p class="text-sm font-medium text-slate-700">Belum ada riwayat transaksi</p>
                        <p class="text-xs text-slate-400 mt-1">Riwayat peminjaman akan muncul di sini.</p>
                    </div>
                </td></tr>`;
                return;
            }
            tbody.innerHTML = data.map(b => `
                <tr class="hover:bg-slate-50/60 transition-colors">
                    <td class="px-6 py-4"><p class="font-medium text-slate-900 text-[13px]">${b.iot_kit?.name??'—'}</p><p class="text-[11px] text-slate-400 mono mt-0.5">${b.iot_kit?.code??'—'}</p></td>
                    <td class="px-6 py-4 text-xs text-slate-600">${b.iot_kit?.storage_location??'—'}</td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-900 mono tabular-nums">${b.quantity}</td>
                    <td class="px-6 py-4 text-xs text-slate-600 leading-relaxed">
                        <div class="flex items-center gap-1.5"><span class="text-[10px] text-slate-400 uppercase tracking-wider w-12">Pinjam</span><span class="mono tabular-nums">${b.borrow_date??'—'}</span></div>
                        <div class="flex items-center gap-1.5 mt-0.5"><span class="text-[10px] text-slate-400 uppercase tracking-wider w-12">Batas</span><span class="mono tabular-nums">${b.expected_return_date??'—'}</span></div>
                        ${b.actual_return_date?'<div class="flex items-center gap-1.5 mt-0.5"><span class="text-[10px] text-slate-400 uppercase tracking-wider w-12">Aktual</span><span class="mono tabular-nums">'+b.actual_return_date+'</span></div>':''}
                    </td>
                    <td class="px-6 py-4">${badge(b.status)}</td>
                    <td class="px-6 py-4 text-right text-xs mono font-medium ${Number(b.fine_amount)>0?'text-red-600':'text-slate-300'}">${Number(b.fine_amount)>0?formatRupiah(b.fine_amount):'—'}</td>
                </tr>
            `).join('');

            const active = data.filter(b => ['ON_LOAN','OVERDUE','APPROVED'].includes(b.status)).length;
            const overdue = data.filter(b => b.status === 'OVERDUE').length;
            document.getElementById('statActiveLoans').textContent = active;
            document.getElementById('statOverdue').textContent = overdue;
        } catch(e) {
            tbody.innerHTML = '<tr><td colspan="6" class="p-10 text-center text-red-500 text-sm">Gagal memuat data.</td></tr>';
        }
    }

    async function tampilkanKelola() {
        const tbody = document.getElementById('kelolaList');
        tbody.innerHTML = '<tr><td colspan="7" class="p-10 text-center text-slate-400 text-sm"><div class="inline-flex items-center gap-2"><span class="w-4 h-4 border-2 border-slate-300 border-t-slate-700 rounded-full animate-spin"></span>Memuat data otorisasi...</div></td></tr>';
        try {
            const res = await apiFetch('/api/borrowings'); const json = await res.json(); const data = json.data || [];
            if (!data.length) {
                tbody.innerHTML = '<tr><td colspan="7" class="p-16 text-center text-slate-500 text-sm">Antrean kosong.</td></tr>';
                return;
            }
            tbody.innerHTML = data.map(b => {
                let btn = `<span class="text-slate-300 text-xs">—</span>`;
                if (b.status === 'PENDING') btn = `<div class="flex gap-1.5"><button onclick="aksiAdmin(${b.id},'approve')" class="px-2.5 py-1 text-[11px] font-medium rounded-md border border-emerald-200 text-emerald-700 hover:bg-emerald-50 transition-colors active:scale-95">Setujui</button><button onclick="aksiAdmin(${b.id},'reject')" class="px-2.5 py-1 text-[11px] font-medium rounded-md border border-red-200 text-red-700 hover:bg-red-50 transition-colors active:scale-95">Tolak</button></div>`;
                else if (b.status === 'APPROVED') btn = `<button onclick="aksiAdmin(${b.id},'pickup')" class="px-2.5 py-1 text-[11px] font-medium rounded-md border border-blue-200 text-blue-700 hover:bg-blue-50 transition-colors active:scale-95">Serahkan</button>`;
                else if (b.status === 'ON_LOAN' || b.status === 'OVERDUE') btn = `<button onclick="aksiAdmin(${b.id},'return')" class="px-2.5 py-1 text-[11px] font-medium rounded-md border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 transition-colors active:scale-95">Kembalikan</button>`;
                return `
                <tr class="hover:bg-slate-50/60 transition-colors">
                    <td class="px-5 py-4"><p class="font-medium text-[13px] text-slate-900">${b.user?.name??'—'}</p><p class="text-[11px] text-slate-500 mt-0.5">${b.user?.email??'—'}</p></td>
                    <td class="px-5 py-4"><p class="text-[13px] text-slate-700 font-medium truncate max-w-[160px]" title="${b.iot_kit?.name}">${b.iot_kit?.name??'—'}</p><p class="text-[10px] text-slate-500 mt-0.5">${b.iot_kit?.storage_location??'—'}</p></td>
                    <td class="px-5 py-4 text-sm font-medium text-slate-900 mono tabular-nums">${b.quantity}</td>
                    <td class="px-5 py-4 text-xs text-slate-600"><span class="block mono tabular-nums">${b.borrow_date??'—'}</span><span class="block text-slate-400 mono tabular-nums">s/d ${b.expected_return_date??'—'}</span></td>
                    <td class="px-5 py-4">${badge(b.status)}</td>
                    <td class="px-5 py-4 text-right mono text-[11px] ${Number(b.fine_amount)>0?'text-red-600 font-semibold':'text-slate-300'}">${Number(b.fine_amount)>0?formatRupiah(b.fine_amount):'—'}</td>
                    <td class="px-5 py-4">${btn}</td>
                </tr>`;
            }).join('');
        } catch(e) {}
    }

    async function aksiAdmin(id, action) {
        try {
            const res = await apiFetch(`/api/borrowings/${id}/${action}`, { method:'PUT', body:'{}' });
            const json = await res.json();
            if (res.ok) {
                const labels = { approve:'disetujui', reject:'ditolak', pickup:'diserahkan', return:'dikembalikan' };
                showToast(`Peminjaman berhasil ${labels[action] || 'diproses'}.`);
                tampilkanKelola(); tampilkanKatalogKit(); refreshStats();
            } else {
                alert('Gagal: ' + (json.message || 'Error internal'));
            }
        } catch(e) {}
    }

    async function tampilkanKerusakan() {
        const tbody = document.getElementById('kerusakanList');
        tbody.innerHTML = '<tr><td colspan="7" class="p-10 text-center text-slate-400 text-sm"><div class="inline-flex items-center gap-2"><span class="w-4 h-4 border-2 border-slate-300 border-t-slate-700 rounded-full animate-spin"></span>Memuat data insiden...</div></td></tr>';
        try {
            const res = await apiFetch('/api/damage-reports'); const json = await res.json(); const data = json.data || [];
            damageReports = data;
            if (!data.length) {
                tbody.innerHTML = '<tr><td colspan="7" class="p-16 text-center text-slate-500 text-sm">Belum ada laporan insiden.</td></tr>';
                return;
            }
            tbody.innerHTML = data.map(r => {
                let aksi = `<td class="px-6 py-4 hidden" id="aksiHdn"></td>`;
                if (['asisten_lab', 'owner'].includes(userAuth.role)) {
                    aksi = `<td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1.5">
                        ${r.repair_status === 'REPORTED' ? `<button onclick="resolveKerusakan(${r.id},'IN_REPAIR')" class="px-2.5 py-1 text-[10px] font-medium border border-blue-200 text-blue-700 hover:bg-blue-50 rounded-md transition-colors active:scale-95">Inspeksi</button>` : ''}
                        ${r.repair_status === 'IN_REPAIR' ? `<button onclick="resolveKerusakan(${r.id},'RESOLVED')" class="px-2.5 py-1 text-[10px] font-medium border border-emerald-200 text-emerald-700 hover:bg-emerald-50 rounded-md transition-colors active:scale-95">Selesai</button>` : ''}
                        ${!['RESOLVED','DISCARDED'].includes(r.repair_status) ? `<button onclick="resolveKerusakan(${r.id},'DISCARDED')" class="px-2.5 py-1 text-[10px] font-medium border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-md transition-colors active:scale-95">Buang</button>` : ''}
                        </div>
                    </td>`;
                }
                const firstImageUrl = r.images?.[0]?.image_url || r.image_url;
                const imageCount = r.images?.length || (firstImageUrl ? 1 : 0);
                const extraImages = imageCount - 1;
                return `<tr class="hover:bg-slate-50/60 transition-colors">
                    <td class="px-6 py-4"><p class="text-[13px] font-medium text-slate-900">${r.iot_kit?.name??'—'}</p><p class="text-[11px] text-slate-400 mono mt-0.5">${r.iot_kit?.code??'—'}</p></td>
                    <td class="px-6 py-4 text-xs text-slate-600 font-medium">${r.damage_type.replace(/_/g,' ')}</td>
                    <td class="px-6 py-4">${firstImageUrl ? `<button type="button" onclick="bukaGaleriKerusakan(${Number(r.id)})" title="Lihat ${imageCount} foto" aria-label="Lihat ${imageCount} foto laporan" class="relative block w-16 h-12 overflow-hidden rounded-md"><img src="${escapeHtml(firstImageUrl)}" alt="Foto pertama insiden" class="w-full h-full object-cover"><span class="absolute inset-0 bg-black/40 pointer-events-none"></span>${extraImages > 0 ? `<span class="absolute inset-0 flex items-center justify-center text-white text-xs font-semibold pointer-events-none">+${extraImages}</span>` : ''}</button>` : '<span class="text-slate-300">—</span>'}</td>
                    <td class="px-6 py-4 text-xs text-slate-500 max-w-[220px] truncate" title="${escapeHtml(r.description)}">${escapeHtml(r.description)}</td>
                    <td class="px-6 py-4"><span class="text-xs text-slate-600">${escapeHtml(r.repairer_name || '—')}</span></td>
                    <td class="px-6 py-4">${badge(r.repair_status)}</td>
                    ${aksi}
                </tr>`;
            }).join('');
        } catch(e) {}
    }

    function bukaGaleriKerusakan(reportId) {
        const report = damageReports.find(item => Number(item.id) === Number(reportId));
        const images = report?.images?.length
            ? report.images
            : (report?.image_url ? [{ image_url: report.image_url }] : []);

        if (!images.length) {
            return;
        }

        document.getElementById('damageImagesCount').textContent = `${images.length} gambar`;
        document.getElementById('damageImagesGallery').innerHTML = images.map((image, index) => `
            <img src="${escapeHtml(image.image_url)}" alt="Foto ${index + 1} laporan insiden"
                class="block w-auto h-auto max-w-full max-h-[70vh] object-contain rounded-lg">
        `).join('');
        document.getElementById('modalDamageImages').classList.replace('hidden', 'flex');
    }

    async function kirimLaporanKerusakan(e) {
        e.preventDefault();
        const body = new FormData();
        body.append('iot_kit_id', document.getElementById('damageKitId').value);
        body.append('damage_type', document.getElementById('damageTipe').value);
        body.append('description', document.getElementById('damageDesc').value);
        const images = document.getElementById('damageImage').files;
        for (const image of images) {
            body.append('images[]', image);
        }
        const btn = e.target.querySelector('button'); const originalText = btn.textContent;
        btn.disabled = true; btn.textContent = "Mengajukan..."; btn.classList.add('opacity-70');
        try {
            const res = await apiFetch('/api/damage-reports',{method:'POST',body});
            const json = await res.json();
            if (res.ok) {
                showFeedback('formKerusakanFeedback','success','Laporan insiden berhasil dicatat.');
                showToast('Laporan insiden berhasil dikirim.');
                document.getElementById('formKerusakan').reset();
                document.getElementById('damageImageSelection').textContent = 'Belum ada gambar dipilih.';
                tampilkanKerusakan();
                refreshStats();
            } else {
                showFeedback('formKerusakanFeedback','error',(json.errors?Object.values(json.errors).flat().join(', '):json.message));
            }
        } catch(err) { showFeedback('formKerusakanFeedback','error','Gangguan koneksi.'); }
        btn.disabled = false; btn.textContent = originalText; btn.classList.remove('opacity-70');
    }

    function validasiGambarLaporan(event) {
        const input = event.target;
        const images = Array.from(input.files || []);
        const oversizedImage = images.find(image => image.size > 5 * 1024 * 1024);

        if (images.length > 5) {
            input.value = '';
            document.getElementById('damageImageSelection').textContent = 'Belum ada gambar dipilih.';
            showFeedback('formKerusakanFeedback', 'error', 'Maksimal 5 gambar untuk satu laporan.');
            return;
        }

        if (oversizedImage) {
            input.value = '';
            document.getElementById('damageImageSelection').textContent = 'Belum ada gambar dipilih.';
            showFeedback('formKerusakanFeedback', 'error', 'Ukuran setiap gambar maksimal 5 MB.');
            return;
        }

        document.getElementById('damageImageSelection').textContent = images.length
            ? `${images.length} gambar dipilih. Gambar pertama akan ditampilkan di daftar laporan.`
            : 'Belum ada gambar dipilih.';
        document.getElementById('formKerusakanFeedback').classList.add('hidden');
    }

    async function resolveKerusakan(id, status) {
        try {
            const res = await apiFetch(`/api/damage-reports/${id}/resolve`,{method:'PUT',body:JSON.stringify({repair_status:status})});
            const json = await res.json();
            if (res.ok) {
                showToast('Status penanganan diperbarui.');
                tampilkanKerusakan(); tampilkanKatalogKit(); refreshStats();
            } else {
                showToast(json.message || 'Gagal memperbarui status penanganan.', 'error');
            }
        } catch(e) {
            showToast('Gangguan koneksi saat memperbarui status penanganan.', 'error');
        }
    }

    /* ===== Image Preview Helpers ===== */
    function previewKitImage(e) {
        const file = e.target.files[0];
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
            showFeedback('kitFeedback','error','Ukuran file maksimal 2MB.');
            e.target.value = '';
            return;
        }
        const url = URL.createObjectURL(file);
        document.getElementById('kitImagePreview').innerHTML = `<img src="${url}" class="w-full h-full object-cover">`;
        document.getElementById('kitImageClear').classList.remove('hidden');
        hasNewImage = true;
        removeImage = false;
    }

    function clearKitImage() {
        document.getElementById('kitImage').value = '';
        document.getElementById('kitImagePreview').innerHTML = `
            <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>`;
        document.getElementById('kitImageClear').classList.add('hidden');
        hasNewImage = false;
        if (editModeId !== null) {
            removeImage = true;
        }
    }

    /* ===== Modal Kit: Create ===== */
    function bukaModalKit() {
        editModeId = null;
        hasNewImage = false;
        removeImage = false;
        document.getElementById('modalKitTitle').textContent = 'Entri Perangkat Baru';
        document.getElementById('modalKitSubtitle').textContent = 'Daftarkan perangkat ke katalog inventaris.';
        document.getElementById('modalKitSubmitBtn').textContent = 'Simpan';
        document.getElementById('kitFeedback').classList.add('hidden');
        document.getElementById('formKit').reset();
        document.getElementById('kitImagePreview').innerHTML = `
            <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>`;
        document.getElementById('kitImageClear').classList.add('hidden');
        document.getElementById('modalKit').classList.replace('hidden','flex');
    }

    /* ===== Modal Kit: Edit ===== */
    async function bukaModalEdit(id) {
        editModeId = id;
        hasNewImage = false;
        removeImage = false;
        document.getElementById('modalKitTitle').textContent = 'Edit Perangkat';
        document.getElementById('modalKitSubtitle').textContent = 'Perbarui informasi perangkat di katalog.';
        document.getElementById('modalKitSubmitBtn').textContent = 'Perbarui';
        document.getElementById('kitFeedback').classList.add('hidden');
        document.getElementById('formKit').reset();
        document.getElementById('kitImage').value = '';
        document.getElementById('modalKit').classList.replace('hidden','flex');

        try {
            const res = await apiFetch(`/api/iot-kits/${id}`);
            const json = await res.json();
            const kit = json.data || json;
            document.getElementById('kitCode').value = kit.code || '';
            document.getElementById('kitName').value = kit.name || '';
            document.getElementById('kitCategory').value = kit.category || '';
            document.getElementById('kitLocation').value = kit.storage_location || '';
            document.getElementById('kitStock').value = kit.stock ?? 0;

            if (kit.image_url) {
                document.getElementById('kitImagePreview').innerHTML = `<img src="${kit.image_url}" class="w-full h-full object-cover">`;
                document.getElementById('kitImageClear').classList.remove('hidden');
            } else {
                document.getElementById('kitImagePreview').innerHTML = `
                    <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>`;
                document.getElementById('kitImageClear').classList.add('hidden');
            }
        } catch(e) {
            showFeedback('kitFeedback','error','Gagal memuat data perangkat.');
        }
    }

    /* ===== Submit: Create atau Update ===== */
    async function submitTambahKit(e) {
        e.preventDefault();

        const formData = new FormData();
        formData.append('code', document.getElementById('kitCode').value);
        formData.append('name', document.getElementById('kitName').value);
        formData.append('category', document.getElementById('kitCategory').value);
        formData.append('storage_location', document.getElementById('kitLocation').value);
        formData.append('stock', document.getElementById('kitStock').value);
        formData.append('status', 'AVAILABLE');

        const imageFile = document.getElementById('kitImage').files[0];
        if (imageFile) {
            formData.append('image', imageFile);
        }

        const isEdit = editModeId !== null;

        if (isEdit && removeImage) {
            formData.append('remove_image', '1');
        }

        const url = isEdit ? `/api/iot-kits/${editModeId}` : '/api/iot-kits';
        if (isEdit) formData.append('_method', 'PUT');

        const btn = document.getElementById('modalKitSubmitBtn');
        const originalText = btn.textContent;
        btn.disabled = true; btn.textContent = 'Menyimpan...'; btn.classList.add('opacity-70');

        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json',
                },
                body: formData
            });
            const json = await res.json();
            if (res.ok) {
                const msg = isEdit ? 'Perangkat berhasil diperbarui.' : 'Perangkat baru ditambahkan ke katalog.';
                showFeedback('kitFeedback','success', msg);
                showToast(msg);
                setTimeout(()=>{
                    tutupModal('modalKit');
                    editModeId = null;
                    hasNewImage = false;
                    removeImage = false;
                    tampilkanKatalogKit();
                    refreshStats();
                }, 1000);
            } else {
                showFeedback('kitFeedback','error',(json.errors?Object.values(json.errors).flat().join(', '):json.message));
            }
        } catch(err) {
            showFeedback('kitFeedback','error','Gangguan koneksi.');
        }
        btn.disabled = false; btn.textContent = originalText; btn.classList.remove('opacity-70');
    }

    async function hapusKit(id) {
        if (!confirm('Hapus perangkat ini secara permanen?')) return;
        try {
            const res = await apiFetch(`/api/iot-kits/${id}`,{method:'DELETE'});
            if (res.ok) { showToast('Perangkat dihapus.'); tampilkanKatalogKit(); refreshStats(); }
        } catch(e) {}
    }

    async function logout() {
        try { await apiFetch('/api/auth/logout',{method:'POST'}); } catch(e) {}
        localStorage.removeItem('auth_token'); localStorage.removeItem('user');
        window.location.href = '/';
    }
    </script>
</body>
</html>