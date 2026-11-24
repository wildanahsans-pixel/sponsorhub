<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sponsors', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->unique()->after('id')->constrained('users')->nullOnDelete();
        });

        // Hubungkan akun demo Perusahaan yang sudah ada tanpa mengubah data sponsor lama.
        $company = DB::table('users')->where('email', 'perusahaan@sponsorhub.test')->first();
        if ($company) {
            $sponsor = DB::table('sponsors')->where('user_id', $company->id)->first();
            if (!$sponsor) {
                $existing = DB::table('sponsors')->where('email', 'perusahaan@sponsorhub.test')->first();
                if ($existing && !$existing->user_id) {
                    DB::table('sponsors')->where('id', $existing->id)->update(['user_id' => $company->id]);
                } else {
                    DB::table('sponsors')->insert([
                        'user_id' => $company->id, 'nama_sponsor' => 'Perusahaan Demo',
                        'kontak_person' => $company->name, 'email' => $company->email,
                        'nomor_telepon' => '0800000000', 'kategori' => 'Umum',
                        'status_kerja_sama' => 'Prospek', 'created_at' => now(), 'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::table('sponsors', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
