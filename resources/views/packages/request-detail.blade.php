
@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <a href="{{ route('customizations.requests') }}"
           class="inline-flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-800 font-medium mb-4">
            ← Kembali ke Daftar Pengajuan
        </a>

        <h1 class="text-2xl font-bold text-slate-800">
            Detail Pengajuan Sponsorship
        </h1>
        <p class="text-sm text-slate-500 mt-1">
            Periksa informasi pengajuan sebelum memberikan keputusan.
        </p>
    </div>

    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl bg-rose-50 border border-rose-200 text-rose-700 p-4 text-sm">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Informasi Pengajuan --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-800">
                        Informasi Pengajuan
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Informasi perusahaan dan paket sponsorship.
                    </p>
                </div>

                <div class="p-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                        <div>
                            <p class="text-xs text-slate-500 mb-1">Perusahaan</p>
                            <p class="font-semibold text-slate-800">
                                {{ $customization->sponsor->nama_sponsor ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-500 mb-1">Kegiatan</p>
                            <p class="font-semibold text-slate-800">
                                {{ $customization->event->nama_event ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-500 mb-1">Paket Sponsorship</p>
                            <p class="font-semibold text-indigo-600">
                                {{ $customization->paket }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-500 mb-1">Estimasi Total</p>
                            <p class="text-xl font-bold text-slate-800">
                                Rp{{ number_format((float) $customization->estimasi_total, 0, ',', '.') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-500 mb-1">Tanggal Pengajuan</p>
                            <p class="text-sm font-medium text-slate-700">
                                {{ $customization->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-500 mb-1">Status Pengajuan</p>

                            @if($customization->status === 'Diajukan')
                                <span class="inline-flex rounded-full bg-amber-100 text-amber-700 px-3 py-1 text-xs font-semibold">
                                    Menunggu Keputusan
                                </span>
                            @elseif($customization->status === 'Disetujui')
                                <span class="inline-flex rounded-full bg-emerald-100 text-emerald-700 px-3 py-1 text-xs font-semibold">
                                    Disetujui
                                </span>
                            @elseif($customization->status === 'Ditolak')
                                <span class="inline-flex rounded-full bg-rose-100 text-rose-700 px-3 py-1 text-xs font-semibold">
                                    Ditolak
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-slate-100 text-slate-600 px-3 py-1 text-xs">
                                    {{ $customization->status }}
                                </span>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            {{-- Benefit --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
                <div class="p-5 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-800">
                        Benefit Tambahan yang Dipilih
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Daftar benefit tambahan dari perusahaan.
                    </p>
                </div>

                <div class="p-5">
                    @if(!empty($customization->benefit_terpilih))
                        <div class="space-y-3">
                            @foreach($customization->benefit_terpilih as $benefit)
                                <div class="flex items-center gap-3 rounded-xl bg-slate-50 border border-slate-100 p-3">
                                    <span class="flex items-center justify-center w-7 h-7 rounded-full bg-emerald-100 text-emerald-600">
                                        ✓
                                    </span>
                                    <span class="text-sm font-medium text-slate-700">
                                        {{ $benefit }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-slate-500">
                            Tidak ada benefit tambahan yang dipilih.
                        </p>
                    @endif

                    @if($customization->event && $customization->event->proposal_path)
                        <div class="mt-5 pt-5 border-t border-slate-100">
                            <a href="{{ route('events.proposal', $customization->event->id) }}"
                               target="_blank"
                               class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 text-indigo-600 hover:bg-indigo-50 px-4 py-2 text-sm font-semibold transition">
                                Lihat Proposal Kegiatan ↗
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Riwayat Keputusan --}}
            @if($customization->status !== 'Diajukan')
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
                    <div class="p-5 border-b border-slate-200">
                        <h2 class="font-semibold text-slate-800">
                            Riwayat Keputusan
                        </h2>
                    </div>

                    <div class="p-5 space-y-4">
                        <div>
                            <p class="text-xs text-slate-500">Keputusan</p>
                            <p class="font-semibold text-slate-800">
                                {{ $customization->status }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-500">Diputuskan Oleh</p>
                            <p class="font-medium text-slate-700">
                                {{ $customization->approver->name ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-500">Tanggal Keputusan</p>
                            <p class="font-medium text-slate-700">
                                {{ $customization->tanggal_keputusan?->format('d/m/Y H:i') ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-500">Catatan Keputusan</p>
                            <p class="text-sm text-slate-700">
                                {{ $customization->catatan_keputusan ?: '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        {{-- Keputusan Panitia --}}
        <div>
            @if($customization->status === 'Diajukan')
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden sticky top-6">

                    <div class="p-5 border-b border-slate-200">
                        <h2 class="font-semibold text-slate-800">
                            Keputusan Panitia
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">
                            Tentukan hasil evaluasi pengajuan.
                        </p>
                    </div>

                    <form method="POST"
                          action="{{ route('customizations.decision', $customization->id) }}"
                          class="p-5 space-y-5">

                        @csrf

                        <div>
                            <label for="keputusan" class="block text-sm font-medium text-slate-700 mb-2">
                                Keputusan
                            </label>

                            <select name="keputusan"
                                    id="keputusan"
                                    required
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                                <option value="">Pilih keputusan</option>
                                <option value="Disetujui" @selected(old('keputusan') === 'Disetujui')>
                                    Setujui Pengajuan
                                </option>
                                <option value="Ditolak" @selected(old('keputusan') === 'Ditolak')>
                                    Tolak Pengajuan
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="catatan_keputusan" class="block text-sm font-medium text-slate-700 mb-2">
                                Catatan / Alasan Penolakan
                            </label>

                            <textarea name="catatan_keputusan"
                                      id="catatan_keputusan"
                                      rows="5"
                                      class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none"
                                      placeholder="Tuliskan alasan penolakan atau catatan evaluasi...">{{ old('catatan_keputusan') }}</textarea>

                            <p class="text-xs text-slate-400 mt-2">
                                Wajib diisi apabila pengajuan ditolak.
                            </p>
                        </div>

                        <button type="submit"
                                onclick="return confirm('Apakah kamu yakin ingin menyimpan keputusan ini?')"
                                class="w-full rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white py-3 text-sm font-semibold transition">
                            Simpan Keputusan
                        </button>

                    </form>
                </div>
            @else
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="font-semibold text-slate-800">
                        Pengajuan Telah Diproses
                    </h2>
                    <p class="text-sm text-slate-500 mt-2">
                        Keputusan untuk pengajuan ini sudah disimpan dan tidak dapat diubah.
                    </p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
