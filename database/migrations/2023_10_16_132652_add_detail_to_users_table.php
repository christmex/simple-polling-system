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
            $table->longText('notes')->nullable()->after('password');
            $table->date('read_employee_terms_date')->nullable()->after('password');
            $table->date('kemnaker_join_date')->nullable()->after('password');
            $table->date('jht_join_date')->nullable()->after('password');
            $table->date('bpjs_join_date')->nullable()->after('password');
            $table->date('join_date')->nullable()->after('password');
            $table->date('permanent_date')->nullable()->after('password');
            $table->string('citizenship_number')->nullable()->after('password');
            $table->date('born_date')->nullable()->after('password');
            $table->string('born_place')->nullable()->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('notes');
            $table->dropColumn('read_employee_terms_date');
            $table->dropColumn('kemnaker_join_date');
            $table->dropColumn('jht_join_date');
            $table->dropColumn('bpjs_join_date');
            $table->dropColumn('join_date');
            $table->dropColumn('permanent_date');
            $table->dropColumn('citizenship_number');
            $table->dropColumn('born_date');
            $table->dropColumn('born_place');
        });
    }
};
