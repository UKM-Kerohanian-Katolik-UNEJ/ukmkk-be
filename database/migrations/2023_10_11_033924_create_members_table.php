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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string("nama")->unique();
            $table->string("email")->unique();
            $table->string("no_hp")->unique();
            $table->date("tanggal_lahir");
            $table->string("nim", 12)->unique();
            $table->year("tahun_masuk");
            $table->string("fakultas_asal");
            $table->string("sekolah_asal");
            $table->string("paroki_asal");
            $table->string("provinsi_asal");
            $table->string("kabupaten_asal");
            $table->text("alamat_rumah");
            $table->text("alamat_kost");
            $table->enum("golongan_darah", ["A", "B", "AB", "O"]);
            $table->enum("is_verified", ["Pending", "Verified", "Rejected"])->default("Pending");
            $table->string("password");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
