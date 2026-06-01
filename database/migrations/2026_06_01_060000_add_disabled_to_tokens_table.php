<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tokens')) {
            return;
        }

        if (! Schema::hasColumn('tokens', 'disabled')) {
            Schema::table('tokens', function (Blueprint $table) {
                $table->boolean('disabled')->default(false)->after('token');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tokens') && Schema::hasColumn('tokens', 'disabled')) {
            Schema::table('tokens', function (Blueprint $table) {
                $table->dropColumn('disabled');
            });
        }
    }
};
