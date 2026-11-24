<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agreement extends Model
{
    protected $fillable = [
        'customization_id',
        'sponsor_id',
        'bendahara_id',
        'nominal',
        'daftar_benefit',
        'status',
    ];

    protected $casts = [
        'daftar_benefit' => 'array',
        'nominal' => 'decimal:2',
    ];

    public function customization(): BelongsTo
    {
        return $this->belongsTo(Customization::class);
    }

    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(Sponsor::class);
    }

    public function bendahara(): BelongsTo
    {
        return $this->belongsTo(User::class, 'bendahara_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
