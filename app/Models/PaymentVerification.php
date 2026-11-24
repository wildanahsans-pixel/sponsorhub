<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PaymentVerification extends Model {
 protected $fillable=['payment_id','bendahara_id','waktu_verifikasi','catatan'];
 protected $casts=['waktu_verifikasi'=>'datetime'];
}
