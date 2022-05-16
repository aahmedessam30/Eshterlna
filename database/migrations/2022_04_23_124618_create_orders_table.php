<?php

use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Models\Store;
use App\Models\User;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Store::class)->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(PaymentMethod::class)->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(ShippingMethod::class)->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->enum('status', [0, 1, 2, 3, 4, 5])->default(0); //0 => 'pending', 1 => 'processing', 2 => 'on_delivery', 3 => 'delivered', 4 => 'canceled'
            $table->decimal('total_price', 10, 2);
            $table->decimal('total_discount', 10, 2);
            $table->decimal('total_tax', 10, 2);
            $table->decimal('total_shipping', 10, 2);
            $table->decimal('total_weight', 10, 2);
            $table->decimal('total_items', 10, 2);
            $table->timestamp('ordered_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
