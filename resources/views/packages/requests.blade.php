
@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Pengajuan Sponsorship
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Tinjau dan kelola pengajuan paket sponsorship dari perusahaan.
            </p>
        </div>

        <div class="rounded-xl bg-indigo-50 px-4 py-3">
            <p class="text-xs text-indigo-600">Total Pengajuan</p>
            <p class="text-2xl font-bold text-indigo-700">
                {{ $pengajuan->count() }}
            </p>
        </div>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <p class="text-sm text-slate-500">Menunggu Keputusan</p>
            <p class="text-2xl font-bold text-amber-600 mt-2">
                {{ $pengajuan->where('status', 'Diajukan')->count() }}
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <p class="text-sm text-slate-500">Disetujui</p>
            <p class="text-2xl font-bold text-emerald-600 mt-2">
                {{ $pengajuan->where('status', 'Disetujui')->count() }}
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <p class="text-sm text-slate-500">Ditolak</p>
            <p class="text-2xl font-bold text-rose-600 mt-2">
                {{ $pengajuan->where('status', 'Ditolak')->count() }}
            </p>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-200">
            <h2 class="font-semibold text-slate-800">Daftar Pengajuan</h2>
            <p class="text-xs text-slate-500 mt-1">
                Informasi pengajuan dari seluruh perusahaan.
            </p>
        </div>

        @if($pengajuan->isEmpty())
            <div class="p-12 text-center">
                <p class="font-semibold text-slate-700">Belum ada pengajuan</p>
                <p class="text-sm text-slate-500 mt-1">
                    Pengajuan dari perusahaan akan muncul di sini.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-4">No.</th>
                            <th class="px-5 py-4">Perusahaan</th>
                            <th class="px-5 py-4">Kegiatan</th>
                            <th class="px-5 py-4">Paket</th>
                            <th class="px-5 py-4">Estimasi Total</th>
                            <th class="px-5 py-4">Tanggal</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach($pengajuan as $item)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-4 text-slate-500">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-5 py-4 font-semibold text-slate-800">
                                    {{ $item->sponsor->nama_sponsor ?? '-' }}
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $item->event->nama_event ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    <span class="rounded-lg bg-indigo-50 text-indigo-700 px-3 py-1 font-medium">
                                        {{ $item->paket }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 font-semibold text-slate-800 whitespace-nowrap">
                                    Rp{{ number_format((float) $item->estimasi_total, 0, ',', '.') }}
                                </td>

                                <td class="px-5 py-4 text-slate-500 whitespace-nowrap">
                                    {{ $item->created_at->format('d/m/Y H:i') }}
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    @if($item->status === 'Diajukan')
                                        <span class="rounded-full bg-amber-100 text-amber-700 px-3 py-1 text-xs font-semibold">
                                            Menunggu Keputusan
                                        </span>
                                    @elseif($item->status === 'Disetujui')
                                        <span class="rounded-full bg-emerald-100 text-emerald-700 px-3 py-1 text-xs font-semibold">
                                            Disetujui
                                        </span>
                                    @elseif($item->status === 'Ditolak')
                                        <span class="rounded-full bg-rose-100 text-rose-700 px-3 py-1 text-xs font-semibold">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <a href="{{ route('customizations.show', $item->id) }}"
                                       class="inline-block rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 text-xs font-semibold transition whitespace-nowrap">
                                        Lihat Detail →
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
