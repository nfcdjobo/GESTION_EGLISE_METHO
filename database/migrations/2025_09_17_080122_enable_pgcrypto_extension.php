<?php

// =================================================================
// 2025_08_29_000000_enable_pgcrypto_extension.php
// =================================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Activer l'extension pgcrypto pour les fonctions de hachage
        DB::statement('CREATE EXTENSION IF NOT EXISTS "pgcrypto"');

        // Vérifier que l'extension est bien installée
        $result = DB::select("SELECT EXISTS(SELECT 1 FROM pg_extension WHERE extname = 'pgcrypto') as exists");

        if (!$result[0]->exists) {
            throw new Exception('Impossible d\'activer l\'extension pgcrypto. Vérifiez les permissions de la base de données.');
        }
    }

    public function down(): void
    {
        // Ne pas supprimer l'extension car elle pourrait être utilisée ailleurs
        // DB::statement('DROP EXTENSION IF EXISTS "pgcrypto"');
    }
};
