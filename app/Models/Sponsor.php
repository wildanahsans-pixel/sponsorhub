<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Sponsor extends Model {
 protected $fillable=['user_id','nama_sponsor','kontak_person','email','nomor_telepon','kategori','status_kerja_sama'];
 public function user(){return $this->belongsTo(User::class);}
 public function activities(){return $this->hasMany(ActivityLog::class);}
 public function benefits(){return $this->hasMany(Benefit::class);}
 public function payments(){return $this->hasMany(Payment::class);}
 public function agreements(){return $this->hasMany(Agreement::class);}
 public function customizations(){return $this->hasMany(Customization::class);}
}
