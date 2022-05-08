<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('currency_ar', 100);
            $table->string('currency_en', 100);
            $table->text('about_ar');
            $table->text('about_en');
            $table->text('terms_ar');
            $table->text('terms_en');
            $table->string('theme', 100)->nullable();
            $table->string('image', 150)->nullable();
            $table->boolean('color')->default(1);
            $table->boolean('size')->default(1);
            $table->boolean('store')->default(1);
            $table->boolean('delivery')->default(1);
            $table->boolean('payment')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
