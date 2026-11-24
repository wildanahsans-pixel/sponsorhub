<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ActivityLog extends Model {
 protected $table='aktivitas_sponsor';
 protected $fillable=['sponsor_id','panitia_id','jenis_aktivitas','tanggal_aktivitas','deskripsi','status_respons'];
 protected $casts=['tanggal_aktivitas'=>'datetime'];
 public function sponsor(){return $this->belongsTo(Sponsor::class);}
}
