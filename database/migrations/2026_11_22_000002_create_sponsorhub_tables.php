<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('sponsors',function(Blueprint $t){$t->id();$t->string('nama_sponsor',100);$t->string('kontak_person',100);$t->string('email',100)->unique();$t->string('nomor_telepon',20);$t->string('kategori',50)->default('Umum');$t->string('status_kerja_sama',30)->default('Prospek');$t->timestamps();});
  Schema::create('aktivitas_sponsor',function(Blueprint $t){$t->id();$t->foreignId('sponsor_id')->constrained()->cascadeOnDelete();$t->foreignId('panitia_id')->constrained('users');$t->string('jenis_aktivitas',50);$t->dateTime('tanggal_aktivitas');$t->text('deskripsi')->nullable();$t->string('status_respons',30)->default('Belum Direspons');$t->timestamps();});
  Schema::create('benefits',function(Blueprint $t){$t->id();$t->foreignId('sponsor_id')->constrained()->cascadeOnDelete();$t->string('nama_benefit',100);$t->decimal('nilai',15,2)->default(0);$t->date('tanggal_target');$t->date('tanggal_realisasi')->nullable();$t->string('status_benefit',30)->default('Belum Selesai');$t->timestamps();});
  Schema::create('benefit_evidences',function(Blueprint $t){$t->id();$t->foreignId('benefit_id')->constrained()->cascadeOnDelete();$t->foreignId('uploaded_by')->constrained('users');$t->string('file_path')->nullable();$t->text('public_url')->nullable();$t->text('keterangan');$t->string('status_verifikasi',30)->default('Menunggu Verifikasi');$t->text('catatan_verifikasi')->nullable();$t->timestamps();});
  Schema::create('agreements',function(Blueprint $t){$t->id();$t->foreignId('sponsor_id')->constrained()->cascadeOnDelete();$t->foreignId('bendahara_id')->constrained('users');$t->decimal('nominal',15,2);$t->json('daftar_benefit');$t->string('status',30)->default('Final');$t->timestamps();});
  Schema::create('customizations',function(Blueprint $t){$t->id();$t->foreignId('sponsor_id')->constrained()->cascadeOnDelete();$t->string('paket',30);$t->json('benefit_terpilih');$t->decimal('estimasi_total',15,2);$t->string('status',30)->default('Draft');$t->timestamps();});
  Schema::create('payments',function(Blueprint $t){$t->id();$t->foreignId('sponsor_id')->constrained()->cascadeOnDelete();$t->foreignId('agreement_id')->nullable()->constrained()->nullOnDelete();$t->decimal('jumlah_pembayaran',15,2);$t->date('tanggal_jatuh_tempo');$t->date('tanggal_pembayaran')->nullable();$t->string('status_pembayaran',30)->default('Menunggu Pembayaran');$t->string('bukti_transfer')->nullable();$t->timestamps();});
  Schema::create('payment_verifications',function(Blueprint $t){$t->id();$t->foreignId('payment_id')->constrained()->cascadeOnDelete();$t->foreignId('bendahara_id')->constrained('users');$t->dateTime('waktu_verifikasi');$t->text('catatan')->nullable();$t->timestamps();});
 }
 public function down(): void { foreach(['payment_verifications','payments','customizations','agreements','benefit_evidences','benefits','aktivitas_sponsor','sponsors'] as $table) Schema::dropIfExists($table); }
};
