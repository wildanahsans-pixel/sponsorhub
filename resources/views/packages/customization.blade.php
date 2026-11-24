
@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="max-w-7xl mx-auto px-4 pt-6">
        <div class="bg-green-100 text-green-700 rounded-lg p-4">
            {{ session('success') }}
        </div>
    </div>
@endif

@if(session('error'))
    <div class="max-w-7xl mx-auto px-4 pt-6">
        <div class="bg-red-100 text-red-700 rounded-lg p-4">
            {{ session('error') }}
        </div>
    </div>
@endif

@if($errors->any())
    <div class="max-w-7xl mx-auto px-4 pt-6">
        <div class="bg-red-100 text-red-700 rounded-lg p-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

@php
    $packages = [
        'Bronze' => [
            'price' => 2000000,
            'description' => 'Pilihan awal bagi perusahaan yang ingin memperkenalkan merek kepada peserta kegiatan.',
            'benefits' => [
                'Logo pada banner',
                'Mention media sosial',
                'Pencantuman nama perusahaan pada daftar sponsor',
                'Pencantuman logo pada slide sponsor',
                'Penyebutan sponsor oleh MC sebanyak 1 kali',
                'Sertifikat atau piagam apresiasi sponsor',
                'Dokumentasi digital kegiatan',
            ],
        ],
        'Silver' => [
            'price' => 5000000,
            'description' => 'Memberikan eksposur lebih luas dan kesempatan promosi langsung kepada peserta.',
            'benefits' => [
                'Logo pada banner',
                'Mention media sosial',
                'Pencantuman nama perusahaan pada daftar sponsor',
                'Pencantuman logo pada slide sponsor',
                'Penyebutan sponsor oleh MC sebanyak 1 kali',
                'Sertifikat atau piagam apresiasi sponsor',
                'Dokumentasi digital kegiatan',
                'Logo pada backdrop',
                'Booth promosi standar',
                'Unggahan khusus sponsor melalui Instagram Feed',
                'Distribusi brosur atau merchandise kepada peserta',
                'Pemutaran video promosi maksimal 30 detik',
                'Dokumentasi booth dan aktivitas promosi',
            ],
        ],
        'Gold' => [
            'price' => 10000000,
            'description' => 'Paket premium untuk perusahaan yang menginginkan visibilitas dan aktivasi merek lebih besar.',
            'benefits' => [
                'Logo pada banner',
                'Mention media sosial',
                'Pencantuman nama perusahaan pada daftar sponsor',
                'Pencantuman logo pada slide sponsor',
                'Penyebutan sponsor oleh MC sebanyak 1 kali',
                'Sertifikat atau piagam apresiasi sponsor',
                'Dokumentasi digital kegiatan',
                'Logo pada backdrop',
                'Booth promosi standar',
                'Unggahan khusus sponsor melalui Instagram Feed',
                'Distribusi brosur atau merchandise kepada peserta',
                'Pemutaran video promosi maksimal 30 detik',
                'Dokumentasi booth dan aktivitas promosi',
                'Konten kolaborasi bersama akun penyelenggara',
                'Logo sebagai sponsor utama dengan penempatan prioritas',
                'Booth promosi dengan lokasi prioritas',
                'Kesempatan product sampling kepada peserta',
                'Pemutaran video promosi maksimal 60 detik',
                'Kesempatan memberikan sambutan atau presentasi maksimal 5 menit',
                'Penempatan logo pada sertifikat peserta',
                'Publikasi apresiasi khusus setelah kegiatan',
                'Laporan dokumentasi dan realisasi benefit sponsorship',
                'Penyebutan sponsor oleh MC hingga 3 kali',
            ],
        ],
    ];

    $additionalBenefits = [
        'Logo pada banner' => [
            'price' => 250000,
            'description' => 'Logo perusahaan ditampilkan pada banner resmi kegiatan.',
        ],
        'Booth promosi' => [
            'price' => 250000,
            'description' => 'Kesempatan membuka booth promosi di lokasi acara.',
        ],
        'Mention media sosial' => [
            'price' => 250000,
            'description' => 'Penyebutan nama perusahaan melalui media sosial resmi.',
        ],
        'Logo pada backdrop' => [
            'price' => 250000,
            'description' => 'Logo perusahaan ditampilkan pada backdrop kegiatan.',
        ],
        'Ad-libs oleh MC' => [
            'price' => 250000,
            'description' => 'Penyebutan sponsor oleh MC selama acara berlangsung.',
        ],
        'Konten kolaborasi' => [
            'price' => 250000,
            'description' => 'Konten promosi kolaborasi bersama akun resmi kegiatan.',
        ],
    ];

    $includedBenefitMap = [
        'Bronze' => [
            'Logo pada banner',
            'Mention media sosial',
        ],
        'Silver' => [
            'Logo pada banner',
            'Mention media sosial',
            'Booth promosi',
            'Logo pada backdrop',
        ],
        'Gold' => [
            'Logo pada banner',
            'Mention media sosial',
            'Booth promosi',
            'Logo pada backdrop',
            'Ad-libs oleh MC',
            'Konten kolaborasi',
        ],
    ];

    $selectedPackage = old('paket', 'Bronze');

    if (!isset($packages[$selectedPackage])) {
        $selectedPackage = 'Bronze';
    }

    $selectedBenefits = old('benefit_terpilih', []);
    $selectedBenefits = is_array($selectedBenefits) ? $selectedBenefits : [];
