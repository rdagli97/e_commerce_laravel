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
        Schema::create('saled_products', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('title');
            $table->string('description');
            $table->double('price');
            $table->integer('piece')->default(1);
            $table->string('image')->default("https://climate.onep.go.th/wp-content/uploads/2020/01/default-image.jpg");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saled_products');
    }
};
