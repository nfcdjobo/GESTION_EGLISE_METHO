<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Culte;
use App\Services\CKEditorCacheService;
use Illuminate\Support\Facades\DB;

class OptimizeCKEditorCommand extends Command
{
    protected $signature = 'ckeditor:optimize 
                            {--warm-cache : Pré-remplir le cache}
                            {--analyze : Analyser les performances}
                            {--cleanup : Nettoyer les données orphelines}';

    protected $description = 'Optimise les performances de CKEditor';

    private $cacheService;

    public function __construct(CKEditorCacheService $cacheService)
    {
        parent::__construct();
        $this->cacheService = $cacheService;
    }

    public function handle()
    {
        $this->info('🚀 Optimisation CKEditor en cours...');

        if ($this->option('analyze')) {
            $this->analyzePerformance();
        }

        if ($this->option('cleanup')) {
            $this->cleanupData();
        }

        if ($this->option('warm-cache')) {
            $this->warmCache();
        }

        $this->info('✅ Optimisation terminée !');
    }

    private function analyzePerformance()
    {
        $this->info('📊 Analyse des performances...');

        // Analyser la taille des contenus
        $stats = DB::table('cultes')
            ->selectRaw('
                COUNT(*) as total_cultes,
                AVG(LENGTH(COALESCE(description, ""))) as avg_description_length,
                AVG(LENGTH(COALESCE(resume_message, ""))) as avg_message_length,
                MAX(LENGTH(COALESCE(description, ""))) as max_description_length,
                MAX(LENGTH(COALESCE(resume_message, ""))) as max_message_length
            ')
            ->first();

        $this->table(['Métrique', 'Valeur'], [
            ['Total cultes', number_format($stats->total_cultes)],
            ['Taille moyenne description', number_format($stats->avg_description_length) . ' caractères'],
            ['Taille moyenne message', number_format($stats->avg_message_length) . ' caractères'],
            ['Taille max description', number_format($stats->max_description_length) . ' caractères'],
            ['Taille max message', number_format($stats->max_message_length) . ' caractères'],
        ]);

        // Identifier les contenus volumineux
        $largeCultes = Culte::whereRaw('LENGTH(COALESCE(description, "")) > 10000 OR LENGTH(COALESCE(resume_message, "")) > 10000')
            ->limit(10)
            ->get(['id', 'titre']);

        if ($largeCultes->count() > 0) {
            $this->warn('⚠️ Cultes avec du contenu volumineux détectés:');
            foreach ($largeCultes as $culte) {
                $this->line("- ID {$culte->id}: {$culte->titre}");
            }
        }
    }

    private function cleanupData()
    {
        $this->info('🧹 Nettoyage des données...');

        // Nettoyer les contenus vides ou avec seulement des espaces
        $cleaned = DB::table('cultes')
            ->where(function ($query) {
                $query->where('description', 'LIKE', '%<p></p>%')
                      ->orWhere('description', 'LIKE', '%<p> </p>%')
                      ->orWhere('resume_message', 'LIKE', '%<p></p>%')
                      ->orWhere('resume_message', 'LIKE', '%<p> </p>%');
            })
            ->update([
                'description' => DB::raw('CASE WHEN description LIKE "%<p></p>%" OR description LIKE "%<p> </p>%" THEN NULL ELSE description END'),
                'resume_message' => DB::raw('CASE WHEN resume_message LIKE "%<p></p>%" OR resume_message LIKE "%<p> </p>%" THEN NULL ELSE resume_message END'),
            ]);

        if ($cleaned > 0) {
            $this->info("✅ {$cleaned} enregistrement(s) nettoyé(s)");
        }

        // Nettoyer le cache
        $this->cacheService->clearAllCache();
        $this->info('✅ Cache CKEditor nettoyé');
    }

    private function warmCache()
    {
        $this->info('🔥 Pré-remplissage du cache...');

        $cultes = Culte::withContent()->limit(100)->get();
        $cached = 0;

        foreach ($cultes as $culte) {
            foreach ($culte->getCKEditorFields() as $field) {
                $content = $culte->getAttribute($field);
                if (!empty($content)) {
                    $key = "culte_{$culte->id}_{$field}";
                    $this->cacheService->cacheFormattedContent($key, $content);
                    $this->cacheService->cachePlainText($key, $content);
                    $cached++;
                }
            }
        }

        $this->info("✅ {$cached} éléments mis en cache");
    }
}
