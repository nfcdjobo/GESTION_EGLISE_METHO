<?php

namespace App\Exports;

use App\Models\Culte;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

class CulteExport implements WithMultipleSheets
{
    protected $culte;
    protected $fondsStatistiques;
    protected $metriques;
    protected $dateGeneration;

    public function __construct(Culte $culte, $fondsStatistiques = null, $metriques = null)
    {
        $this->culte = $culte;
        $this->fondsStatistiques = $fondsStatistiques ?? [];
        $this->metriques = $metriques ?? [];
        $this->dateGeneration = now()->format('d/m/Y H:i');
    }

    public function sheets(): array
    {
        $sheets = [
            new CulteInformationsSheet($this->culte, $this->dateGeneration),
            new CulteStatistiquesSheet($this->culte, $this->fondsStatistiques, $this->metriques),
        ];

        if (!empty($this->fondsStatistiques['total_transactions'])) {
            $sheets[] = new CulteFinancesSheet($this->culte, $this->fondsStatistiques, $this->metriques);
        }

        return $sheets;
    }
}

// Feuille des informations générales
class CulteInformationsSheet implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents, WithTitle
{
    protected $culte;
    protected $dateGeneration;

    public function __construct(Culte $culte, $dateGeneration)
    {
        $this->culte = $culte;
        $this->dateGeneration = $dateGeneration;
    }

    public function title(): string
    {
        return 'Informations Générales';
    }

    public function startCell(): string
    {
        return 'A6'; // Laisser de la place pour l'en-tête
    }

