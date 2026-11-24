
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agreements', function (Blueprint $table) {
            $table->foreignId('customization_id')
                ->nullable()
                ->unique()
                ->after('id')
                ->constrained('customizations')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('agreements', function (Blueprint $table) {
            $table->dropForeign(['customization_id']);
            $table->dropUnique(['customization_id']);
            $table->dropColumn('customization_id');
        });
    }
};
