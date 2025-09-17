<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('annonces', function (Blueprint $table) {
            // Clé primaire
            $table->uuid('id')->primary();

            // Informations essentielles
            $table->string('titre', 200)->comment('Titre de l\'annonce');
            $table->text('contenu')->comment('Contenu principal de l\'annonce');

            // Type et priorité
            $table->enum('type_annonce', [
                'evenement',
                'administrative',
                'pastorale',
                'urgence',
                'information'
            ])->comment('Type d\'annonce');

            $table->enum('niveau_priorite', [
                'normal',
                'important',
                'urgent'
            ])->default('normal')->comment('Niveau de priorité');

            // Audience
            $table->enum('audience_cible', [
                'tous',
                'membres',
                'leadership',
                'jeunes'
            ])->default('tous')->comment('Audience ciblée');

            // Dates
            $table->timestamp('publie_le')->nullable()->comment('Date/heure de publication');
            $table->timestamp('expire_le')->nullable()->comment('Date/heure d\'expiration');
            $table->date('date_evenement')->nullable()->comment('Date de l\'événement');

            // Canaux de diffusion essentiels
            $table->boolean('afficher_site_web')->default(true)->comment('Afficher sur le site web');
            $table->boolean('annoncer_culte')->default(false)->comment('Annoncer pendant le culte');

            // Contact et lieu
            $table->uuid('contact_principal_id')->nullable()->comment('Contact principal');
            $table->string('lieu_evenement')->nullable()->comment('Lieu de l\'événement');

            // Statut
            $table->enum('statut', [
                'brouillon',
                'publiee',
                'expiree'
            ])->default('brouillon')->comment('Statut de l\'annonce');

            // Audit minimal
            $table->uuid('cree_par')->nullable()->comment('Membres créateur');

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('contact_principal_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('cree_par')->references('id')->on('users')->onDelete('set null');

            // Index essentiels
            $table->index(['statut', 'publie_le']);
            $table->index(['type_annonce', 'audience_cible']);
            $table->index(['niveau_priorite', 'publie_le']);
            $table->index(['date_evenement']);
        });

        // Vue simple pour les annonces actives
        DB::statement("
            CREATE VIEW annonces_actives AS
            SELECT
                a.*,
                CONCAT(contact.nom, ' ', contact.prenom) as nom_contact,
                CONCAT(auteur.nom, ' ', auteur.prenom) as nom_auteur
            FROM annonces a
            LEFT JOIN users contact ON a.contact_principal_id = contact.id
            LEFT JOIN users auteur ON a.cree_par = auteur.id
            WHERE a.statut = 'publiee'
                AND a.deleted_at IS NULL
                AND (a.expire_le IS NULL OR a.expire_le > NOW())
            ORDER BY a.niveau_priorite DESC, a.publie_le DESC
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS annonces_actives");
        Schema::dropIfExists('annonces');
    }
};