    public function collection()
    {
        $data = collect([
            ['INFORMATION', 'VALEUR'],
            ['Titre du culte', $this->culte->titre],
            ['Type de culte', $this->culte->type_culte_libelle],
            ['Catégorie', $this->culte->categorie_libelle],
            ['Date du culte', $this->culte->date_culte->format('d/m/Y')],
            ['Heure de début', $this->culte->heure_debut ? \Carbon\Carbon::parse($this->culte->heure_debut)->format('H:i') : ''],
            ['Heure de fin', $this->culte->heure_fin ? \Carbon\Carbon::parse($this->culte->heure_fin)->format('H:i') : ''],
            ['Lieu', $this->culte->lieu],
            ['Statut', $this->culte->statut_libelle],
            ['Public', $this->culte->est_public ? 'Oui' : 'Non'],
            ['Diffusion en ligne', $this->culte->diffusion_en_ligne ? 'Oui' : 'Non'],
            // ['', ''], // Ligne vide
            // ['RESPONSABLES', ''],
            // ['Pasteur principal', $this->culte->pasteurPrincipal?->nom_complet ?? 'Non assigné'],
            // ['Prédicateur', $this->culte->predicateur?->nom_complet ?? 'Non assigné'],
            // ['Responsable culte', $this->culte->responsableCulte?->nom_complet ?? 'Non assigné'],
            // ['Dirigeant louange', $this->culte->dirigeantLouange?->nom_complet ?? 'Non assigné'],
            // ['', ''], // Ligne vide
            // ['MESSAGE', ''],
            // ['Titre du message', $this->culte->titre_message ?? ''],
            // ['Passage biblique', $this->culte->passage_biblique ?? ''],
        ]);

        // Ajouter les officiants
if ($this->culte->officiants_detail->isNotEmpty()) {
    $data->push(['', '']); // Ligne vide
    $data->push(['OFFICIANTS', '']);
    
    foreach ($this->culte->officiants_detail as $officiant) {
        $provenance = ($officiant['provenance'] && $officiant['provenance'] !== 'Église Locale') 
            ? ' (' . $officiant['provenance'] . ')' 
            : '';
        $data->push([
            $officiant['titre'], 
            $officiant['nom_complet'] . $provenance
        ]);
    }
}

$data->push(['', '']); // Ligne vide
$data->push(['MESSAGE', '']);
$data->push(['Titre du message', $this->culte->titre_message ?? '']);
$data->push(['Passage biblique', $this->culte->passage_biblique ?? '']);

        // Ajouter les statistiques de participation si disponibles
        if ($this->culte->nombre_participants) {
            $data = $data->concat([
                ['', ''], // Ligne vide
                ['PARTICIPATION', ''],
                ['Total participants', number_format($this->culte->nombre_participants)],
                ['Adultes', number_format($this->culte->nombre_adultes ?? 0)],
                ['Jeunes', number_format($this->culte->nombre_jeunes ?? 0)],
                ['Enfants', number_format($this->culte->nombre_enfants ?? 0)],
                ['Nouveaux visiteurs', number_format($this->culte->nombre_nouveaux ?? 0)],
                ['Conversions', number_format($this->culte->nombre_conversions ?? 0)],
                ['Baptêmes', number_format($this->culte->nombre_baptemes ?? 0)],
            ]);
        }

        // Ajouter les évaluations si disponibles
        if ($this->culte->note_globale) {
            $data = $data->concat([
                ['', ''], // Ligne vide
                ['ÉVALUATIONS', ''],
                ['Note globale', $this->culte->note_globale . '/10'],
                ['Note louange', ($this->culte->note_louange ?? '') ? $this->culte->note_louange . '/10' : ''],
                ['Note message', ($this->culte->note_message ?? '') ? $this->culte->note_message . '/10' : ''],
                ['Note organisation', ($this->culte->note_organisation ?? '') ? $this->culte->note_organisation . '/10' : ''],
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return []; // Pas d'en-têtes car on gère tout dans collection()
    }

    public function styles(Worksheet $sheet)
    {
        return [
            6 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '3B82F6']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // En-tête principal
                $sheet->mergeCells('A1:B3');
                $sheet->setCellValue('A1', 'RAPPORT DE CULTE');
                $sheet->getStyle('A1:B3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 18, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '1F2937']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // Sous-titre
                $sheet->mergeCells('A4:B4');
                $sheet->setCellValue('A4', $this->culte->titre . ' - ' . $this->culte->date_culte->format('d/m/Y'));
                $sheet->getStyle('A4')->applyFromArray([
                    'font' => ['size' => 12, 'color' => ['rgb' => '6B7280']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Date de génération
                $sheet->setCellValue('A5', 'Généré le : ' . $this->dateGeneration);
                $sheet->getStyle('A5')->applyFromArray([
                    'font' => ['size' => 10, 'color' => ['rgb' => '9CA3AF'], 'italic' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Styling des sections
                $currentRow = 6;
                $data = $this->collection();

                foreach ($data as $row) {
                    if (in_array($row[0], ['OFFICIANTS', 'MESSAGE', 'PARTICIPATION', 'ÉVALUATIONS'])) {
                        $sheet->getStyle("A{$currentRow}:B{$currentRow}")->applyFromArray([
                            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '059669']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        ]);
                    } elseif ($row[0] !== '' && $row[0] !== 'INFORMATION') {
                        $sheet->getStyle("A{$currentRow}")->applyFromArray([
                            'font' => ['bold' => true],
                        ]);
                    }
                    $currentRow++;
                }

                // Bordures pour toutes les cellules utilisées
                $lastRow = $currentRow - 1;
                $sheet->getStyle("A6:B{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']],
                    ],
                ]);

                // Ajuster la largeur des colonnes
                $sheet->getColumnDimension('A')->setWidth(25);
                $sheet->getColumnDimension('B')->setWidth(35);
            },
        ];
    }
}

// Feuille des statistiques détaillées
class CulteStatistiquesSheet implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents, WithTitle
{
    protected $culte;
    protected $fondsStatistiques;
    protected $metriques;

    public function __construct(Culte $culte, $fondsStatistiques, $metriques)
    {
        $this->culte = $culte;
        $this->fondsStatistiques = $fondsStatistiques;
        $this->metriques = $metriques;
    }

    public function title(): string
    {
        return 'Statistiques';
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function collection()
    {
        $data = collect([
            ['MÉTRIQUE', 'VALEUR', 'UNITÉ'],
        ]);

        // Statistiques de participation
        if ($this->culte->nombre_participants) {
            $data = $data->concat([
                ['PARTICIPATION', '', ''],
                ['Total participants', $this->culte->nombre_participants, 'personnes'],
                ['Adultes', $this->culte->nombre_adultes ?? 0, 'personnes'],
                ['Jeunes', $this->culte->nombre_jeunes ?? 0, 'personnes'],
                ['Enfants', $this->culte->nombre_enfants ?? 0, 'personnes'],
                ['Nouveaux visiteurs', $this->culte->nombre_nouveaux ?? 0, 'personnes'],
                ['Conversions', $this->culte->nombre_conversions ?? 0, 'personnes'],
                ['Baptêmes', $this->culte->nombre_baptemes ?? 0, 'personnes'],
                ['', '', ''],
            ]);
        }

        // Statistiques financières si disponibles
        if (!empty($this->fondsStatistiques['total_transactions'])) {
            $data = $data->concat([
                ['FINANCES', '', ''],
                ['Montant total collecté', number_format($this->fondsStatistiques['montant_total'], 0), 'FCFA'],
                ['Nombre de transactions', $this->fondsStatistiques['total_transactions'], 'transactions'],
                ['Nombre de donateurs', $this->fondsStatistiques['donateurs_uniques'], 'donateurs'],
                ['Transaction moyenne', number_format($this->metriques['transaction_moyenne'] ?? 0, 0), 'FCFA'],
                ['', '', ''],
            ]);

            // Ratios par participant
            if ($this->culte->nombre_participants > 0) {
                $data = $data->concat([
                    ['RATIOS PAR PARTICIPANT', '', ''],
                    ['Offrande par participant', number_format($this->metriques['offrande_par_participant'] ?? 0, 0), 'FCFA'],
                    ['Dîme par participant', number_format($this->metriques['dime_par_participant'] ?? 0, 0), 'FCFA'],
                    ['Taux participation financière', $this->metriques['taux_participation_financiere'] ?? 0, '%'],
                    ['', '', ''],
                ]);
            }
        }

        // Horaires réels
        if ($this->culte->heure_debut_reelle || $this->culte->heure_fin_reelle) {
            $data = $data->concat([
                ['HORAIRES RÉELS', '', ''],
                ['Heure début réelle', $this->culte->heure_debut_reelle ? \Carbon\Carbon::parse($this->culte->heure_debut_reelle)->format('H:i') : '', ''],
                ['Heure fin réelle', $this->culte->heure_fin_reelle ? \Carbon\Carbon::parse($this->culte->heure_fin_reelle)->format('H:i') : '', ''],
                ['Durée totale', $this->culte->duree_totale ?? '', ''],
                ['', '', ''],
            ]);
        }

        // Évaluations
        if ($this->culte->note_globale) {
            $data = $data->concat([
                ['ÉVALUATIONS', '', ''],
                ['Note globale', $this->culte->note_globale, '/10'],
                ['Note louange', $this->culte->note_louange ?? '', '/10'],
                ['Note message', $this->culte->note_message ?? '', '/10'],
                ['Note organisation', $this->culte->note_organisation ?? '', '/10'],
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            6 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '7C3AED']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // En-tête
                $sheet->mergeCells('A1:C3');
                $sheet->setCellValue('A1', 'STATISTIQUES DÉTAILLÉES');
                $sheet->getStyle('A1:C3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '7C3AED']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                $sheet->mergeCells('A4:C4');
                $sheet->setCellValue('A4', $this->culte->titre);
                $sheet->getStyle('A4')->applyFromArray([
                    'font' => ['size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Styling des sections
                $currentRow = 6;
                $data = $this->collection();

                foreach ($data as $row) {
                    if (in_array($row[0], ['PARTICIPATION', 'FINANCES', 'RATIOS PAR PARTICIPANT', 'HORAIRES RÉELS', 'ÉVALUATIONS'])) {
                        $sheet->getStyle("A{$currentRow}:C{$currentRow}")->applyFromArray([
                            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'DC2626']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        ]);
                    } elseif ($row[0] !== '' && $row[0] !== 'MÉTRIQUE') {
                        $sheet->getStyle("A{$currentRow}")->applyFromArray([
                            'font' => ['bold' => true],
                        ]);
                        // Alignement des valeurs numériques à droite
                        $sheet->getStyle("B{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    }
                    $currentRow++;
                }

                // Bordures
                $lastRow = $currentRow - 1;
                $sheet->getStyle("A6:C{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']],
                    ],
                ]);

                // Largeur des colonnes
                $sheet->getColumnDimension('A')->setWidth(30);
                $sheet->getColumnDimension('B')->setWidth(20);
                $sheet->getColumnDimension('C')->setWidth(15);
            },
        ];
    }
}

// Feuille des finances détaillées
class CulteFinancesSheet implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents, WithTitle
{
    protected $culte;
    protected $fondsStatistiques;
    protected $metriques;

    public function __construct(Culte $culte, $fondsStatistiques, $metriques)
    {
        $this->culte = $culte;
        $this->fondsStatistiques = $fondsStatistiques;
        $this->metriques = $metriques;
    }

    public function title(): string
    {
        return 'Finances Détaillées';
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function collection()
    {
        $data = collect([
            ['Type de Transaction', 'Nombre', 'Montant (FCFA)', 'Pourcentage'],
        ]);

        // Répartition par type
        foreach ($this->fondsStatistiques['par_type'] as $type => $typeData) {
            $data->push([
                ucfirst(str_replace('_', ' ', $type)),
                $typeData['nombre'],
                number_format($typeData['montant'], 0),
                $typeData['pourcentage'] . '%'
            ]);
        }

        $data->push(['', '', '', '']); // Ligne vide
        $data->push(['MODES DE PAIEMENT', '', '', '']);

        // Répartition par mode de paiement
        foreach ($this->fondsStatistiques['par_mode_paiement'] as $mode => $modeData) {
            $data->push([
                ucfirst(str_replace('_', ' ', $mode)),
                $modeData['nombre'],
                number_format($modeData['montant'], 0),
                $modeData['pourcentage'] . '%'
            ]);
        }

        // Top donateurs
        if (!empty($this->fondsStatistiques['top_donateurs'])) {
            $data->push(['', '', '', '']); // Ligne vide
            $data->push(['TOP DONATEURS', '', '', '']);
            $data->push(['Donateur', 'Nb Dons', 'Montant Total', 'Rang']);

            foreach ($this->fondsStatistiques['top_donateurs'] as $index => $donateur) {
                $data->push([
                    $donateur['donateur'],
                    $donateur['nombre_dons'],
                    number_format($donateur['montant_total'], 0),
                    '#' . ($index + 1)
                ]);
            }
        }

        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            6 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '059669']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // En-tête
                $sheet->mergeCells('A1:D3');
                $sheet->setCellValue('A1', 'RAPPORT FINANCIER DÉTAILLÉ');
                $sheet->getStyle('A1:D3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '059669']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // Résumé financier
                $sheet->mergeCells('A4:D4');
                $totalFormate = number_format($this->fondsStatistiques['montant_total'], 0);
                $sheet->setCellValue('A4', "Total collecté: {$totalFormate} FCFA | {$this->fondsStatistiques['total_transactions']} transactions | {$this->fondsStatistiques['donateurs_uniques']} donateurs");
                $sheet->getStyle('A4')->applyFromArray([
                    'font' => ['size' => 12, 'bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Styling des sections et données
                $currentRow = 6;
                $data = $this->collection();

                foreach ($data as $row) {
                    if (in_array($row[0], ['MODES DE PAIEMENT', 'TOP DONATEURS'])) {
                        $sheet->getStyle("A{$currentRow}:D{$currentRow}")->applyFromArray([
                            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'F59E0B']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        ]);
                    } elseif ($row[0] === 'Type de Transaction' || $row[0] === 'Donateur') {
                        $sheet->getStyle("A{$currentRow}:D{$currentRow}")->applyFromArray([
                            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '059669']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        ]);
                    } elseif ($row[0] !== '') {
                        // Alignement des colonnes numériques
                        $sheet->getStyle("B{$currentRow}:D{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    }
                    $currentRow++;
                }

                // Bordures
                $lastRow = $currentRow - 1;
                $sheet->getStyle("A6:D{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']],
                    ],
                ]);

                // Largeur des colonnes
                $sheet->getColumnDimension('A')->setWidth(25);
                $sheet->getColumnDimension('B')->setWidth(12);
                $sheet->getColumnDimension('C')->setWidth(18);
                $sheet->getColumnDimension('D')->setWidth(15);
            },
        ];
    }
}
