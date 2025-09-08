
<?php
// 2025_08_29_040507_create_fimecos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fimecos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('responsable_id')->nullable();
            $table->foreign('responsable_id')->references('id')->on('users')->onDelete('set null');

            $table->string('nom', 100); // Nom de la campagne FIMECO
            $table->text('description')->nullable();
            $table->date('debut');
            $table->date('fin');
            // $table->decimal('cible', 15, 2); // Montant plus réaliste

            $table->enum('statut', ['active', 'inactive', 'cloturee'])->default('active');

            // Timestamps standards
            $table->timestamps();
            $table->softDeletes();

            // Index pour performance
            $table->index(['debut', 'fin', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fimecos');
    }
};
