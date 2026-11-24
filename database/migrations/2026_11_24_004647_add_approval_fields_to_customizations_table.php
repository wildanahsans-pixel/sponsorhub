
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customizations', function (Blueprint $table) {
            $table->foreignId('diputuskan_oleh')
                ->nullable()
                ->after('status')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('tanggal_keputusan')->nullable();

            $table->text('catatan_keputusan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('customizations', function (Blueprint $table) {
            $table->dropForeign(['diputuskan_oleh']);

            $table->dropColumn([
                'diputuskan_oleh',
                'tanggal_keputusan',
                'catatan_keputusan',
            ]);
        });
    }
};
