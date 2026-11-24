@extends('layouts.app')
@section('content')
<h1 class="text-3xl font-bold">Upload Bukti Pemenuhan Benefit</h1><p class="text-slate-500 mb-7 mt-1">UC-03 · Unggah file PDF/gambar atau tautan publikasi.</p>
<div class="bg-white border rounded-2xl p-5 max-w-3xl"><form method="POST" enctype="multipart/form-data" action="{{ route('evidence.store') }}" class="space-y-4">@csrf
<label class="block text-sm font-medium">Pilih benefit<select name="benefit_id" required class="block w-full border rounded-xl p-3 mt-1">@foreach($benefits as $b)<option value="{{ $b->id }}">{{ $b->sponsor->nama_sponsor }} — {{ $b->nama_benefit }}</option>@endforeach</select></label>
<label class="block text-sm font-medium">File bukti (JPG/PNG/PDF, maks. 5 MB)<input type="file" name="file" accept=".jpg,.jpeg,.png,.pdf" class="block w-full border rounded-xl p-3 mt-1"></label>
<label class="block text-sm font-medium">Atau URL publik<input type="url" name="public_url" placeholder="https://..." class="block w-full border rounded-xl p-3 mt-1"></label>
<label class="block text-sm font-medium">Keterangan<textarea name="keterangan" required class="block w-full border rounded-xl p-3 mt-1"></textarea></label><button class="bg-indigo-600 text-white px-5 py-3 rounded-xl">Unggah Bukti</button></form></div>
@endsection