@endphp

<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-900">
            Kustomisasi Paket Sponsorship
        </h1>
        <p class="text-slate-500 mt-1">
            UC-04 · Sesuaikan paket dan benefit dengan kebutuhan perusahaan Anda.
        </p>
    </div>

    <form method="POST" action="{{ route('customization.store') }}" id="customizationForm">
        @csrf

        <input type="hidden" name="event_id" value="{{ $event->id }}">

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">

            <div class="xl:col-span-2 space-y-6">

                {{-- INFORMASI KEGIATAN --}}
                <section class="bg-white border border-slate-200 rounded-2xl p-5">

                    <h2 class="font-bold text-lg mb-4">
                        Informasi Kegiatan
                    </h2>

                    <h3 class="font-bold text-xl text-slate-900">
                        {{ $event->nama_event }}
                    </h3>

                    <p class="text-sm text-slate-500 mt-2">
                        Lokasi: {{ $event->lokasi }}
                    </p>

                    <p class="text-sm text-slate-500 mt-1">
                        Tanggal:
                        {{ $event->tanggal_mulai->format('d F Y') }}

                        @if($event->tanggal_selesai)
                            – {{ $event->tanggal_selesai->format('d F Y') }}
                        @endif
                    </p>

                    @if($event->target_peserta)
                        <p class="text-sm text-slate-500 mt-1">
                            Target peserta: {{ number_format($event->target_peserta) }} orang
                        </p>
                    @endif

                    <div class="mt-4">
                        <h4 class="font-semibold text-sm mb-2">
                            Deskripsi Kegiatan
                        </h4>

                        <p class="text-sm text-slate-600 whitespace-pre-line">
                            {{ $event->deskripsi }}
                        </p>
                    </div>

                    @if($event->proposal_path)
                        <a href="{{ route('company.events.proposal', $event) }}"
                           class="inline-block mt-4 text-sm font-semibold text-indigo-600 hover:underline">
                            Lihat / Unduh Proposal →
                        </a>
                    @else
                        <p class="text-sm text-amber-600 mt-4">
                            Proposal belum tersedia.
                        </p>
                    @endif

                </section>

                {{-- PILIH PAKET --}}
                <section class="bg-white border border-slate-200 rounded-2xl p-5">

                    <h2 class="font-bold text-lg">
                        Pilih Paket Dasar
                    </h2>

                    <p class="text-sm text-slate-500 mt-1 mb-5">
                        Setiap paket memiliki fasilitas dan benefit yang berbeda.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        @foreach($packages as $name => $package)

                            <label
                                class="package-card relative flex flex-col border-2 rounded-xl p-4 cursor-pointer transition hover:border-indigo-400
                                {{ $selectedPackage === $name ? 'border-indigo-600 bg-indigo-50' : 'border-slate-200' }}"
                            >

                                <input
                                    type="radio"
                                    name="paket"
                                    value="{{ $name }}"
                                    data-price="{{ $package['price'] }}"
                                    class="package-radio"
                                    @checked($selectedPackage === $name)
                                    required
                                >

                                <div class="mt-3">
                                    <h3 class="font-bold text-lg">
                                        {{ $name }}
                                    </h3>

                                    <p class="text-indigo-600 font-bold text-xl mt-1">
                                        Rp{{ number_format($package['price'], 0, ',', '.') }}
                                    </p>

                                    <p class="text-xs text-slate-500 mt-2 min-h-10">
                                        {{ $package['description'] }}
                                    </p>
                                </div>

                                <div class="border-t border-slate-200 mt-4 pt-3 flex-1">

                                    <p class="font-semibold text-xs mb-3 text-slate-700">
                                        Benefit yang diperoleh ({{ count($package['benefits']) }})
                                    </p>

                                    <ul class="space-y-2">
                                        @foreach($package['benefits'] as $benefit)
                                            <li class="flex gap-2 text-xs text-slate-600">
                                                <span class="text-green-600 font-bold shrink-0">✓</span>
                                                <span>{{ $benefit }}</span>
                                            </li>
                                        @endforeach
                                    </ul>

                                </div>

                                <div
                                    class="text-xs text-indigo-600 font-bold mt-4 selected-label"
                                    style="{{ $selectedPackage === $name ? '' : 'display:none' }}"
                                >
                                    ✓ Paket dipilih
                                </div>

                            </label>

                        @endforeach

                    </div>

                </section>

                {{-- BENEFIT TAMBAHAN --}}
                <section class="bg-white border border-slate-200 rounded-2xl p-5">

                    <h2 class="font-bold text-lg">
                        Benefit Tambahan
                    </h2>

                    <p class="text-sm text-slate-500 mt-1 mb-4">
                        Tambahkan fasilitas di luar benefit paket. Setiap benefit tambahan dikenakan biaya Rp250.000.
                    </p>

                    <div class="space-y-3">

                        @foreach($additionalBenefits as $name => $benefit)

                            <label class="benefit-card flex items-start gap-3 border border-slate-200 rounded-xl p-4 transition">

                                <input
                                    type="checkbox"
                                    name="benefit_terpilih[]"
                                    value="{{ $name }}"
                                    data-price="{{ $benefit['price'] }}"
                                    class="benefit-checkbox mt-1 w-4 h-4 accent-indigo-600"
                                    @checked(in_array($name, $selectedBenefits))
                                >

                                <div class="flex-1">

                                    <div class="flex flex-col sm:flex-row sm:justify-between gap-1">
                                        <span class="font-semibold text-sm">
                                            {{ $name }}
                                        </span>

                                        <span class="font-bold text-sm text-indigo-600">
                                            + Rp{{ number_format($benefit['price'], 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <p class="text-xs text-slate-500 mt-1">
                                        {{ $benefit['description'] }}
                                    </p>

                                    <p class="included-message text-xs text-green-700 font-medium mt-2 hidden">
                                        ✓ Sudah termasuk dalam paket yang dipilih.
                                    </p>

                                </div>

                            </label>

                        @endforeach

                    </div>

                    <p class="text-xs text-slate-400 mt-4">
                        Benefit yang sudah termasuk dalam paket otomatis dinonaktifkan untuk mencegah perhitungan ganda.
                    </p>

                </section>

            </div>

            {{-- RINGKASAN --}}
            <aside class="xl:col-span-1">

                <div class="bg-white border border-slate-200 rounded-2xl p-5 xl:sticky xl:top-6 shadow-sm">

                    <h2 class="font-bold text-lg mb-5">
                        Ringkasan Kustomisasi
                    </h2>

                    <div class="bg-slate-50 rounded-xl p-4 mb-4">
                        <p class="text-xs text-slate-500 uppercase font-semibold">
                            Kegiatan
                        </p>

                        <p class="font-bold text-sm mt-2">
                            {{ $event->nama_event }}
                        </p>

                        <p class="text-xs text-slate-500 mt-1">
                            {{ $event->lokasi }}
                        </p>
                    </div>

                    {{-- PAKET --}}
                    <div class="bg-slate-50 rounded-xl p-4 mb-4">

                        <p class="text-xs text-slate-500 uppercase font-semibold">
                            Paket Dasar
                        </p>

                        <div class="flex justify-between items-center mt-2 gap-2">
                            <span id="summaryPackage" class="font-bold text-lg">
                                {{ $selectedPackage }}
                            </span>

                            <span id="summaryPackagePrice" class="font-semibold text-sm">
                                Rp{{ number_format($packages[$selectedPackage]['price'], 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="border-t border-slate-200 mt-3 pt-3">

                            <p class="font-semibold text-xs mb-2">
                                Benefit termasuk paket
                            </p>

                            <ul id="summaryPackageBenefits" class="space-y-2 text-xs text-slate-600">
                                @foreach($packages[$selectedPackage]['benefits'] as $benefit)
                                    <li class="flex gap-2">
                                        <span class="text-green-600 font-bold">✓</span>
                                        <span>{{ $benefit }}</span>
                                    </li>
                                @endforeach
                            </ul>

                        </div>

                    </div>

                    {{-- BENEFIT TAMBAHAN --}}
                    <div class="mb-5">

                        <h3 class="font-bold text-sm mb-3">
                            Benefit Tambahan
                        </h3>

                        <div id="summaryBenefits" class="space-y-3">
                            <p class="text-xs text-slate-400">
                                Belum ada benefit tambahan dipilih.
                            </p>
                        </div>

                    </div>

                    {{-- TOTAL --}}
                    <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 mb-3">

                        <p class="text-xs text-indigo-700 uppercase font-semibold">
                            Estimasi Total
                        </p>

                        <p id="summaryTotal" class="text-2xl font-bold text-indigo-700 mt-1">
                            Rp{{ number_format($packages[$selectedPackage]['price'], 0, ',', '.') }}
                        </p>

                        <div class="border-t border-indigo-200 mt-3 pt-3 space-y-2 text-xs">

                            <div class="flex justify-between gap-2">
                                <span class="text-slate-600">Harga paket</span>
                                <span id="summaryBaseCost" class="font-semibold">
                                    Rp{{ number_format($packages[$selectedPackage]['price'], 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-2">
                                <span class="text-slate-600">Benefit tambahan</span>
                                <span id="summaryExtraCost" class="font-semibold">
                                    Rp0
                                </span>
                            </div>

                        </div>

                    </div>

                    <p class="text-xs text-slate-500 mb-5">
                        Estimasi dapat berubah setelah konfirmasi dan kesepakatan dengan panitia.
                    </p>

                    <div class="space-y-3">

                        <button
                            type="submit"
                            name="aksi"
                            value="ajukan"
                            class="w-full rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 transition"
                        >
                            Kirim Pengajuan
                        </button>

                        <button
                            type="submit"
                            name="aksi"
                            value="draft"
                            class="w-full rounded-xl border border-slate-300 hover:bg-slate-50 text-indigo-600 font-semibold py-3 transition"
                        >
                            Simpan Draft
                        </button>

                    </div>

                    <div class="bg-slate-50 rounded-lg p-3 mt-4">
                        <p class="text-xs text-center text-slate-500">
                            Pengajuan akan ditinjau oleh panitia sesuai prosedur sponsorship.
                        </p>
                    </div>

                </div>

            </aside>

        </div>

    </form>

    {{-- RIWAYAT KUSTOMISASI --}}
    <section class="mt-8">

        <h2 class="font-bold text-xl mb-4">
            Riwayat Kustomisasi
        </h2>

        <div class="bg-white border border-slate-200 rounded-2xl divide-y">

            @forelse($customizations as $item)

                <div class="p-4 flex flex-wrap justify-between gap-3">

                    <div>
                        <p class="font-semibold">
                            Paket {{ $item->paket }}
                        </p>

                        <p class="text-sm text-slate-500">
                            Kegiatan:
                            {{ $item->event?->nama_event ?? 'Belum terhubung dengan kegiatan' }}
                        </p>

                        <p class="text-sm text-slate-500">
                            {{ implode(', ', $item->benefit_terpilih ?? []) ?: 'Tanpa benefit tambahan' }}
                        </p>
                    </div>

                    <div class="text-right">

                        <p class="font-bold">
                            Rp{{ number_format($item->estimasi_total, 0, ',', '.') }}
                        </p>

                        <p class="text-xs text-slate-500">
                            {{ $item->status }}
                            ·
                            {{ $item->created_at->format('d/m/Y H:i') }}
                        </p>

                    </div>

                </div>

            @empty

                <div class="p-5 text-sm text-slate-400">
                    Belum ada kustomisasi yang disimpan.
                </div>

            @endforelse

        </div>

    </section>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const packageRadios = document.querySelectorAll('.package-radio');
    const benefitCheckboxes = document.querySelectorAll('.benefit-checkbox');

    const summaryPackage = document.getElementById('summaryPackage');
    const summaryPackagePrice = document.getElementById('summaryPackagePrice');
    const summaryPackageBenefits = document.getElementById('summaryPackageBenefits');
    const summaryBenefits = document.getElementById('summaryBenefits');
    const summaryTotal = document.getElementById('summaryTotal');
    const summaryBaseCost = document.getElementById('summaryBaseCost');
    const summaryExtraCost = document.getElementById('summaryExtraCost');

    const packageData = @json($packages);
    const includedBenefitMap = @json($includedBenefitMap);

    const formatRupiah = (number) =>
        'Rp' + new Intl.NumberFormat('id-ID').format(number);

    function updateSummary() {

        const selectedPackage = document.querySelector('.package-radio:checked');

        if (!selectedPackage) return;

        const packageName = selectedPackage.value;
        const packagePrice = Number(selectedPackage.dataset.price);

        const fullPackageBenefits = packageData[packageName].benefits;
        const includedBenefits = includedBenefitMap[packageName] || [];

        let benefitTotal = 0;
        let benefitHTML = '';

        summaryPackage.textContent = packageName;
        summaryPackagePrice.textContent = formatRupiah(packagePrice);

        // Tampilkan seluruh benefit paket.
        summaryPackageBenefits.innerHTML = fullPackageBenefits.map(benefit => `
            <li class="flex gap-2">
                <span class="text-green-600 font-bold">✓</span>
                <span>${benefit}</span>
            </li>
        `).join('');

        // Perbarui tampilan kartu paket.
        packageRadios.forEach(radio => {

            const card = radio.closest('.package-card');
            const label = card.querySelector('.selected-label');

            card.classList.toggle('border-indigo-600', radio.checked);
            card.classList.toggle('bg-indigo-50', radio.checked);
            card.classList.toggle('border-slate-200', !radio.checked);

            label.style.display = radio.checked ? 'block' : 'none';

        });

        // Periksa benefit tambahan.
        benefitCheckboxes.forEach(checkbox => {

            const card = checkbox.closest('.benefit-card');
            const includedMessage = card.querySelector('.included-message');

            const isIncluded = includedBenefits.includes(checkbox.value);

            if (isIncluded) {
                checkbox.checked = false;
                checkbox.disabled = true;

                card.classList.add('opacity-60', 'bg-slate-50');
                card.classList.remove('cursor-pointer', 'border-indigo-500', 'bg-indigo-50');

                includedMessage.classList.remove('hidden');

            } else {

                checkbox.disabled = false;

                card.classList.remove('opacity-60', 'bg-slate-50');
                card.classList.add('cursor-pointer');

                includedMessage.classList.add('hidden');

            }

            card.classList.toggle(
                'border-indigo-500',
                checkbox.checked && !isIncluded
            );

            card.classList.toggle(
                'bg-indigo-50',
                checkbox.checked && !isIncluded
            );

            if (checkbox.checked && !isIncluded) {

                const price = Number(checkbox.dataset.price);

                benefitTotal += price;

                benefitHTML += `
                    <div class="flex justify-between gap-2 text-xs">
                        <span class="text-slate-600">${checkbox.value}</span>
                        <span class="font-semibold whitespace-nowrap">${formatRupiah(price)}</span>
                    </div>
                `;

            }

        });

        summaryBenefits.innerHTML = benefitHTML ||
            '<p class="text-xs text-slate-400">Belum ada benefit tambahan dipilih.</p>';

        summaryBaseCost.textContent = formatRupiah(packagePrice);
        summaryExtraCost.textContent = formatRupiah(benefitTotal);
        summaryTotal.textContent = formatRupiah(packagePrice + benefitTotal);

    }

    packageRadios.forEach(radio => {
        radio.addEventListener('change', updateSummary);
    });

    benefitCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSummary);
    });

    updateSummary();

});
</script>

@endsection
