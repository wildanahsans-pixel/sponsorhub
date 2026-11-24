
@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('events.index') }}"
       class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
        ← Kembali ke Manajemen Kegiatan
    </a>

    <h1 class="text-3xl font-bold text-slate-900 mt-3">
        Tambah Kegiatan
    </h1>
    <p class="text-slate-500 mt-1">
        Masukkan informasi kegiatan dan unggah proposal sponsorship.
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

<form action="{{ route('events.store') }}"
      method="POST"
      enctype="multipart/form-data">
    @csrf

    <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-5">

        <div>
            <h2 class="font-bold text-lg text-slate-900">
                Informasi Kegiatan
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Lengkapi informasi dasar kegiatan yang akan ditawarkan kepada perusahaan.
            </p>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">
                Nama Kegiatan <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   name="nama_event"
                   value="{{ old('nama_event') }}"
                   required
                   maxlength="150"
                   placeholder="Contoh: Seminar Nasional Teknologi 2027"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">
                Deskripsi Kegiatan <span class="text-red-500">*</span>
            </label>
            <textarea name="deskripsi"
                      rows="5"
                      required
                      placeholder="Jelaskan tujuan, konsep, sasaran, dan gambaran kegiatan..."
                      class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Tanggal Mulai <span class="text-red-500">*</span>
                </label>
                <input type="date"
                       name="tanggal_mulai"
                       value="{{ old('tanggal_mulai') }}"
                       required
                       class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Tanggal Selesai
                </label>
                <input type="date"
                       name="tanggal_selesai"
                       value="{{ old('tanggal_selesai') }}"
                       class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">
                Lokasi Kegiatan <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   name="lokasi"
                   value="{{ old('lokasi') }}"
                   required
                   maxlength="255"
                   placeholder="Contoh: Gedung Samantha Krida, Universitas Brawijaya"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">
                Target Peserta
            </label>
            <input type="number"
                   name="target_peserta"
                   value="{{ old('target_peserta') }}"
                   min="1"
                   placeholder="Contoh: 500"
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div class="border-t border-slate-200 pt-5">
            <h2 class="font-bold text-lg text-slate-900">
                Dokumen Proposal
            </h2>
            <p class="text-sm text-slate-500 mt-1 mb-4">
                Unggah proposal sponsorship dalam format PDF. Maksimal 10 MB.
            </p>

            <input type="file"
                   name="proposal"
                   accept=".pdf,application/pdf"
                   class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">

            <p class="text-xs text-slate-400 mt-2">
                Dokumen proposal dapat dilihat dan diunduh oleh perusahaan setelah kegiatan dipublikasikan.
            </p>
        </div>

        <div class="border-t border-slate-200 pt-5">
            <h2 class="font-bold text-lg text-slate-900 mb-3">
                Status Publikasi
            </h2>

            <select name="status"
                    required
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="Draft" {{ old('status', 'Draft') === 'Draft' ? 'selected' : '' }}>
                    Draft
                </option>
                <option value="Publikasi" {{ old('status') === 'Publikasi' ? 'selected' : '' }}>
                    Publikasi
                </option>
                <option value="Selesai" {{ old('status') === 'Selesai' ? 'selected' : '' }}>
                    Selesai
                </option>
            </select>

            <p class="text-xs text-slate-400 mt-2">
                Hanya kegiatan berstatus Publikasi yang nantinya ditampilkan kepada perusahaan.
            </p>
        </div>

        <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 border-t border-slate-200 pt-5">
            <a href="{{ route('events.index') }}"
               class="text-center rounded-xl border border-slate-300 px-5 py-3 font-semibold text-slate-600 hover:bg-slate-50">
                Batal
            </a>

            <button type="submit"
                    class="rounded-xl bg-indigo-600 hover:bg-indigo-700 px-6 py-3 font-semibold text-white transition">
                Simpan Kegiatan
            </button>
        </div>
    </div>
</form>
@endsection
