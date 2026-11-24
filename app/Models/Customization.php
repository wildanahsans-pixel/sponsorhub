<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customization extends Model
{
    protected $fillable = [
        'sponsor_id',
        'event_id',
        'paket',
        'benefit_terpilih',
        'estimasi_total',
        'status',
        'diputuskan_oleh',
        'tanggal_keputusan',
        'catatan_keputusan',
    ];

    protected $casts = [
        'benefit_terpilih' => 'array',
        'estimasi_total' => 'decimal:2',
        'tanggal_keputusan' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(Sponsor::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diputuskan_oleh');
    }
}
