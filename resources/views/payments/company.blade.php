
@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <h1 class="text-3xl font-bold">Tagihan Sponsorship</h1>
    <p class="text-slate-500 mt-1 mb-7">
        Lihat tagihan dan unggah bukti pembayaran sponsorship.
    </p>

    <div class="space-y-5">
        @forelse($payments as $payment)
            <div class="bg-white border rounded-2xl p-6 space-y-4">
                <div class="flex flex-wrap justify-between gap-3">
                    <div>
                        <h2 class="font-semibold text-lg">
                            {{ $payment->agreement?->customization?->event?->nama_event ?? 'Kegiatan Sponsorship' }}
                        </h2>

                        <p class="text-sm text-slate-500">
                            {{ $payment->agreement?->customization?->paket ?? 'Paket Sponsorship' }}
                        </p>
                    </div>

                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        {{ $payment->status_pembayaran === 'Lunas'
                            ? 'bg-emerald-100 text-emerald-700'
                            : 'bg-amber-100 text-amber-700' }}">
                        {{ $payment->status_pembayaran }}
                    </span>
                </div>

                <div class="grid md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-slate-500">Nominal Tagihan</p>
                        <p class="font-bold text-xl">
                            Rp{{ number_format($payment->jumlah_pembayaran, 0, ',', '.') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-slate-500">Jatuh Tempo</p>
                        <p class="font-semibold">
                            {{ $payment->tanggal_jatuh_tempo?->format('d/m/Y') ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-slate-500">Tanggal Pembayaran</p>
                        <p class="font-semibold">
                            {{ $payment->tanggal_pembayaran?->format('d/m/Y') ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-slate-500">Bukti Transfer</p>
                        @if($payment->bukti_transfer)
                            <a href="{{ asset('storage/' . $payment->bukti_transfer) }}"
                               target="_blank"
                               class="text-blue-600 underline">
                                Lihat bukti transfer
                            </a>
                        @else
                            <p class="text-slate-500">Belum diunggah</p>
                        @endif
                    </div>
                </div>

                @if($payment->status_pembayaran === 'Menunggu Pembayaran')
                    <div class="border-t pt-4">
                        <form method="POST"
                              action="{{ route('company.payments.proof', $payment) }}"
                              enctype="multipart/form-data"
                              class="space-y-3">
                            @csrf

                            <label class="block text-sm font-medium">
                                Unggah Bukti Transfer (JPG, PNG, PDF; maksimal 5 MB)
                            </label>

                            <input type="file"
                                   name="bukti_transfer"
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   required
                                   class="block w-full border rounded-lg p-3 text-sm">

                            <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg px-5 py-2.5 text-sm font-semibold">
                                Unggah Bukti Transfer
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white border rounded-2xl p-8 text-center text-slate-500">
                Belum ada tagihan sponsorship.
            </div>
        @endforelse
    </div>
</div>
@endsection
