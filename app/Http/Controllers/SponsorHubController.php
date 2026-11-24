<?php

namespace App\Http\Controllers;

use App\Models\{Sponsor,ActivityLog,Benefit,BenefitEvidence,Payment,Agreement,Customization,PaymentVerification,Event};
use App\Services\HealthScoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SponsorHubController extends Controller
{
  

public function dashboard(Request $request)
{

    if ($request->user()->role === 'Perusahaan') {
        $sponsor = Sponsor::where('user_id', $request->user()->id)
            ->firstOrFail();

        $customizations = Customization::with('event')
            ->where('sponsor_id', $sponsor->id)
            ->latest()
            ->get();

        // Benefit yang sudah selesai
        $benefitDone = Benefit::where('sponsor_id', $sponsor->id)
            ->where('status_benefit', 'Selesai')
            ->count();

        // Target benefit berdasarkan pengajuan yang disetujui
        $benefitPerPackage = [
            'Bronze' => 7,
            'Silver' => 13,
            'Gold' => 23,
        ];

        $benefitAll = 0;

        foreach ($customizations->where('status', 'Disetujui') as $item) {
            $jumlahBenefitPaket = $benefitPerPackage[$item->paket] ?? 0;
            $jumlahBenefitTambahan = count($item->benefit_terpilih ?? []);

            $benefitAll += $jumlahBenefitPaket + $jumlahBenefitTambahan;
        }

        $totalPengajuan = $customizations->count();

        $totalNilaiPengajuan = $customizations
            ->whereNotIn('status', ['Draft', 'Ditolak'])
            ->sum('estimasi_total');

        $pengajuanDiproses = $customizations
            ->where('status', 'Diajukan')
            ->count();

        return view('dashboard', compact(
            'sponsor',
            'customizations',
            'totalPengajuan',
            'totalNilaiPengajuan',
            'pengajuanDiproses',
            'benefitAll',
            'benefitDone'
        ));
    }

    $query = Sponsor::query()->with(['payments', 'benefits']);

    if ($request->filled('kategori') && $request->user()->role === 'Koordinator') {
        $query->where('kategori', $request->kategori);
    }

    if ($request->filled('dari')) {
        $query->whereDate('created_at', '>=', $request->dari);
    }

    if ($request->filled('sampai')) {
        $query->whereDate('created_at', '<=', $request->sampai);
    }

    $sponsors = $query->get();
    $sponsorIds = $sponsors->pluck('id');

    $totalDana = Payment::whereIn('sponsor_id', $sponsorIds)
        ->whereIn('status_pembayaran', ['Diterima', 'Lunas'])
        ->sum('jumlah_pembayaran');

    $benefitAll = Benefit::whereIn('sponsor_id', $sponsorIds)->count();

    $benefitDone = Benefit::whereIn('sponsor_id', $sponsorIds)
        ->where('status_benefit', 'Selesai')
        ->count();

    $health = app(HealthScoreService::class);
    $scores = $sponsors->map(fn ($s) => $health->calculate($s));
    $avgScore = $scores->count() ? round($scores->avg('score')) : 0;

    return view('dashboard', compact(
        'sponsors',
        'totalDana',
        'benefitAll',
        'benefitDone',
        'avgScore'
    ));
}


    public function healthScore()
    {
        $sponsors = Sponsor::with(['activities','benefits','payments'])->get();
        $service = app(HealthScoreService::class);
        $scores = $sponsors->map(fn($s) => ['sponsor'=>$s] + $service->calculate($s));
        return view('health.index', compact('scores'));
    }

    public function sponsors()
    {
        $sponsors = Sponsor::with('activities')->latest()->get();
        return view('sponsors.index', compact('sponsors'));
    }

    public function storeSponsor(Request $r)
    {
        $data = $r->validate(['nama_sponsor'=>'required|string|max:100','kontak_person'=>'required|string|max:100','email'=>'required|email|max:100','nomor_telepon'=>'required|string|max:20','kategori'=>'required|string|max:50']);
        Sponsor::create($data + ['status_kerja_sama'=>'Prospek']);
        return back()->with('success','Data sponsor berhasil ditambahkan.');
    }

    public function updateSponsor(Request $r, Sponsor $sponsor)
    {
        $data = $r->validate(['nama_sponsor'=>'required|string|max:100','kontak_person'=>'required|string|max:100','email'=>'required|email|max:100','nomor_telepon'=>'required|string|max:20','kategori'=>'required|string|max:50','status_kerja_sama'=>'required|string|max:30']);
        $sponsor->update($data);
        return back()->with('success','Data sponsor diperbarui.');
    }

    public function storeFollowUp(Request $r, Sponsor $sponsor)
    {
        $data = $r->validate(['jenis_aktivitas'=>'required|string|max:50','tanggal_aktivitas'=>'required|date','deskripsi'=>'nullable|string','status_respons'=>'required|in:Direspons,Belum Direspons']);
        $sponsor->activities()->create($data + ['panitia_id'=>$r->user()->id]);
        return back()->with('success','Log follow-up tersimpan.');
    }

    public function evidence()
    {
        $benefits = Benefit::with('sponsor')->orderBy('tanggal_target')->get();
        return view('benefits.evidence', compact('benefits'));
    }

    public function uploadEvidence(Request $r)
    {
        $data = $r->validate([
            'benefit_id'=>'required|exists:benefits,id','keterangan'=>'required|string|max:1000',
            'file'=>'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120','public_url'=>'nullable|url|required_without:file'
        ]);
        $path = $r->file('file')?->store('benefit-evidence','public');
        BenefitEvidence::create(['benefit_id'=>$data['benefit_id'],'uploaded_by'=>$r->user()->id,'file_path'=>$path,'public_url'=>$data['public_url']??null,'keterangan'=>$data['keterangan'],'status_verifikasi'=>'Menunggu Verifikasi']);
        Benefit::whereKey($data['benefit_id'])->update(['status_benefit'=>'Menunggu Verifikasi']);
        return back()->with('success','Bukti berhasil diunggah dan menunggu verifikasi.');
    }

 
public function customization(Request $request)
{
    $sponsor = Sponsor::where('user_id', $request->user()->id)
        ->firstOrFail();

    // Ambil kegiatan yang dipilih perusahaan
    $event = Event::where('id', $request->query('event_id'))
        ->where('status', 'Publikasi')
        ->first();

    // Perusahaan wajib memilih kegiatan yang sudah dipublikasikan
    if (!$event) {
        return redirect()
            ->route('company.events.index')
            ->with('error', 'Silakan pilih kegiatan yang tersedia terlebih dahulu.');
    }

    $customizations = Customization::where('sponsor_id', $sponsor->id)
        ->latest()
        ->get();

    return view('packages.customization', compact(
        'sponsor',
        'customizations',
        'event'
    ));
}



public function saveCustomization(Request $r)
{
    // 1. Validasi input dasar
    $data = $r->validate([
        'event_id' => 'required|exists:events,id',

        'paket' => 'required|in:Bronze,Silver,Gold',

        'benefit_terpilih' => 'nullable|array',

        'benefit_terpilih.*' => [
            'string',
            'distinct',
            'in:Logo pada banner,Booth promosi,Mention media sosial,Logo pada backdrop,Ad-libs oleh MC,Konten kolaborasi'
        ],

        'aksi' => 'required|in:draft,ajukan',
    ]);

    // 2. Pastikan kegiatan masih dipublikasikan
    $event = Event::where('id', $data['event_id'])
        ->where('status', 'Publikasi')
        ->firstOrFail();

    // 3. Harga paket
    $prices = [
        'Bronze' => 2000000,
        'Silver' => 5000000,
        'Gold' => 10000000,
    ];

    // 4. Benefit tambahan yang sudah termasuk dalam setiap paket
    $includedBenefits = [
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

    // 5. Ambil benefit tambahan yang dipilih perusahaan
    $benefits = $data['benefit_terpilih'] ?? [];

    // 6. Cegah benefit bawaan paket dipilih dan dibayar dua kali
    $duplicateBenefits = array_intersect(
        $benefits,
        $includedBenefits[$data['paket']]
    );

    if (!empty($duplicateBenefits)) {
        throw \Illuminate\Validation\ValidationException::withMessages([
            'benefit_terpilih' =>
                'Benefit berikut sudah termasuk dalam paket: '
                . implode(', ', $duplicateBenefits)
                . '. Silakan hapus pilihan tersebut.'
        ]);
    }

    // 7. Hitung estimasi biaya
    $pricePerBenefit = 250000;

    $totalBenefit = count($benefits) * $pricePerBenefit;

    $total = $prices[$data['paket']] + $totalBenefit;

    // 8. Ambil data sponsor milik perusahaan yang login
    $sponsor = Sponsor::where('user_id', $r->user()->id)
        ->firstOrFail();

    // 9. Simpan kustomisasi
    Customization::create([
        'sponsor_id' => $sponsor->id,
        'event_id' => $event->id,
        'paket' => $data['paket'],
        'benefit_terpilih' => $benefits,
        'estimasi_total' => $total,
        'status' => $data['aksi'] === 'draft'
            ? 'Draft'
            : 'Diajukan',
    ]);

    // 10. Berikan notifikasi
    return back()->with(
        'success',
        $data['aksi'] === 'draft'
            ? 'Draf kustomisasi berhasil disimpan.'
            : 'Pengajuan kustomisasi berhasil dikirim kepada panitia.'
    );
}

    private function authorizePanitia(): void
    {
        abort_unless(auth()->user()?->role === 'Panitia', 403);
    }

    public function events()
    {
        $this->authorizePanitia();

        $events = Event::with('creator')
            ->latest()
            ->get();

        return view('events.index', compact('events'));
    }

    public function createEvent()
    {
        $this->authorizePanitia();

        return view('events.create');
    }

    public function storeEvent(Request $request)
    {
        $this->authorizePanitia();

        $data = $request->validate([
            'nama_event' => 'required|string|max:150',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'required|string|max:255',
            'target_peserta' => 'nullable|integer|min:1',
            'proposal' => 'nullable|file|mimes:pdf|max:10240',
            'status' => 'required|in:Draft,Publikasi,Selesai',
        ]);

        if ($request->hasFile('proposal')) {
            $data['proposal_path'] = $request->file('proposal')
                ->store('proposals', 'public');
        }

        unset($data['proposal']);

        $data['created_by'] = $request->user()->id;

        Event::create($data);

        return redirect()
            ->route('events.index')
            ->with('success', 'Event berhasil ditambahkan.');
    }

    public function editEvent(Event $event)
    {
        $this->authorizePanitia();

        return view('events.edit', compact('event'));
    }

    public function updateEvent(Request $request, Event $event)
    {
        $this->authorizePanitia();

        $data = $request->validate([
            'nama_event' => 'required|string|max:150',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'required|string|max:255',
            'target_peserta' => 'nullable|integer|min:1',
            'proposal' => 'nullable|file|mimes:pdf|max:10240',
            'status' => 'required|in:Draft,Publikasi,Selesai',
        ]);

        if ($request->hasFile('proposal')) {
            if ($event->proposal_path) {
                Storage::disk('public')->delete($event->proposal_path);
            }

            $data['proposal_path'] = $request->file('proposal')
                ->store('proposals', 'public');
        }

        unset($data['proposal']);

        $event->update($data);

        return redirect()
            ->route('events.index')
            ->with('success', 'Event berhasil diperbarui.');
    }

    public function destroyEvent(Event $event)
    {
        $this->authorizePanitia();

        if ($event->proposal_path) {
            Storage::disk('public')->delete($event->proposal_path);
        }

        $event->delete();

        return redirect()
            ->route('events.index')
            ->with('success', 'Event berhasil dihapus.');
    }

    public function downloadProposal(Event $event)
    {
        abort_unless(
            auth()->user()?->role === 'Panitia'
            || $event->status === 'Publikasi',
            403
        );

        abort_unless($event->proposal_path, 404);

        abort_unless(
            Storage::disk('public')->exists($event->proposal_path),
            404
        );

        return Storage::disk('public')->download(
            $event->proposal_path
        );
    }


public function companyEvents()
{
    abort_unless(auth()->user()?->role === 'Perusahaan', 403);

    $events = Event::where('status', 'Publikasi')
        ->latest()
        ->get();

    return view('events.company-index', compact('events'));
}

public function showCompanyEvent(Event $event)
{
    abort_unless(auth()->user()?->role === 'Perusahaan', 403);
    abort_unless($event->status === 'Publikasi', 404);

    return view('events.show', compact('event'));
}


    public function verification(Request $request)
    {
        $sponsor = Sponsor::where('user_id', $request->user()->id)->firstOrFail();
        $evidences = BenefitEvidence::with('benefit.sponsor')->whereHas('benefit', fn($q) => $q->where('sponsor_id', $sponsor->id))->latest()->get();
        return view('benefits.verification', compact('evidences'));
    }

    public function verifyEvidence(Request $r, BenefitEvidence $evidence)
    {
        abort_unless($evidence->benefit && $evidence->benefit->sponsor && $evidence->benefit->sponsor->user_id === $r->user()->id, 403);
        $data = $r->validate(['keputusan'=>'required|in:Approve,Reject','catatan'=>'nullable|string|max:1000']);
        if ($data['keputusan']==='Reject' && blank($data['catatan']??null)) return back()->withErrors(['catatan'=>'Catatan wajib diisi jika bukti ditolak.']);
        DB::transaction(function() use ($data,$evidence) {
            $status = $data['keputusan']==='Approve'?'Approved':'Rejected';
            $evidence->update(['status_verifikasi'=>$status,'catatan_verifikasi'=>$data['catatan']??null]);
            $benefit = $evidence->benefit;
            $benefit->update(['status_benefit'=>$status==='Approved'?'Selesai':'Belum Selesai','tanggal_realisasi'=>$status==='Approved'?now()->toDateString():null]);
        });
        return back()->with('success','Hasil verifikasi berhasil disimpan.');
    }

   
public function agreements()
{
    // Pengajuan yang sudah disetujui dan belum memiliki perjanjian
    $customizations = Customization::with(['sponsor', 'event'])
        ->where('status', 'Disetujui')
        ->whereNotIn(
            'id',
            Agreement::whereNotNull('customization_id')
                ->select('customization_id')
        )
        ->latest()
        ->get();

    // Riwayat perjanjian
    $agreements = Agreement::with([
        'sponsor',
        'customization.event',
    ])->latest()->get();

    return view('payments.agreements', compact(
        'customizations',
        'agreements'
    ));
}


   
public function storeAgreement(Request $r)
{
    $data = $r->validate([
        'customization_id' => 'required|exists:customizations,id',
    ]);

    DB::transaction(function () use ($data, $r) {

        $customization = Customization::with(['sponsor', 'event'])
            ->whereKey($data['customization_id'])
            ->lockForUpdate()
            ->firstOrFail();

        // Hanya pengajuan yang disetujui yang dapat dibuatkan perjanjian
        if ($customization->status !== 'Disetujui') {
            abort(422, 'Pengajuan belum disetujui Panitia.');
        }

        // Cegah perjanjian ganda
        if (Agreement::where('customization_id', $customization->id)->exists()) {
            abort(422, 'Pengajuan ini sudah memiliki perjanjian.');
        }

        $includedBenefits = [
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

        $packageBenefits = $includedBenefits[$customization->paket] ?? [];

        $additionalBenefits = $customization->benefit_terpilih ?? [];

        $allBenefits = array_values(array_unique(array_merge(
            $packageBenefits,
            $additionalBenefits
        )));

        $agreement = Agreement::create([
            'customization_id' => $customization->id,
            'sponsor_id' => $customization->sponsor_id,
            'bendahara_id' => $r->user()->id,
            'nominal' => $customization->estimasi_total,
            'daftar_benefit' => $allBenefits,
            'status' => 'Final',
        ]);

        $agreement->sponsor->update([
            'status_kerja_sama' => 'Menunggu Pembayaran',
        ]);

        Payment::create([
            'sponsor_id' => $customization->sponsor_id,
            'agreement_id' => $agreement->id,
            'jumlah_pembayaran' => $customization->estimasi_total,
            'tanggal_jatuh_tempo' => now()->addDays(14)->toDateString(),
            'status_pembayaran' => 'Menunggu Pembayaran',
        ]);
    });

    return redirect()
        ->route('agreements.index')
        ->with('success', 'Kesepakatan berhasil dibuat dan tagihan telah diterbitkan.');
}


public function companyPayments(Request $request)
{
    $sponsor = Sponsor::where('user_id', $request->user()->id)
        ->firstOrFail();

    $payments = Payment::with('agreement.customization.event')
        ->where('sponsor_id', $sponsor->id)
        ->latest()
        ->get();

    return view('payments.company', compact('payments'));
}

public function uploadPaymentProof(Request $request, Payment $payment)
{
    $sponsor = Sponsor::where('user_id', $request->user()->id)
        ->firstOrFail();

    // Pastikan tagihan benar-benar milik perusahaan yang login
    abort_unless($payment->sponsor_id === $sponsor->id, 403);

    if ($payment->status_pembayaran !== 'Menunggu Pembayaran') {
        abort(422, 'Tagihan ini tidak dapat menerima bukti transfer.');
    }

    $data = $request->validate([
        'bukti_transfer' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
    ]);

    // Hapus bukti lama jika perusahaan mengunggah ulang
    if ($payment->bukti_transfer) {
        Storage::disk('public')->delete($payment->bukti_transfer);
    }

    $path = $request->file('bukti_transfer')
        ->store('payment-proofs', 'public');

    $payment->update([
        'bukti_transfer' => $path,
    ]);

    return back()->with(
        'success',
        'Bukti transfer berhasil diunggah dan menunggu verifikasi Bendahara.'
    );
}


    public function payments()
    {
        $payments = Payment::with('sponsor')->latest()->get();
        return view('payments.index', compact('payments'));
    }

    
public function verifyPayment(Request $r, Payment $payment)
{
    $data = $r->validate([
        'catatan' => 'nullable|string|max:1000',
    ]);

    DB::transaction(function () use ($r, $payment, $data) {

        $item = Payment::whereKey($payment->id)
            ->lockForUpdate()
            ->firstOrFail();

        // Pembayaran tidak boleh diverifikasi dua kali
        if ($item->status_pembayaran === 'Lunas') {
            abort(422, 'Pembayaran ini sudah diverifikasi.');
        }

        // Hanya tagihan yang menunggu pembayaran yang dapat diverifikasi
        if ($item->status_pembayaran !== 'Menunggu Pembayaran') {
            abort(422, 'Status pembayaran tidak valid untuk diverifikasi.');
        }

        // Bukti transfer wajib tersedia
        if (
            blank($item->bukti_transfer) ||
            !Storage::disk('public')->exists($item->bukti_transfer)
        ) {
            abort(422, 'Bukti transfer belum tersedia atau file tidak ditemukan.');
        }

        $item->update([
            'status_pembayaran' => 'Lunas',
            'tanggal_pembayaran' => now()->toDateString(),
        ]);

        PaymentVerification::create([
            'payment_id' => $item->id,
            'bendahara_id' => $r->user()->id,
            'waktu_verifikasi' => now(),
            'catatan' => $data['catatan'] ?? null,
        ]);

        $item->sponsor->update([
            'status_kerja_sama' => 'Aktif',
        ]);
    });

    return back()->with(
        'success',
        'Pembayaran berhasil diverifikasi. Status kerja sama sponsor diperbarui.'
    );
}


public function customizationRequests()
{
    $pengajuan = Customization::with(['sponsor', 'event'])
        ->whereIn('status', ['Diajukan', 'Disetujui', 'Ditolak'])
        ->latest()
        ->get();

    return view('packages.requests', compact('pengajuan'));
}

public function showCustomizationRequest(Customization $customization)
{
    $customization->load(['sponsor', 'event', 'approver']);

    return view('packages.request-detail', compact('customization'));
}

public function decideCustomization(Request $request, Customization $customization)
{
    $validated = $request->validate([
        'keputusan' => 'required|in:Disetujui,Ditolak',
        'catatan_keputusan' => 'nullable|string|max:2000',
    ]);

    if (
        $validated['keputusan'] === 'Ditolak' &&
        empty(trim($validated['catatan_keputusan'] ?? ''))
    ) {
        return back()
            ->withErrors(['catatan_keputusan' => 'Alasan penolakan wajib diisi.'])
            ->withInput();
    }

    DB::transaction(function () use ($customization, $validated, $request) {
        $item = Customization::whereKey($customization->id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($item->status !== 'Diajukan') {
            abort(422, 'Pengajuan ini sudah diproses atau belum diajukan.');
        }

        $item->update([
            'status' => $validated['keputusan'],
            'diputuskan_oleh' => $request->user()->id,
            'tanggal_keputusan' => now(),
            'catatan_keputusan' => $validated['catatan_keputusan'] ?? null,
        ]);
    });

    return redirect()
        ->route('customizations.show', $customization->id)
        ->with('success', 'Keputusan pengajuan berhasil disimpan.');
}


    public function exportReport(): StreamedResponse
    {
        $rows = Sponsor::with(['payments','benefits','activities'])->get();
        return response()->streamDownload(function() use ($rows) {
            $out=fopen('php://output','w');
            fputcsv($out,['Sponsor','Kategori','Status','Total Pembayaran','Benefit Selesai','Total Benefit','Jumlah Aktivitas']);
            foreach($rows as $s) fputcsv($out,[$s->nama_sponsor,$s->kategori,$s->status_kerja_sama,$s->payments->whereIn('status_pembayaran',['Lunas','Diterima'])->sum('jumlah_pembayaran'),$s->benefits->where('status_benefit','Selesai')->count(),$s->benefits->count(),$s->activities->count()]);
            fclose($out);
        }, 'laporan-sponsorship.csv', ['Content-Type'=>'text/csv']);
    }
}
