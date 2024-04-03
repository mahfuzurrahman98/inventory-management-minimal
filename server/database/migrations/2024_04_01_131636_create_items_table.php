<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('description');
            $table->string('image')->nullable(); // Assuming image is a string field containing the image path
            $table->integer('quantity')->default(0); // Assuming quantity is an integer field
            $table->foreignId('inventory_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('items');
    }
};
