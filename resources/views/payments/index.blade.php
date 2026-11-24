
@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto">

    <h1 class="text-3xl font-bold">Verifikasi Penerimaan Dana</h1>

    <p class="text-slate-500 mt-1 mb-7">
        UC-09 · Periksa bukti transfer dan konfirmasi penerimaan pembayaran.
    </p>

    <div class="space-y-5">

        @forelse($payments as $p)

            <div class="bg-white border rounded-2xl p-6 space-y-4">

                <div class="flex flex-wrap justify-between gap-3">

                    <div>
                        <h2 class="font-semibold text-lg">
                            {{ $p->sponsor->nama_sponsor }}
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Rp{{ number_format($p->jumlah_pembayaran, 0, ',', '.') }}
                        </p>

                        <p class="text-sm text-slate-500">
                            Jatuh tempo:
                            {{ $p->tanggal_jatuh_tempo?->format('d/m/Y') ?? '-' }}
                        </p>
                    </div>

                    <span class="h-fit px-3 py-1 rounded-full text-sm font-semibold
                        {{ $p->status_pembayaran === 'Lunas'
                            ? 'bg-emerald-100 text-emerald-700'
                            : 'bg-amber-100 text-amber-700' }}">

                        {{ $p->status_pembayaran }}

                    </span>

                </div>

                <div class="border-t pt-4">

                    <h3 class="font-semibold text-sm mb-2">
                        Bukti Transfer
                    </h3>

                    @if($p->bukti_transfer)

                        <a href="{{ asset('storage/' . $p->bukti_transfer) }}"
                           target="_blank"
                           class="inline-flex items-center gap-2 text-blue-600 hover:underline text-sm">

                            Lihat / Unduh Bukti Transfer

                        </a>

                    @else

                        <p class="text-sm text-rose-600">
                            Perusahaan belum mengunggah bukti transfer.
                        </p>

                    @endif

                </div>

                @if($p->status_pembayaran === 'Menunggu Pembayaran')

                    @if($p->bukti_transfer)

                        <form method="POST"
                              action="{{ route('payments.verify', $p) }}"
                              class="border-t pt-4 space-y-3">

                            @csrf

                            <div>
                                <label class="block text-sm font-medium mb-1">
                                    Catatan Pencocokan Mutasi
                                </label>

                                <textarea name="catatan"
                                          rows="2"
                                          placeholder="Contoh: Dana masuk sesuai nominal tagihan."
                                          class="w-full border rounded-lg p-3 text-sm"></textarea>
                            </div>

                            <button type="submit"
                                    onclick="return confirm('Pastikan dana sudah masuk ke rekening. Verifikasi pembayaran ini?')"
                                    class="bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg px-5 py-2.5 text-sm font-semibold">

                                Verifikasi Pembayaran

                            </button>

                        </form>

                    @else

                        <p class="text-sm text-slate-500 border-t pt-4">
                            Verifikasi belum dapat dilakukan karena bukti transfer belum tersedia.
                        </p>

                    @endif

                @else

                    <div class="border-t pt-4 text-sm text-emerald-700">
                        Pembayaran telah diverifikasi.
                        Tanggal pembayaran:
                        {{ $p->tanggal_pembayaran?->format('d/m/Y') ?? '-' }}
                    </div>

                @endif

            </div>

        @empty

            <div class="bg-white border rounded-2xl p-8 text-center text-slate-500">
                Belum ada tagihan pembayaran.
            </div>

        @endforelse

    </div>

</div>

@endsection
