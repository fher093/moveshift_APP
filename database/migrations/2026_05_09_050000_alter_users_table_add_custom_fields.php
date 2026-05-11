<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name')->nullable()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->string('zone')->nullable()->comment('Barrio/Zona')->after('phone');
            $table->string('career')->nullable()->comment('Carrera/Programa')->after('zone');
            $table->string('microsoft_id')->nullable()->unique()->after('career');
            $table->text('avatar')->nullable()->after('microsoft_id');
            $table->timestamp('last_login')->nullable()->after('avatar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'last_name',
                'phone',
                'zone',
                'career',
                'microsoft_id',
                'avatar',
                'last_login'
            ]);
        });
    }
};
