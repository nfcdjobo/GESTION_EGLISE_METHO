<?php

namespace App\Exports;

use App\Models\Culte;
use App\Models\Parametres;
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
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class CulteExport implements WithMultipleSheets
{
    protected $culte;
    protected $fondsStatistiques;
    protected $metriques;
    protected $dateGeneration;
    protected $appParametres;

    public function __construct(Culte $culte, $fondsStatistiques = null, $metriques = null)
    {
        $this->culte = $culte;
        $this->fondsStatistiques = $fondsStatistiques ?? [];
        $this->metriques = $metriques ?? [];
        $this->dateGeneration = now()->format('d/m/Y H:i');
        $this->appParametres = Parametres::first();
    }

    public function sheets(): array
    {
        $sheets = [
            new CulteInformationsSheet($this->culte, $this->dateGeneration, $this->appParametres),
            new CulteStatistiquesSheet($this->culte, $this->fondsStatistiques, $this->metriques, $this->appParametres),
        ];

        if (!empty($this->fondsStatistiques['total_transactions'])) {
            $sheets[] = new CulteFinancesSheet($this->culte, $this->fondsStatistiques, $this->metriques, $this->appParametres);
        }

        return $sheets;
    }
}

// Trait pour gérer l'en-tête et le pied de page communs
trait HasHeaderFooter
{
    protected function applyHeaderFooter($sheet, $appParametres, $maxColumn, $startDataRow)
    {
        // EN-TÊTE - Ligne 1 à 4
        
        // Ajouter le logo si disponible
        if (!empty($appParametres->logo)) {
            try {
                $logoPath = storage_path('app/public/' . $appParametres->logo);
                
                if (file_exists($logoPath)) {
                    $drawing = new Drawing();
                    $drawing->setName('Logo');
                    $drawing->setDescription('Logo de l\'église');
                    $drawing->setPath($logoPath);
                    $drawing->setCoordinates('A1');
                    $drawing->setHeight(50); // Hauteur en pixels
                    $drawing->setOffsetX(5);
                    $drawing->setOffsetY(5);
                    $drawing->setWorksheet($sheet);
                    
                    // Ajuster la hauteur de la ligne pour le logo
                    $sheet->getRowDimension(1)->setRowHeight(40);
                }
            } catch (\Exception $e) {
                // Si erreur, continuer sans logo
            }
        }
        
        // Ligne 1: Nom de l'église (décalé pour laisser place au logo)
        $sheet->mergeCells("B1:{$maxColumn}1");
        $sheet->setCellValue('B1', strtoupper($appParametres->nom_eglise ?? 'ÉGLISE'));
        $sheet->getStyle("B1:{$maxColumn}1")->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '1E40AF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        
        // Fond bleu pour la colonne A aussi
        $sheet->getStyle('A1')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '1E40AF']],
        ]);
        $sheet->getColumnDimension('A')->setWidth(12); // Largeur pour le logo

        // Ligne 2: Contact gauche et droit
        $contactLeft = [];
        if (!empty($appParametres->telephone_1)) {
            $contactLeft[] = "Tel 1: {$appParametres->telephone_1}";
        }
        if (!empty($appParametres->telephone_2)) {
            $contactLeft[] = "Tel 2: {$appParametres->telephone_2}";
        }
        
        // Partie gauche (après le logo)
        $midCol = chr(ord('A') + floor((ord($maxColumn) - ord('A')) / 2));
        $sheet->mergeCells("A2:{$midCol}2");
        $sheet->setCellValue('A2', implode(' | ', $contactLeft));
        $sheet->getStyle("A2:{$midCol}2")->applyFromArray([
            'font' => ['size' => 9, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '1E40AF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // Partie droite (email)
        $afterMidCol = chr(ord($midCol) + 1);
        $sheet->mergeCells("{$afterMidCol}2:{$maxColumn}2");
        $sheet->setCellValue("{$afterMidCol}2", $appParametres->email ?? '');
        $sheet->getStyle("{$afterMidCol}2:{$maxColumn}2")->applyFromArray([
            'font' => ['size' => 9, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '1E40AF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // Ligne 3: Adresse
        $adresseComplete = $appParametres->adresse ?? '';
        if (!empty($appParametres->ville)) {
            $adresseComplete .= ', ' . $appParametres->ville;
        }
        if (!empty($appParametres->pays)) {
            $adresseComplete .= ', ' . $appParametres->pays;
        }

        $sheet->mergeCells("A3:{$maxColumn}3");
        $sheet->setCellValue('A3', $adresseComplete);
        $sheet->getStyle("A3:{$maxColumn}3")->applyFromArray([
            'font' => ['size' => 9, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '1E40AF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(18);

        // Ligne 4: Bordure décorative
        $sheet->mergeCells("A4:{$maxColumn}4");
        $sheet->getStyle("A4:{$maxColumn}4")->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'F59E0B']],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(4);

        // PIED DE PAGE - Calculer la dernière ligne
        $lastRow = $sheet->getHighestRow() + 2;

        // Ligne de séparation
        $sheet->mergeCells("A{$lastRow}:{$maxColumn}{$lastRow}");
        $sheet->getStyle("A{$lastRow}:{$maxColumn}{$lastRow}")->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '3B82F6']],
        ]);
        $sheet->getRowDimension($lastRow)->setRowHeight(3);
        $lastRow++;

        // Verset biblique
        $verset = $appParametres->verset_biblique ?? "Car Dieu a tant aimé le monde qu'il a donné son Fils unique...";
        $reference = $appParametres->reference_verset ?? "Jean 3:16";
        
        $sheet->mergeCells("A{$lastRow}:{$maxColumn}{$lastRow}");
        $sheet->setCellValue("A{$lastRow}", "\"{$verset}\" - {$reference}");
        $sheet->getStyle("A{$lastRow}")->applyFromArray([
            'font' => ['italic' => true, 'size' => 9, 'color' => ['rgb' => 'F59E0B']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '1F2937']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension($lastRow)->setRowHeight(20);
        $lastRow++;

        // Réseaux sociaux et date
        $social = [];
        if (!empty($appParametres->facebook_url)) {
            $social[] = "Facebook: {$appParametres->facebook_url}";
        }
        if (!empty($appParametres->instagram_url)) {
            $social[] = "Instagram: {$appParametres->instagram_url}";
        }
        
        $sheet->mergeCells("A{$lastRow}:{$maxColumn}{$lastRow}");
        $sheet->setCellValue("A{$lastRow}", implode(' | ', $social));
        $sheet->getStyle("A{$lastRow}")->applyFromArray([
            'font' => ['size' => 8, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '1F2937']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $lastRow++;

        // Dernière ligne avec plateforme et date de génération
        $footerText = '';
        if (!empty($appParametres->plateforme_url)) {
            $footerText = "Site web: {$appParametres->plateforme_url} | ";
        }
        $footerText .= "Généré le " . now()->format('d/m/Y H:i');

        $sheet->mergeCells("A{$lastRow}:{$maxColumn}{$lastRow}");
        $sheet->setCellValue("A{$lastRow}", $footerText);
        $sheet->getStyle("A{$lastRow}")->applyFromArray([
            'font' => ['size' => 8, 'color' => ['rgb' => '9CA3AF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '1F2937']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
    }
}

// Feuille des informations générales
class CulteInformationsSheet implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents, WithTitle
{
    use HasHeaderFooter;

    protected $culte;
    protected $dateGeneration;
    protected $appParametres;

    public function __construct(Culte $culte, $dateGeneration, Parametres $appParametres )
    {
        $this->culte = $culte;
        $this->dateGeneration = $dateGeneration;
        $this->appParametres = Parametres::first();
    }

    public function title(): string
    {
        return 'Informations Générales';
    }

    public function startCell(): string
    {
        return 'A8'; // Commencer après l'en-tête (lignes 1-7)
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
        ]);

        // Ajouter les officiants
        if ($this->culte->officiants_detail->isNotEmpty()) {
            $data->push(['', '']);
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

        $data->push(['', '']);
        $data->push(['MESSAGE', '']);
        $data->push(['Titre du message', $this->culte->titre_message ?? '']);
        $data->push(['Passage biblique', $this->culte->passage_biblique ?? '']);

        // Ajouter les statistiques de participation si disponibles
        if ($this->culte->nombre_participants) {
            $data = $data->concat([
                ['', ''],
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
                ['', ''],
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
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            8 => [
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

                // Appliquer l'en-tête et le pied de page
                $this->applyHeaderFooter($sheet, $this->appParametres, 'B', 8);

                // Titre du rapport - Ligne 6
                $sheet->mergeCells('A6:B6');
                $sheet->setCellValue('A6', 'RAPPORT DE CULTE');
                $sheet->getStyle('A6:B6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '1F2937']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(6)->setRowHeight(25);

                // Sous-titre - Ligne 7
                $sheet->mergeCells('A7:B7');
                $sheet->setCellValue('A7', $this->culte->titre . ' - ' . $this->culte->date_culte->format('d/m/Y'));
                $sheet->getStyle('A7')->applyFromArray([
                    'font' => ['size' => 11, 'color' => ['rgb' => '6B7280']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Styling des sections
                $currentRow = 8;
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
                $sheet->getStyle("A8:B{$lastRow}")->applyFromArray([
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
    use HasHeaderFooter;

    protected $culte;
    protected $fondsStatistiques;
    protected $metriques;
    protected $appParametres;

    public function __construct(Culte $culte, $fondsStatistiques, $metriques, Parametres $appParametres = null)
    {
        $this->culte = $culte;
        $this->fondsStatistiques = $fondsStatistiques;
        $this->metriques = $metriques;
        $this->appParametres = Parametres::first();
    }

    public function title(): string
    {
        return 'Statistiques';
    }

    public function startCell(): string
    {
        return 'A8';
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
            8 => [
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

                // Appliquer l'en-tête et le pied de page
                $this->applyHeaderFooter($sheet, $this->appParametres, 'C', 8);

                // Titre - Ligne 6
                $sheet->mergeCells('A6:C6');
                $sheet->setCellValue('A6', 'STATISTIQUES DÉTAILLÉES');
                $sheet->getStyle('A6:C6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '7C3AED']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(6)->setRowHeight(25);

                // Sous-titre - Ligne 7
                $sheet->mergeCells('A7:C7');
                $sheet->setCellValue('A7', $this->culte->titre);
                $sheet->getStyle('A7')->applyFromArray([
                    'font' => ['size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Styling des sections
                $currentRow = 8;
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
                        $sheet->getStyle("B{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    }
                    $currentRow++;
                }

                // Bordures
                $lastRow = $currentRow - 1;
                $sheet->getStyle("A8:C{$lastRow}")->applyFromArray([
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
    use HasHeaderFooter;

    protected $culte;
    protected $fondsStatistiques;
    protected $metriques;
    protected $appParametres;

    public function __construct(Culte $culte, $fondsStatistiques, $metriques, Parametres $appParametres )
    {
        $this->culte = $culte;
        $this->fondsStatistiques = $fondsStatistiques;
        $this->metriques = $metriques;
        $this->appParametres = Parametres::first();
    }

    public function title(): string
    {
        return 'Finances Détaillées';
    }

    public function startCell(): string
    {
        return 'A8';
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

        $data->push(['', '', '', '']);
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
            $data->push(['', '', '', '']);
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
            8 => [
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

                // Appliquer l'en-tête et le pied de page
                $this->applyHeaderFooter($sheet, $this->appParametres, 'D', 8);

                // Titre - Ligne 6
                $sheet->mergeCells('A6:D6');
                $sheet->setCellValue('A6', 'RAPPORT FINANCIER DÉTAILLÉ');
                $sheet->getStyle('A6:D6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '059669']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(6)->setRowHeight(25);

                // Résumé financier - Ligne 7
                $sheet->mergeCells('A7:D7');
                $totalFormate = number_format($this->fondsStatistiques['montant_total'], 0);
                $sheet->setCellValue('A7', "Total collecté: {$totalFormate} FCFA | {$this->fondsStatistiques['total_transactions']} transactions | {$this->fondsStatistiques['donateurs_uniques']} donateurs");
                $sheet->getStyle('A7')->applyFromArray([
                    'font' => ['size' => 11, 'bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Styling des sections et données
                $currentRow = 8;
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
                $sheet->getStyle("A8:D{$lastRow}")->applyFromArray([
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