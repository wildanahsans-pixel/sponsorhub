
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>SponsorHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 text-slate-800">
<div class="min-h-screen md:flex">

    <aside class="w-full md:w-64 bg-slate-950 text-white p-5">

        <div class="text-2xl font-bold tracking-tight">
            Sponsor<span class="text-indigo-400">Hub</span>
        </div>

        <p class="text-xs text-slate-400 mt-1">
            Sponsorship Management System
        </p>

        <div class="mt-7 flex items-center gap-3 rounded-xl bg-slate-800/70 p-3">
            <div class="w-11 h-11 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-lg shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div class="min-w-0">
                <p class="text-sm font-semibold text-white truncate">
                    {{ auth()->user()->name }}
                </p>
                <p class="text-xs text-slate-400">
                    {{ auth()->user()->role }}
                </p>
            </div>
        </div>

        <nav class="mt-6 space-y-1 text-sm">

            @php
                $links = [
                    ['dashboard', 'Dashboard'],
                    ['health.index', 'Health Score'],
                    ['sponsors.index', 'Data Sponsor'],
                    ['events.index', 'Kelola Kegiatan'],
                    ['evidence.index', 'Upload Bukti Benefit'],
                    ['customizations.requests', 'Pengajuan Sponsorship'],
                    ['customization.index', 'Kustomisasi Paket'],
                    ['verification.index', 'Verifikasi Benefit'],
                    ['agreements.index', 'Kesepakatan'],
                    ['payments.index', 'Verifikasi Dana'],
                    ['company.payments.index', 'Tagihan Saya'],
                ];
            @endphp

            @foreach($links as [$route, $label])

                @php
                    $role = auth()->user()->role;

                    $allowedRoutes = [
                        'Koordinator' => [
                            'dashboard',
                            'health.index',
                            'sponsors.index',
                        ],

                        'Panitia' => [
                            'dashboard',
                            'health.index',
                            'sponsors.index',
                            'events.index',
                            'evidence.index',
                            'customizations.requests',
                        ],

                        'Perusahaan' => [
                            'dashboard',
                            'customization.index',
                            'verification.index',
                            'company.payments.index',
                        ],

                        'Bendahara' => [
                            'dashboard',
                            'agreements.index',
                            'payments.index',
                        ],
                    ];
                @endphp

                @if(
                    Route::has($route) &&
                    in_array($route, $allowedRoutes[$role] ?? [])
                )
                    <a href="{{ route($route) }}"
                       class="block rounded-lg px-3 py-2 hover:bg-slate-800 transition
                       {{ request()->routeIs($route) ? 'bg-slate-800 text-indigo-300' : '' }}">

                        {{ $label }}

                    </a>
                @endif

            @endforeach

            @if(auth()->user()->role === 'Koordinator' && Route::has('reports.export'))
                <a href="{{ route('reports.export') }}"
                   class="block rounded-lg px-3 py-2 hover:bg-slate-800 transition">
                    Unduh Laporan
                </a>
            @endif

        </nav>

        <form method="POST"
              action="{{ route('logout') }}"
              class="mt-8 pt-5 border-t border-slate-800">

            @csrf

            <button type="submit"
                    class="w-full flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-slate-400 hover:bg-rose-500/10 hover:text-rose-400 transition">

                <span class="text-lg">↪</span>
                <span>Keluar dari Akun</span>

            </button>
        </form>

    </aside>

    <main class="flex-1 p-5 md:p-9 max-w-7xl">

        @if(session('success'))
            <div class="mb-5 rounded-xl bg-emerald-50 text-emerald-800 p-3 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-5 rounded-xl bg-rose-50 text-rose-700 p-3 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 rounded-xl bg-rose-50 text-rose-700 p-3 text-sm">
                @foreach($errors->all() as $e)
                    <div>{{ $e }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')

    </main>

</div>
</body>
</html>
