<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BenefitEvidence extends Model
{
    protected $table = 'benefit_evidences';

    protected $fillable = [
        'benefit_id',
        'uploaded_by',
        'file_path',
        'public_url',
        'keterangan',
        'status_verifikasi',
        'catatan_verifikasi'
    ];

    public function benefit()
    {
        return $this->belongsTo(Benefit::class);
    }
}
