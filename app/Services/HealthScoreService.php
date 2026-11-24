<?php

namespace App\Services;

use App\Models\Sponsor;

class HealthScoreService
{
    public function calculate(Sponsor $sponsor): array
    {
        $activities = $sponsor->activities;
        $benefits = $sponsor->benefits;
        $payments = $sponsor->payments;

        // Transparent scoring heuristic: response 30%, payment 35%, benefit completion 35%.
        $response = $activities->count()
            ? ($activities->where('status_respons', 'Direspons')->count() / $activities->count()) * 100
            : 0;
        $payment = $payments->count()
            ? ($payments->whereIn('status_pembayaran', ['Diterima','Lunas'])->count() / $payments->count()) * 100
            : 0;
        $benefit = $benefits->count()
            ? ($benefits->where('status_benefit', 'Selesai')->count() / $benefits->count()) * 100
            : 0;

        $score = round(($response * .30) + ($payment * .35) + ($benefit * .35));
        $category = $score >= 80 ? 'Sehat' : ($score >= 50 ? 'Waspada' : 'Berisiko');
        $recommendation = match ($category) {
            'Sehat' => 'Pertahankan komunikasi dan pastikan seluruh benefit terdokumentasi.',
            'Waspada' => 'Jadwalkan follow-up dan prioritaskan item pembayaran atau benefit yang tertunda.',
            default => 'Segera hubungi sponsor, klarifikasi kendala pembayaran, dan susun rencana pemenuhan benefit.'
        };

        return compact('response','payment','benefit','score','category','recommendation');
    }
}
