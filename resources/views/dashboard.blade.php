
@extends('layouts.app')

@section('content')

@if(auth()->user()->role === 'Perusahaan')

    {{-- DASHBOARD PERUSAHAAN --}}
    <div class="flex flex-wrap justify-between gap-3 items-end mb-8">
        <div>
            <p class="text-sm text-indigo-600 font-semibold">OVERVIEW</p>
            <h1 class="text-3xl font-bold mt-1">Dashboard Perusahaan</h1>
            <p class="text-slate-500 mt-1">
                Pantau pengajuan sponsorship dan realisasi benefit perusahaan Anda.
            </p>
        </div>

        <a href="{{ route('company.events.index') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium">
            Cari Kegiatan
        </a>
    </div>

    {{-- STATISTIK --}}
    <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-7">

        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <p class="text-sm text-slate-500">Total Pengajuan</p>
            <p class="text-2xl font-bold mt-2">{{ $totalPengajuan }}</p>
            <p class="text-xs text-slate-400 mt-1">Seluruh pengajuan sponsorship</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <p class="text-sm text-slate-500">Nilai Pengajuan</p>
            <p class="text-2xl font-bold mt-2">
                Rp {{ number_format($totalNilaiPengajuan, 0, ',', '.') }}
            </p>
            <p class="text-xs text-slate-400 mt-1">Tidak termasuk draft</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <p class="text-sm text-slate-500">Menunggu Keputusan</p>
            <p class="text-2xl font-bold mt-2">{{ $pengajuanDiproses }}</p>
            <p class="text-xs text-slate-400 mt-1">Pengajuan berstatus Diajukan</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <p class="text-sm text-slate-500">Benefit Terpenuhi</p>
            <p class="text-2xl font-bold mt-2">
                {{ $benefitAll > 0 ? round(($benefitDone / $benefitAll) * 100) : 0 }}%
            </p>
            <p class="text-xs text-slate-400 mt-1">Benefit berstatus selesai</p>
        </div>

    </div>

    {{-- RIWAYAT PENGAJUAN --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-5 mb-7">
        <div class="flex flex-wrap justify-between items-center gap-3 mb-4">
            <div>
                <h2 class="font-semibold text-lg">Riwayat Pengajuan Sponsorship</h2>
                <p class="text-sm text-slate-500 mt-1">
                    Daftar kegiatan dan paket sponsorship yang Anda pilih.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left text-slate-500 border-b">
                    <tr>
                        <th class="py-3 pr-4">Kegiatan</th>
                        <th class="pr-4">Paket</th>
                        <th class="pr-4">Nilai Pengajuan</th>
                        <th class="pr-4">Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($customizations as $customization)
                        <tr class="border-b last:border-0">
                            <td class="py-4 pr-4 font-medium">
                                {{ $customization->event?->nama_event ?? 'Kegiatan tidak tersedia' }}
                            </td>

                            <td class="pr-4">
                                {{ $customization->paket }}
                            </td>

                            <td class="pr-4 whitespace-nowrap">
                                Rp {{ number_format((float) $customization->estimasi_total, 0, ',', '.') }}
                            </td>

                            <td class="pr-4 whitespace-nowrap text-slate-500">
                                {{ $customization->created_at->format('d/m/Y') }}
                            </td>

                            <td>
                                @php
                                    $statusClass = match($customization->status) {
                                        'Disetujui' => 'bg-green-100 text-green-700',
                                        'Ditolak' => 'bg-red-100 text-red-700',
                                        'Diajukan' => 'bg-amber-100 text-amber-700',
                                        'Draft' => 'bg-slate-100 text-slate-600',
                                        default => 'bg-blue-100 text-blue-700',
                                    };
                                @endphp

                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                    {{ $customization->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">
                                Belum ada pengajuan sponsorship.
                                <div class="mt-2">
                                    <a href="{{ route('company.events.index') }}"
                                       class="text-indigo-600 hover:underline font-medium">
                                        Jelajahi kegiatan yang tersedia
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- RINGKASAN BENEFIT --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-5">
        <h2 class="font-semibold text-lg">Realisasi Benefit Sponsorship</h2>
        <p class="text-sm text-slate-500 mt-1 mb-5">
            Pantau pemenuhan benefit dari kerja sama sponsorship Anda.
        </p>

        <div class="flex justify-between text-sm mb-2">
            <span class="text-slate-600">Benefit selesai</span>
            <span class="font-semibold">{{ $benefitDone }} dari {{ $benefitAll }}</span>
        </div>

        <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
            <div
                class="bg-indigo-600 h-3 rounded-full transition-all"
                style="width: {{ $benefitAll > 0 ? ($benefitDone / $benefitAll) * 100 : 0 }}%">
            </div>
        </div>

        <p class="text-xs text-slate-400 mt-2">
            Progres dihitung berdasarkan benefit yang telah dinyatakan selesai.
        </p>
    </div>

@else

    {{-- DASHBOARD PANITIA / KOORDINATOR / ROLE LAIN --}}
    <div class="flex flex-wrap justify-between gap-3 items-end mb-8">
        <div>
            <p class="text-sm text-indigo-600 font-semibold">OVERVIEW</p>
            <h1 class="text-3xl font-bold mt-1">Dashboard Sponsorship</h1>
            <p class="text-slate-500 mt-1">
                Ringkasan kondisi kerja sama dan pemenuhan sponsor.
            </p>
        </div>

        @if(auth()->user()->role === 'Koordinator')
            <a href="{{ route('reports.export') }}"
               class="bg-slate-900 text-white px-4 py-2 rounded-xl text-sm">
                Unduh laporan CSV
            </a>
        @endif
    </div>

    @if(auth()->user()->role === 'Koordinator')
        <form method="GET" action="{{ route('dashboard') }}"
              class="bg-white border rounded-2xl p-4 mb-5 grid sm:grid-cols-4 gap-3">

            <select name="kategori" class="border rounded-xl p-2 text-sm">
                <option value="">Semua kategori</option>
                @foreach($sponsors->pluck('kategori')->unique() as $kategori)
                    <option value="{{ $kategori }}" @selected(request('kategori') === $kategori)>
                        {{ $kategori }}
                    </option>
                @endforeach
            </select>

            <input type="date" name="dari" value="{{ request('dari') }}"
                   class="border rounded-xl p-2 text-sm">

            <input type="date" name="sampai" value="{{ request('sampai') }}"
                   class="border rounded-xl p-2 text-sm">

            <button class="bg-slate-900 text-white rounded-xl p-2 text-sm">
                Terapkan Filter
            </button>
        </form>
    @endif

    <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-7">
        @foreach([
            ['Total Sponsor', $sponsors->count()],
            ['Dana Diterima', 'Rp '.number_format($totalDana, 0, ',', '.')],
            ['Benefit Selesai', $benefitDone.' / '.$benefitAll],
            ['Rata-rata Health Score', $avgScore.'/100']
        ] as [$label, $value])
            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <p class="text-sm text-slate-500">{{ $label }}</p>
                <p class="text-2xl font-bold mt-2">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="bg-white border rounded-2xl p-5">
        <h2 class="font-semibold text-lg mb-4">Daftar Sponsor</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left text-slate-500 border-b">
                    <tr>
                        <th class="py-3">Sponsor</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Benefit</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($sponsors as $s)
                        <tr class="border-b last:border-0">
                            <td class="py-3 font-medium">{{ $s->nama_sponsor }}</td>
                            <td>{{ $s->kategori }}</td>
                            <td>{{ $s->status_kerja_sama }}</td>
                            <td>
                                {{ $s->benefits->where('status_benefit', 'Selesai')->count() }}/{{ $s->benefits->count() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-400">
                                Belum ada data sponsor.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endif

@endsection
