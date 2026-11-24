
@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Manajemen Kegiatan</h1>
        <p class="text-slate-500 mt-1">
            Kelola informasi kegiatan dan proposal sponsorship.
        </p>
    </div>

    <a href="{{ route('events.create') }}"
       class="inline-flex items-center justify-center rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-3 transition">
        + Tambah Kegiatan
    </a>
</div>

@if(session('success'))
    <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
    <div class="p-5 border-b border-slate-200">
        <h2 class="font-bold text-lg">Daftar Kegiatan</h2>
        <p class="text-sm text-slate-500 mt-1">
            Total {{ $events->count() }} kegiatan terdaftar.
        </p>
    </div>

    @forelse($events as $event)
        <div class="p-5 border-b border-slate-100 last:border-b-0">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <h3 class="font-bold text-lg text-slate-900">
                            {{ $event->nama_event }}
                        </h3>

                        @if($event->status === 'Publikasi')
                            <span class="rounded-full bg-green-100 text-green-700 text-xs font-semibold px-3 py-1">
                                Publikasi
                            </span>
                        @elseif($event->status === 'Selesai')
                            <span class="rounded-full bg-slate-100 text-slate-600 text-xs font-semibold px-3 py-1">
                                Selesai
                            </span>
                        @else
                            <span class="rounded-full bg-amber-100 text-amber-700 text-xs font-semibold px-3 py-1">
                                Draft
                            </span>
                        @endif
                    </div>

                    <p class="text-sm text-slate-600 mb-3">
                        {{ Str::limit($event->deskripsi, 180) }}
                    </p>

                    <div class="flex flex-wrap gap-x-5 gap-y-2 text-xs text-slate-500">
                        <span>
                            Lokasi: {{ $event->lokasi }}
                        </span>
                        <span>
                            Tanggal:
                            {{ $event->tanggal_mulai->format('d/m/Y') }}
                            @if($event->tanggal_selesai)
                                - {{ $event->tanggal_selesai->format('d/m/Y') }}
                            @endif
                        </span>
                        <span>
                            Target peserta:
                            {{ $event->target_peserta ? number_format($event->target_peserta, 0, ',', '.') : '-' }}
                        </span>
                    </div>

                    <p class="text-xs text-slate-400 mt-2">
                        Dibuat oleh: {{ $event->creator->name ?? '-' }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    @if($event->proposal_path)
                        <a href="{{ route('events.proposal', $event) }}"
                           class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
                            Lihat Proposal
                        </a>
                    @endif

                    <a href="{{ route('events.edit', $event) }}"
                       class="rounded-lg border border-indigo-200 px-3 py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-50">
                        Edit
                    </a>

                    <form action="{{ route('events.destroy', $event) }}"
                          method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="rounded-lg border border-red-200 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="p-10 text-center">
            <div class="text-4xl mb-3">▣</div>
            <h3 class="font-bold text-slate-800">Belum Ada Kegiatan</h3>
            <p class="text-sm text-slate-500 mt-1">
                Tambahkan kegiatan pertama untuk mulai mengelola sponsorship.
            </p>
            <a href="{{ route('events.create') }}"
               class="inline-block mt-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 text-sm font-semibold">
                Tambah Kegiatan
            </a>
        </div>
    @endforelse
</div>
@endsection
