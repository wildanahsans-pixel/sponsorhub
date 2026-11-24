<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Benefit extends Model {
 protected $fillable=['sponsor_id','nama_benefit','nilai','tanggal_target','tanggal_realisasi','status_benefit'];
 protected $casts=['tanggal_target'=>'date','tanggal_realisasi'=>'date'];
 public function sponsor(){return $this->belongsTo(Sponsor::class);}
 public function evidences(){return $this->hasMany(BenefitEvidence::class);}
}
