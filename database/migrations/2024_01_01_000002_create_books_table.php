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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->index();
            $table->string('title');
            $table->string('author');
            $table->string('publisher')->nullable();
            $table->string('isbn')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 8, 2)->nullable();
            $table->enum('status', ['available', 'lent'])->default('available');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Tenant isolation index
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'author']);
            $table->unique(['tenant_id', 'isbn']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
