@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">
            Pilih Kegiatan untuk Disponsori
        </h1>
        <p class="text-gray-500 mt-2">
            Jelajahi kegiatan yang tersedia dan pilih kegiatan yang sesuai
            dengan tujuan sponsorship perusahaan Anda.
        </p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if($events->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 p-10 text-center">
            <h2 class="text-lg font-semibold text-gray-700">
                Belum Ada Kegiatan Tersedia
            </h2>
            <p class="text-gray-500 mt-2">
                Saat ini belum ada kegiatan yang dipublikasikan oleh panitia.
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach($events as $event)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">

                    <div class="p-6 flex-1">

                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-green-100 text-green-700">
                                Tersedia
                            </span>

                            <span class="text-xs text-gray-500">
                                {{ $event->tanggal_mulai->format('d M Y') }}
                            </span>
                        </div>

                        <h2 class="text-lg font-bold text-gray-800 mb-3">
                            {{ $event->nama_event }}
                        </h2>

                        <p class="text-sm text-gray-600 mb-5">
                            {{ \Illuminate\Support\Str::limit($event->deskripsi, 130) }}
                        </p>

                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-start gap-2">
                                <span class="font-semibold min-w-20">Lokasi</span>
                                <span>{{ $event->lokasi }}</span>
                            </div>

                            <div class="flex items-start gap-2">
                                <span class="font-semibold min-w-20">Tanggal</span>
                                <span>
                                    {{ $event->tanggal_mulai->format('d M Y') }}

                                    @if($event->tanggal_selesai)
                                        – {{ $event->tanggal_selesai->format('d M Y') }}
                                    @endif
                                </span>
                            </div>

                            @if($event->target_peserta)
                                <div class="flex items-start gap-2">
                                    <span class="font-semibold min-w-20">Target</span>
                                    <span>
                                        {{ number_format($event->target_peserta) }} peserta
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="px-6 pb-6 flex gap-3">
                        <a href="{{ route('company.events.show', $event) }}"
                           class="flex-1 text-center px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
                            Lihat Detail
                        </a>

                        @if($event->proposal_path)
                            <a href="{{ route('company.events.proposal', $event) }}"
                               class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm font-semibold hover:bg-gray-50 transition">
                                Proposal
                            </a>
                        @endif
                    </div>

                </div>
            @endforeach

        </div>
    @endif

</div>
@endsection