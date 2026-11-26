<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Sponsor;
use App\Models\Benefit;
use App\Models\ActivityLog;
use App\Models\Payment;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['Panitia Demo','panitia@sponsorhub.test','Panitia'],
            ['Perusahaan Demo','perusahaan@sponsorhub.test','Perusahaan'],
            ['Koordinator Demo','koordinator@sponsorhub.test','Koordinator'],
            ['Bendahara Demo','bendahara@sponsorhub.test','Bendahara'],
        ] as [$name,$email,$role]) {
            User::updateOrCreate(['email'=>$email],['name'=>$name,'password'=>Hash::make('password'),'role'=>$role]);
        }

        $s = Sponsor::firstOrCreate(['email'=>'budi@kopi.test'],[
            'nama_sponsor'=>'PT Kopi Nusantara','kontak_person'=>'Budi Santoso','nomor_telepon'=>'081234567890',
            'kategori'=>'F&B','status_kerja_sama'=>'Aktif'
        ]);
        // Hubungkan akun perusahaan demo dengan sponsor yang memiliki data benefit.
$company = User::where('email', 'perusahaan@sponsorhub.test')->first();

if ($company) {
    // Lepaskan relasi dari sponsor demo lain jika sebelumnya sudah terhubung.
    Sponsor::where('user_id', $company->id)
        ->where('id', '!=', $s->id)
        ->update(['user_id' => null]);

    // Hubungkan akun perusahaan dengan PT Kopi Nusantara.
    $s->update(['user_id' => $company->id]);
}
        if (!$s->benefits()->exists()) {
            Benefit::create(['sponsor_id'=>$s->id,'nama_benefit'=>'Logo pada banner acara','nilai'=>1000000,'tanggal_target'=>now()->addDays(10),'status_benefit'=>'Selesai','tanggal_realisasi'=>now()->toDateString()]);
            Benefit::create(['sponsor_id'=>$s->id,'nama_benefit'=>'Booth promosi','nilai'=>1500000,'tanggal_target'=>now()->addDays(15),'status_benefit'=>'Belum Selesai']);
        }
        if (!$s->activities()->exists()) ActivityLog::create(['sponsor_id'=>$s->id,'panitia_id'=>User::where('role','Panitia')->first()->id,'jenis_aktivitas'=>'Follow-up','tanggal_aktivitas'=>now(),'deskripsi'=>'Mengirim proposal kerja sama','status_respons'=>'Direspons']);
        if (!$s->payments()->exists()) Payment::create(['sponsor_id'=>$s->id,'jumlah_pembayaran'=>5000000,'tanggal_jatuh_tempo'=>now()->addDays(7),'tanggal_pembayaran'=>now()->toDateString(),'status_pembayaran'=>'Lunas']);
    }
}
