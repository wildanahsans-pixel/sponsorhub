
@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    <a href="{{ route('company.events.index') }}"
       class="text-sm text-blue-600 hover:underline">
        ← Kembali ke Daftar Kegiatan
    </a>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mt-5 overflow-hidden">

        <div class="p-6 md:p-8">

            <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-green-100 text-green-700">
                    Tersedia untuk Sponsorship
                </span>

                <span class="text-sm text-gray-500">
                    Dipublikasikan
                </span>
            </div>

            <h1 class="text-3xl font-bold text-gray-800 mb-4">
                {{ $event->nama_event }}
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">

                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Tanggal Kegiatan</p>
                    <p class="font-semibold text-gray-800 mt-1">
                        {{ $event->tanggal_mulai->format('d F Y') }}

                        @if($event->tanggal_selesai)
                            – {{ $event->tanggal_selesai->format('d F Y') }}
                        @endif
                    </p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Lokasi</p>
                    <p class="font-semibold text-gray-800 mt-1">
                        {{ $event->lokasi }}
                    </p>
                </div>

                @if($event->target_peserta)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Target Peserta</p>
                        <p class="font-semibold text-gray-800 mt-1">
                            {{ number_format($event->target_peserta) }} peserta
                        </p>
                    </div>
                @endif

            </div>

            <div class="mb-8">
                <h2 class="text-lg font-bold text-gray-800 mb-3">
                    Deskripsi Kegiatan
                </h2>

                <div class="text-gray-600 leading-relaxed whitespace-pre-line">
                    {{ $event->deskripsi }}
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6">

                <h2 class="text-lg font-bold text-gray-800 mb-2">
                    Proposal Kegiatan
                </h2>

                <p class="text-sm text-gray-500 mb-4">
                    Pelajari proposal untuk mengetahui informasi dan rencana
                    pelaksanaan kegiatan secara lebih lengkap.
                </p>

                @if($event->proposal_path)
                    <a href="{{ route('company.events.proposal', $event) }}"
                       class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 text-gray-700 font-semibold text-sm hover:bg-gray-50 transition">
                        Lihat / Unduh Proposal
                    </a>
                @else
                    <p class="text-sm text-amber-600">
                        Proposal kegiatan belum tersedia.
                    </p>
                @endif

            </div>

        </div>

        <div class="bg-gray-50 border-t border-gray-200 p-6 md:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">

            <div>
                <p class="font-semibold text-gray-800">
                    Tertarik menjadi sponsor kegiatan ini?
                </p>
                <p class="text-sm text-gray-500 mt-1">
                    Lanjutkan untuk melihat paket dan mengatur benefit sponsorship.
                </p>
            </div>

            <a href="{{ route('customization.index', ['event_id' => $event->id]) }}"
               class="w-full sm:w-auto text-center px-6 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">
                Pilih Kegiatan & Kustomisasi
            </a>

        </div>

    </div>

</div>
@endsection
