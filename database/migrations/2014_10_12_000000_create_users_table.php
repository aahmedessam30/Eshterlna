<?php

use App\Models\City;
use App\Models\Country;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fname', 100);
            $table->string('lname', 100);
            $table->string('username', 150)->unique();
            $table->string('email', 150)->unique();
            $table->string('phone', 20)->unique();
            $table->string('image', 150)->nullable();
            $table->text('device_key', 150)->nullable();
            $table->string('address')->nullable();
            $table->enum('type', ['customer', 'merchant'])->default('customer');
            $table->enum('status', ['active', 'in_active'])->default('in_active');
            $table->foreignIdFor(Country::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignIdFor(City::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
