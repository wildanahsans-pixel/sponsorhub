
@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('events.index') }}"
       class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
        ← Kembali ke Manajemen Kegiatan
    </a>

    <h1 class="text-3xl font-bold text-slate-900 mt-3">
        Edit Kegiatan
    </h1>
    <p class="text-slate-500 mt-1">
        Perbarui informasi kegiatan dan dokumen proposal.
    </p>
</div>

@if($errors->any())
    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('events.update', $event) }}"
      method="POST"
      enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-5">

        <div>
            <h2 class="font-bold text-lg text-slate-900">
                Informasi Kegiatan
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Ubah informasi kegiatan yang sudah tersimpan.
            </p>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">
                Nama Kegiatan <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   name="nama_event"
                   value="{{ old('nama_event', $event->nama_event) }}"
                   required
                   maxlength="150"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">
                Deskripsi Kegiatan <span class="text-red-500">*</span>
            </label>
            <textarea name="deskripsi"
                      rows="5"
                      required
                      class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">{{ old('deskripsi', $event->deskripsi) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Tanggal Mulai <span class="text-red-500">*</span>
                </label>
                <input type="date"
                       name="tanggal_mulai"
                       value="{{ old('tanggal_mulai', $event->tanggal_mulai->format('Y-m-d')) }}"
                       required
                       class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Tanggal Selesai
                </label>
                <input type="date"
                       name="tanggal_selesai"
                       value="{{ old('tanggal_selesai', $event->tanggal_selesai?->format('Y-m-d')) }}"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">
                Lokasi Kegiatan <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   name="lokasi"
                   value="{{ old('lokasi', $event->lokasi) }}"
                   required
                   maxlength="255"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">
                Target Peserta
            </label>
            <input type="number"
                   name="target_peserta"
                   value="{{ old('target_peserta', $event->target_peserta) }}"
                   min="1"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div class="border-t border-slate-200 pt-5">
            <h2 class="font-bold text-lg text-slate-900">
                Dokumen Proposal
            </h2>

            @if($event->proposal_path)
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 mt-3 mb-4">
                    <p class="text-sm font-semibold text-slate-700">
                        Proposal saat ini
                    </p>
                    <a href="{{ route('events.proposal', $event) }}"
                       class="inline-block mt-2 text-sm text-indigo-600 hover:underline">
                        Lihat / Unduh Proposal PDF
                    </a>
                </div>
            @else
                <p class="text-sm text-amber-600 mt-2 mb-4">
                    Belum ada proposal yang diunggah.
                </p>
            @endif

            <label class="block text-sm font-semibold text-slate-700 mb-2">
                Ganti Proposal (Opsional)
            </label>
            <input type="file"
                   name="proposal"
                   accept=".pdf,application/pdf"
                   class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:font-semibold file:text-indigo-700">

            <p class="text-xs text-slate-400 mt-2">
                Kosongkan jika tidak ingin mengganti proposal. Format PDF, maksimal 10 MB.
            </p>
        </div>

        <div class="border-t border-slate-200 pt-5">
            <label class="block text-sm font-semibold text-slate-700 mb-2">
                Status Publikasi
            </label>

            <select name="status"
                    required
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="Draft" {{ old('status', $event->status) === 'Draft' ? 'selected' : '' }}>
                    Draft
                </option>
                <option value="Publikasi" {{ old('status', $event->status) === 'Publikasi' ? 'selected' : '' }}>
                    Publikasi
                </option>
                <option value="Selesai" {{ old('status', $event->status) === 'Selesai' ? 'selected' : '' }}>
                    Selesai
                </option>
            </select>
        </div>

        <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 border-t border-slate-200 pt-5">
            <a href="{{ route('events.index') }}"
               class="text-center rounded-xl border border-slate-300 px-5 py-3 font-semibold text-slate-600 hover:bg-slate-50">
                Batal
            </a>

            <button type="submit"
                    class="rounded-xl bg-indigo-600 hover:bg-indigo-700 px-6 py-3 font-semibold text-white transition">
                Simpan Perubahan
            </button>
        </div>
    </div>
</form>
@endsection
