<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId("users_id")->references("id")->on("users")->cascadeOnDelete();
            $table->string("name")->nullable();
            $table->text("note")->nullable();
            $table->float("panjang_badan")->default(0);
            $table->float("lebar_bahu")->default(0);
            $table->float("panjang_lengan")->default(0);
            $table->float("lingkar_lengan")->default(0);
            $table->float("lingkar_perut")->default(0);
            $table->float("lingkar_dada")->default(0);
            $table->float("lingkar_pesak")->default(0);
            $table->float("lingkar_panggul")->default(0);
            $table->float("lingkar_paha")->default(0);
            $table->float("lingkar_lutut")->default(0);
            $table->float("lingkar_tumit")->default(0);
            $table->float("panjang_celana")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sizes');
    }
};
