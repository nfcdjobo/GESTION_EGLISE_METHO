<?php

namespace App\Services;

use App\Models\Fonds;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;

class ReceiptService
{
    /**
     * Générer un reçu fiscal en HTML
     */
    public function generateHTML(Fonds $fonds): string
    {
        // Charger les relations nécessaires
        $fonds->load(['donateur', 'culte', 'collecteur', 'validateur', 'projet']);

        return view('exports.receipts.fiscal-receipt', [
            'fonds' => $fonds,
            'egliseInfo' => $this->getChurchInfo(),
            'montantEnLettres' => $this->convertAmountToWords($fonds->montant, $fonds->devise)
        ])->render();
    }

    /**
     * Générer un reçu fiscal pour téléchargement direct (sans sauvegarde)
     */
    public function generateForDownload(Fonds $fonds): Response
    {
        $html = $this->generateHTML($fonds);
        $filename = 'recu_fiscal_' . $fonds->numero_recu;

        // Si DomPDF est disponible, générer un PDF
        if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
            $pdf->setPaper('A4', 'portrait');

            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '.pdf"',
                'Cache-Control' => 'private, max-age=0, must-revalidate',
                'Pragma' => 'no-cache'
            ]);
        }

        // Fallback HTML
        return response($html, 200, [
            'Content-Type' => 'text/html; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.html"',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
            'Pragma' => 'no-cache'
        ]);
    }

    /**
     * Envoyer le reçu par email (génération temporaire)
     */
    public function sendByEmailDirect(Fonds $fonds, string $email): bool
    {
        try {
            if (!class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
                throw new \Exception('DomPDF requis pour l\'envoi par email');
            }

            // Générer le PDF temporairement
            $html = $this->generateHTML($fonds);
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
            $pdf->setPaper('A4', 'portrait');
            $pdfContent = $pdf->output();

            // Créer un fichier temporaire
            $tempFile = tempnam(sys_get_temp_dir(), 'recu_fiscal_');
            file_put_contents($tempFile, $pdfContent);

            // Envoyer l'email avec la pièce jointe temporaire
            \Mail::send('emails.receipt', [
                'fonds' => $fonds,
                'egliseInfo' => $this->getChurchInfo()
            ], function ($message) use ($fonds, $email, $tempFile) {
                $message->to($email)
                       ->subject('Reçu fiscal - ' . $fonds->numero_recu)
                       ->attach($tempFile, [
                           'as' => 'recu_fiscal_' . $fonds->numero_recu . '.pdf',
                           'mime' => 'application/pdf'
                       ]);
            });

            // Supprimer le fichier temporaire
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }

            Log::info('Reçu envoyé par email', [
                'transaction_id' => $fonds->id,
                'numero_recu' => $fonds->numero_recu,
                'email' => $email
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Erreur envoi email reçu', [
                'error' => $e->getMessage(),
                'transaction_id' => $fonds->id,
                'email' => $email
            ]);

            return false;
        }
    }

    /**
     * Générer un duplicata pour téléchargement direct
     */
    public function generateDuplicateForDownload(Fonds $fonds, string $reason = ''): Response
    {
        if (!$fonds->recu_emis) {
            throw new \Exception('Aucun reçu original à dupliquer');
        }

        // Générer le HTML avec mention DUPLICATA
        $html = $this->generateHTML($fonds);
        $html = str_replace(
            'REÇU FISCAL POUR DON',
            'REÇU FISCAL POUR DON - DUPLICATA',
            $html
        );

        $filename = 'duplicata_recu_' . $fonds->numero_recu . '_' . now()->format('Ymd');

        if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
            $pdf->setPaper('A4', 'portrait');

            Log::info('Duplicata de reçu téléchargé', [
                'transaction_id' => $fonds->id,
                'numero_recu' => $fonds->numero_recu,
                'reason' => $reason
            ]);

            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '.pdf"',
                'Cache-Control' => 'private, max-age=0, must-revalidate',
                'Pragma' => 'no-cache'
            ]);
        }

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.html"',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
            'Pragma' => 'no-cache'
        ]);
    }

    /**
     * Obtenir les informations de l'église
     */
    private function getChurchInfo(): array
    {
        return [
            'nom' => config('church.name'),
            'adresse' => config('church.address'),
            'telephone' => config('church.phone'),
            'email' => config('church.email'),
            'website' => config('church.website'),
            'logo' => config('church.logo'),
            'responsable_financier' => config('church.financial_manager.name'),
            'titre_responsable' => config('church.financial_manager.title'),
        ];
    }

    /**
     * Convertir un montant en lettres
     */
    public function convertAmountToWords(float $amount, string $currency = 'XOF'): string
    {
        // Tableaux des nombres en lettres
        $units = ['', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf'];
        $tens = ['', '', 'vingt', 'trente', 'quarante', 'cinquante', 'soixante', 'soixante', 'quatre-vingt', 'quatre-vingt'];
        $teens = ['dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize', 'dix-sept', 'dix-huit', 'dix-neuf'];

        // Fonction pour convertir un nombre entre 0 et 999
        $convertHundreds = function($number) use ($units, $tens, $teens) {
            $result = '';

            // Centaines
            $hundreds = intval($number / 100);
            if ($hundreds > 0) {
                $result .= ($hundreds == 1) ? 'cent' : $units[$hundreds] . ' cent';
                if ($number % 100 > 0) {
                    $result .= ' ';
                } elseif ($hundreds > 1) {
                    $result .= 's';
                }
            }

            // Dizaines et unités
            $remainder = $number % 100;
            if ($remainder >= 10 && $remainder <= 19) {
                $result .= $teens[$remainder - 10];
            } else {
                $ten = intval($remainder / 10);
                $unit = $remainder % 10;

                if ($ten > 0) {
                    if ($ten == 7 || $ten == 9) {
                        $result .= $tens[$ten - 1] . '-' . $teens[$unit];
                    } else {
                        $result .= $tens[$ten];
                        if ($unit > 0) {
                            $result .= ($ten == 8 && $unit == 1) ? '-un' : '-' . $units[$unit];
                        } elseif ($ten == 8) {
                            $result .= 's';
                        }
                    }
                } elseif ($unit > 0) {
                    $result .= $units[$unit];
                }
            }

            return $result;
        };

        // Convertir le montant
        $integerAmount = intval($amount);

        if ($integerAmount == 0) {
            return 'zéro ' . $this->getCurrencyLabel($currency, false);
        }

        $result = '';

        // Millions
        $millions = intval($integerAmount / 1000000);
        if ($millions > 0) {
            $result .= ($millions == 1) ? 'un million' : $convertHundreds($millions) . ' millions';
            $integerAmount %= 1000000;
            if ($integerAmount > 0) $result .= ' ';
        }

        // Milliers
        $thousands = intval($integerAmount / 1000);
        if ($thousands > 0) {
            $result .= ($thousands == 1) ? 'mille' : $convertHundreds($thousands) . ' mille';
            $integerAmount %= 1000;
            if ($integerAmount > 0) $result .= ' ';
        }

        // Centaines, dizaines, unités
        if ($integerAmount > 0) {
            $result .= $convertHundreds($integerAmount);
        }

        // Ajouter la devise
        $result .= ' ' . $this->getCurrencyLabel($currency, $integerAmount > 1);

        return ucfirst(trim($result));
    }

    /**
     * Obtenir le libellé de la devise
     */
    private function getCurrencyLabel(string $currency, bool $plural): string
    {
        return match($currency) {
            'XOF' => $plural ? 'francs CFA' : 'franc CFA',
            'EUR' => $plural ? 'euros' : 'euro',
            'USD' => $plural ? 'dollars' : 'dollar',
            default => $currency
        };
    }

    /**
     * Valider qu'un reçu peut être généré
     */
    public function canGenerateReceipt(Fonds $fonds): array
    {
        $errors = [];

        if ($fonds->statut !== 'validee') {
            $errors[] = 'La transaction doit être validée avant la génération du reçu';
        }

        if (!$fonds->recu_demande) {
            $errors[] = 'Le reçu fiscal n\'a pas été demandé pour cette transaction';
        }

        if (!$fonds->deductible_impots) {
            $errors[] = 'Cette transaction n\'est pas déductible des impôts';
        }

        if ($fonds->montant <= 0) {
            $errors[] = 'Le montant de la transaction doit être positif';
        }

        return $errors;
    }

    /**
     * Statistiques des reçus (sans fichiers sauvegardés)
     */
    public function getReceiptStatistics(int $year = null): array
    {
        $year = $year ?? now()->year;

        return [
            'total_reçus_generables' => Fonds::where('recu_demande', true)
                ->where('statut', 'validee')
                ->whereYear('date_transaction', $year)
                ->count(),

            'montant_total_reçus' => Fonds::where('recu_demande', true)
                ->where('statut', 'validee')
                ->whereYear('date_transaction', $year)
                ->sum('montant'),

            'reçus_avec_numero' => Fonds::whereNotNull('numero_recu')
                ->where('recu_emis', true)
                ->whereYear('created_at', $year)
                ->count(),

            'par_mois' => Fonds::where('recu_demande', true)
                ->where('statut', 'validee')
                ->whereYear('date_transaction', $year)
                ->selectRaw('MONTH(date_transaction) as mois, COUNT(*) as nombre, SUM(montant) as montant')
                ->groupBy('mois')
                ->orderBy('mois')
                ->get(),

            'par_type_transaction' => Fonds::where('recu_demande', true)
                ->where('statut', 'validee')
                ->whereYear('date_transaction', $year)
                ->selectRaw('type_transaction, COUNT(*) as nombre, SUM(montant) as montant')
                ->groupBy('type_transaction')
                ->orderBy('montant', 'desc')
                ->get()
        ];
    }


}
