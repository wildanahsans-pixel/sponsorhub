
@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <div>
        <h1 class="text-3xl font-bold text-slate-800">
            Kesepakatan Nominal & Benefit
        </h1>
        <p class="text-slate-500 mt-1">
            UC-08 · Buat kesepakatan berdasarkan pengajuan yang disetujui Panitia.
        </p>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-6">
        <h2 class="text-lg font-semibold mb-4">Buat Kesepakatan Baru</h2>

        @if($customizations->isEmpty())
            <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-4">
                Belum ada pengajuan yang disetujui dan belum memiliki kesepakatan.
            </div>
        @else
            <form method="POST" action="{{ route('agreements.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Pilih Pengajuan Sponsorship
                    </label>

                    <select name="customization_id" required
                        class="w-full border border-slate-300 rounded-xl p-3">
                        <option value="">-- Pilih pengajuan --</option>

                        @foreach($customizations as $item)
                            <option value="{{ $item->id }}"
                                {{ old('customization_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->sponsor->nama_sponsor }}
                                — {{ $item->event->nama_event ?? 'Kegiatan tidak tersedia' }}
                                — {{ $item->paket }}
                                — Rp{{ number_format($item->estimasi_total, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>

                    @error('customization_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 text-sm text-indigo-800">
                    Nominal dan daftar benefit akan diambil otomatis dari pengajuan yang dipilih.
                    Bendahara tidak perlu memasukkan ulang data tersebut.
                </div>

                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-xl font-medium">
                    Buat Kesepakatan dan Tagihan
                </button>
            </form>
        @endif
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-6">
        <h2 class="text-lg font-semibold mb-4">Riwayat Kesepakatan</h2>

        @forelse($agreements as $a)
            <div class="border-b border-slate-100 py-4 last:border-0">
                <div class="flex flex-col md:flex-row md:justify-between gap-2">
                    <div>
                        <p class="font-semibold text-slate-800">
                            {{ $a->sponsor->nama_sponsor }}
                        </p>

                        <p class="text-sm text-slate-500">
                            {{ $a->customization->event->nama_event ?? 'Kegiatan tidak tersedia' }}
                            · Paket {{ $a->customization->paket ?? '-' }}
                        </p>
                    </div>

                    <div class="md:text-right">
                        <p class="font-semibold text-slate-800">
                            Rp{{ number_format($a->nominal, 0, ',', '.') }}
                        </p>
                        <span class="text-sm text-slate-500">{{ $a->status }}</span>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-slate-500 text-sm">Belum ada riwayat kesepakatan.</p>
        @endforelse
    </div>

</div>
@endsection
